<?php

namespace ITime\Calendar;

use \GuzzleHttp\Cookie\CookieJar;
use \GuzzleHttp\Client;
use \GuzzleHttp\Exception\ClientException;

class LibUniMelb implements ITimeCalendar{
    private $jar;
    private $client;
    private $reqHeaders;

    public function __construct(){
        $this->jar = new CookieJar;
        $this->client = new Client(['cookies'=>$this->jar, 'base_uri' => 'https://api.its.unimelb.edu.au']);
        $this->reqHeaders = ['headers'=>['Content-Type'=>'application/json']]; 
    }

    public function login($userId, $password){
        $appParam = ['appkey' => 'mobile', 'appsecret' => 'mobile'];
        $this->reqHeaders['body'] = json_encode($appParam);
        try{
            $response = $this->client->post('/auth/app/login', $this->reqHeaders);
        } catch (ClientException $ce){
            $statusCode = $ce->getResponse()->getStatusCode();
            $statusMsg = $ce->getResponse()->getReasonPhrase();
            var_dump($ce);
        } catch (Exception $e){
            // other error
        }

        $this->reqHeaders['body'] = json_encode([
            'username'=>$userId, 
            'password'=>$password, 
            'platform'=>'ios', 
            'device'=>'']);
        try{
            $response = $this->client->post('/auth/user/login', $this->reqHeaders);
        } catch (ClientException $ce){
            $statusCode = $ce->getResponse()->getStatusCode();
            $statusMsg = $ce->getResponse()->getReasonPhrase();
            var_dump($ce);
        } catch (Exception $e){
            // other error
        }

        return 'login';
    }

    public function fetch(){
        $this->reqHeaders['body'] = '';
        $timestamp = time();
        $this->reqHeaders['query'] = ['lastSyncTime'=>'', '_'=> $timestamp];
        try {
            $response = $this->client->get('services/classTimetable', $this->reqHeaders);
        } catch (ClientException $ce){
            $statusCode = $ce->getResponse()->getStatusCode();
            $statusMsg = $ce->getResponse()->getReasonPhrase();
            var_dump($ce);
        } catch (Exception $e){
            // other error
            var_dump($e);
        }

        $contents = $response->getBody()->getContents();
        var_dump(json_decode($contents, true));
        return 'fetch';
    }
}


