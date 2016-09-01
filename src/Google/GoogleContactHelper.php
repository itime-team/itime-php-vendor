<?php
namespace ITime\Google;

use \Google_Client;
use \Google_Http_Request;
use \SimpleXMLElement;
class GoogleContactHelper{
    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    public function fetch(){
        $client = $this->client;
        $accessTokenJson = $client->getAccessToken();
        $arr = json_decode($accessTokenJson, true);
        $url = 'https://www.google.com/m8/feeds/contacts/default/full?';
        $url .= 'oauth_token='.$arr['access_token'];

        $req = new Google_Http_Request($url);
        $req->setRequestHeaders(array('GData-Version'=> '3.0','content-type'=>'application/atom+xml; charset=UTF-8; type=feed'));

        list($body, $head, $code) = $client->getIo()->executeRequest($req);
        if($code != 200){
            return false;
        }
        $ret = [];
        $xml = new SimpleXMLElement($body);
        if(!$xml->entry){
            return $ret;
        }
        foreach ($xml->entry as $entry) {
            $name = $entry->title->__toString();
            $address = $entry->xpath('gd:email')[0]['address']->__toString();
            if(empty($name)){
                $name = substr($address, 0, strpos($address, '@'));
            }
            $contact = array(
                'name' => $name,
                'email' => $address
            );
            array_push($ret, $contact);
        }
        return $ret;
    }


}