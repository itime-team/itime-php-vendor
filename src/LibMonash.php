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

        $ret = new ITimeRet();
        if(!array_key_exists('Set-Cookie', $response->getHeaders())){
            $ret->status = -1;
            $ret->info = 'incorrect username or password';
            return $ret;
        }
        $ret->status = 1;
        $ret->info = 'success';
        return $ret;
    }


    public function fetch(){
        $timetableUrl = 'https://my.monash.edu.au/json/app/timetable/';
        $this->reqHeaders = [
            'headers' => ['User-Agent' => 'Monash/1.7.1 (iPhone; iOS 9.3.2; Scale/2.00)'],
        ];
        $response = $this->client->get($timetableUrl, $this->reqHeaders);
        $contents = $response->getBody()->getContents();
        var_dump($contents);
        $jsonObj = json_decode($contents);
        $ret = new ITimeRet();
        if(!property_exists($jsonObj, 'timetable')){
            $ret->status = -1;
            $ret->info = 'uni server error';
            return $ret; 
        }
        $resultArr = [];
        $timetableCount = count($jsonObj->timetable);
        date_default_timezone_set('Australia/Melbourne');
        for($i = 0; $i < $timetableCount; $i++){
            $timetable = $jsonObj->timetable[$i];
            $datesCount = count($timetable->dates);
            $durationTimestamp = (int)$timetable->duration * 60;
            for($j = 0; $j < $datesCount; $j++){
                $date = $timetable->dates[$j];
                $event = (object)[];
                $event->startTime = $date . 'T' . $timetable->startTime;
                $date = new \DateTime($event->startTime);
                $event->startTime = date('c', $date->getTimestamp()); 
                $endTimestamp = $date->getTimestamp() + $durationTimestamp;
                $event->endTime = date('c', $endTimestamp);
                array_push($resultArr, $event);
            }
        }

        // var_dump($resultArr);
        $ret->status = 1;
        $ret->info = 'success';
        $ret->data = $resultArr;
        return $ret;
    }

}

