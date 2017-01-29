<?php

namespace ITime\UniCalendar;

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
        $ret = new ITimeRet();
        try{
            $response = $this->client->post('/auth/app/login', $this->reqHeaders);

            $this->reqHeaders['body'] = json_encode([
                'username'=>$userId, 
                'password'=>$password, 
                'platform'=>'ios', 
                'device'=>''
            ]);
            $response = $this->client->post('/auth/user/login', $this->reqHeaders);
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

        $ret->status = 1;
        $ret->info = 'success';
        return $ret;
    }

    public function fetch(){
        $this->reqHeaders['body'] = '';
        $timestamp = time();
        $this->reqHeaders['query'] = ['lastSyncTime'=>'', '_'=> $timestamp];
        $ret = new ITimeRet();
        try {
            $response = $this->client->get('services/classTimetable', $this->reqHeaders);
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
        $numClasses = count($jsonObj->classes); // print($numClasses);
        $events = [];
        for($i = 0; $i < $numClasses; $i++){
            $classObj = $jsonObj->classes[$i];
            // var_dump($classObj); return;

            $numTimetables = count($classObj->classTimetables);
            for($j = 0; $j < $numTimetables; $j++){
                $event = [];
                $event['summary'] = $classObj->fullTitle;

                $timetableObj = $classObj->classTimetables[$j];
                $address = $timetableObj->locationDescription . ' Campus; ';
                $address .= 'Building '.$timetableObj->buildingName;
                $address .= ' ('.$timetableObj->buildingNumber.'), ';
                $address .= 'Room '.$timetableObj->roomName;

                $event['startTime'] = strtotime($timetableObj->startDatetime)*1000;
                $event['endTime'] = strtotime($timetableObj->endDatetime)*1000;
                $event['location'] = $address;
                $event['invitee'] = [];
                $event['recurrence'] = [];

                array_push($events, $event);
            }

            $numTimetables = count($classObj->classTimetables);
        }

        $ret->status = 1;
        $ret->info = 'success';
        $ret->data = $events;
        return $ret;
    }
}


