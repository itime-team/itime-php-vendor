<?php

namespace ITime\Calendar;

use \GuzzleHttp\Cookie\CookieJar;
use \GuzzleHttp\Client;
use \GuzzleHttp\Exception\ClientException;

class LibMonash implements ITimeCalendar{

    private $jar;
    private $client;
    private $reqHeaders;

    public function __construct(){
        $this->jar = new CookieJar;
        $this->client = new Client(['cookies'=>$this->jar]);
        $this->reqHeaders = [
            'headers' => ['User-Agent' => 'Monash/1.7.1 (iPhone; iOS 9.3.2; Scale/2.00)'],
            'allow_redirects' => false
        ]; 
    }

    public function login($userId, $password){
        // to get the real login url by this request
        $response = $this->client->get('https://my.monash.edu.au/', $this->reqHeaders);
        $loginUrl = $response->getHeaders()['Location'][0];
        $loginForm = [
            'AuthMehod' => 'FormsAuthentication',
            'Kmsi' => 'true',
            'UserName' => 'Monash\\'.$userId,
            'Password' => $password,
        ];
        $this->reqHeaders['allow_redirects'] = true;
        $this->reqHeaders['form_params'] = $loginForm;
        $response = $this->client->post($loginUrl, $this->reqHeaders);
        if(!array_key_exists('Set-Cookie', $response->getHeaders())){
            return 'failed'; 
        }
        return 'login';
    }


    public function fetch(){
        $timetableUrl = 'https://my.monash.edu.au/json/app/timetable/';
        $this->reqHeaders = [
            'headers' => ['User-Agent' => 'Monash/1.7.1 (iPhone; iOS 9.3.2; Scale/2.00)'],
        ];
        $response = $this->client->get($timetableUrl, $this->reqHeaders);
        $contents = $response->getBody()->getContents();
        var_dump($contents);
        return 'fetch';
    }

}

