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
        try{
            $response = $this->client->post('/auth/app/login', $this->reqHeaders);
        } catch (ClientException $ce){
            $statusCode = $ce->getResponse()->getStatusCode();
            $statusMsg = $ce->getResponse()->getReasonPhrase();
            return array(
                'status' => -1,
                'info' => $statusMsg
                );
        } catch (Exception $e){
            // other error
            return array(
                'status' => -2,
                'info' => 'server error'
                );
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
            return array(
                'status' => -3,
                'info' => $statusMsg
                );
        } catch (Exception $e){
            // other error
            return array(
                'status' => -4,
                'info' => 'server error'
                );
        }

        return array(
            'status' => 1,
            'info' => 'success'
            );
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


