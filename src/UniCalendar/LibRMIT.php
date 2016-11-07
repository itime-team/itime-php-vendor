<?php

namespace ITime\UniCalendar;

use \GuzzleHttp\Cookie\CookieJar;
use \GuzzleHttp\Client;
use \GuzzleHttp\Exception\ClientException;
use ITime\Util\simple_html_dom;
use Sunra\PhpSimple\HtmlDomParser;


class LibRMIT implements ITimeCalendar{
    private $jar;
    private $client;
    private $reqHeaders;

    public function __construct(){
        $this->jar = new CookieJar;
        $this->client = new Client(['cookies'=>$this->jar]);
        $this->reqHeaders = ['headers'=>[
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36',
            ]
        ]; 
    }

    public function login($userId, $password){
        $baseUrl = 'https://sso-cas.rmit.edu.au';
        $ret = new ITimeRet();
        $loginForm = [
            'username' => $userId,
            'password' => $password,
            'lt' => '',
            'execution' => 'e1s1', // or e1s2
            '_eventId' => 'submit',
            'submit' => 'Login'
        ];
        try{
            $response = $this->client->get($baseUrl.'/rmitcas/login');
            $content = $response->getBody()->getContents();
            $dom = HtmlDomParser::str_get_html($content);
            $lt = $dom->find('[name=lt]')[0];
            $loginForm['lt'] = $lt->value;

            $action = $dom->find('#fm1')[0]->action;
            $this->reqHeaders['allow_redirects'] = true;
            $this->reqHeaders['form_params'] = $loginForm;
            $response = $this->client->post($baseUrl.$action, $this->reqHeaders);
            $content = $response->getBody()->getContents();
            $dom = HtmlDomParser::str_get_html($content);
            $tagErrors = $dom->find('div#status');
            if(count($tagErrors) > 0){
                $ret->status = -3;
                $ret->info = $tagErrors[0]->plaintext;
            }else{
                $ret->status = 1;
                $ret->info = 'success';
            }
        } catch (ClientException $ce){
            $statusCode = $ce->getResponse()->getStatusCode();
            $statusMsg = $ce->getResponse()->getReasonPhrase();
            $ret->status = -1;
            $ret->info = $statusMsg;
        } catch (Exception $e){
            $ret->status = -2;
            $ret->info = 'server error';
        }
        return $ret;
    }

    public function fetch(){
        $baseUrl = 'https://my.rmit.edu.au/';
        $this->reqHeaders['body'] = '';
        $ret = new ITimeRet();
        try {
            $timestamp = round(microtime(true) * 1000);
            $timetableUrl = $baseUrl.'/service/myclasstimetable?time='.$timestamp;
            $response = $this->client->get($timetableUrl, $this->reqHeaders);
        } catch (ClientException $ce){
            $statusCode = $ce->getResponse()->getStatusCode();
            $statusMsg = $ce->getResponse()->getReasonPhrase();
            $ret->status = -1;
            $ret->info = $statusMsg;
            return $ret;
        } catch (Exception $e){
            // other error
            $ret->status = -2;
            $ret->info = 'server error';
            return $ret;
        }

        $contents = $response->getBody()->getContents();
        $jsonObj = json_decode($contents);
        if(!property_exists($jsonObj, 'classes')){
            $ret->status = -3;
            $ret->info = 'uni server error';
            return $ret;
        }
        $classesCount = count($jsonObj->classes);
        $resultArr = [];
        for($i = 0; $i < $classesCount; $i++){
            $classObj = $jsonObj->classes[$i];
            // echo $classObj->fullTitle .'\n\r';
            $timetableCount = count($classObj->classTimetables);
            for($j = 0; $j < $timetableCount; $j++){
                $timetableObj = $classObj->classTimetables[$j];
                $address = $timetableObj->locationDescription . ' Campus; ';
                $address .= 'Building '.$timetableObj->buildingName;
                $address .= ' ('.$timetableObj->buildingNumber.'), ';
                $address .= 'Room '.$timetableObj->roomName;
                $event = (object)[];
                $event->title = $classObj->fullTitle;
                $event->startTime = $timetableObj->startDatetime;
                $event->endTime = $timetableObj->endDatetime;
                $event->address = $address;
                array_push($resultArr, $event);
            }
        }

        $ret->status = 1;
        $ret->info = 'success';
        $ret->data = $resultArr;
        return $ret;
    }
}


