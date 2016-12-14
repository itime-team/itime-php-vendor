<?php
namespace ITime\Test;
use ITime\UniCalendar\ITimeFactory;
use ITime\Google\GoogleContactHelper;
use ITime\Google\GoogleCalendarHelper;

use \Google_Client;
use \Google_Service_Calendar;
class CalendarTest extends \PHPUnit_Framework_TestCase{
    private $accessToken;
    public function __construct(){
        parent::__construct();
        $this->accessToken = '{"access_token":"ya29.Ci9QA-PB8hP8iubS5KzGf3HIU8U92qOxbE1NMYR-8CnMjLxrhI1fhYOxT791hOwpBw","token_type":"Bearer","expires_in":3600,"id_token":"eyJhbGciOiJSUzI1NiIsImtpZCI6ImVmMDgyMWExNWU4M2Q3YjM5MjU4ZTgyZTlmNWVhYjFlNzIzOGY4OTUifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6Ik5LM1dSSk0zY1ZVMUIxMkdYSHRlaUEiLCJhdWQiOiIxMDU4Nzk0ODQwMTE2LThoMmE0MGp2N2dvNWo4YjM3NmR1b3F1N3I5cGhnM29iLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTEyMzUzMjQ3MDczODc0MDc5OTQ1IiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF6cCI6IjEwNTg3OTQ4NDAxMTYtOGgyYTQwanY3Z281ajhiMzc2ZHVvcXU3cjlwaGczb2IuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJlbWFpbCI6ImpvaG5jZHlpbkBnbWFpbC5jb20iLCJpYXQiOjE0NzI2Mjg2MTcsImV4cCI6MTQ3MjYzMjIxN30.G0udImb_dlMIez1zHSjVm6uXZnjr-KK5ubHkp1hH3cDSb3OSdYlkvZLaSYPwgLC3i-KD6JHQVrjIeZ5s3BtGYDcTKJxLBC6gOWRYcEFMs6vyq7qypVTxswfj7CYYTTkjpXOCrLOob9EFaxNH-MkPg6V4ooV-PqPDxxt_ZtEh31tkhEZAtJuZV4YUq5OlNVGBWR1n3KLtR4466HYRX34Xbk7v11FffZJx9bOUwTmrXEGuCxJFwTG4V-EM2UzxGK5cv2rN1V_EloGY4OgJnr9Txih7DRJ2dNSwUnhDcbYg9e4z-9jtocFpfbpq2Mz3Al3KSV9tXFVXR0FvD1fP9TVd5w","refresh_token":"1\/XP8yXL85cca_wU8d0y2pSagLMOJD1P1DAOqdgMm-Hyk","created":1472628617}';

    }

    public function testUniMelb(){
        $cls = ITimeFactory::create(ITimeFactory::$LIB_UNIMELB);
        $ret = $cls->login('mingyanx', 'xmy15234');
        // $ret = $cls->fetch();
        var_dump($ret);
        // $this->assertEquals($ret->status, 1);

    }

    public function testMonash(){
        // $cls = ITimeFactory::create(ITimeFactory::$LIB_MONASH);
        // $ret = $cls->login('xli531', 'Lx19930102');
        // $ret = $cls->fetch();
        // $this->assertEquals($ret->status, 1);
    }

    public function testRMIT(){
        // $cls = ITimeFactory::create(ITimeFactory::$LIB_RMIT);
        // $ret = $cls->login('s3463979', '123123');
        // var_dump($ret);
        // $ret = $cls->fetch();
        // $this->assertEquals($ret->status, 1);
    }

    public function testTimeFormat(){
        // date_default_timezone_set('Australia/Melbourne');
        // $rfc_1123_date = date('c', time());
        // echo $rfc_1123_date.'      ';
        // $date = new \DateTime('2016-08-25T14:15+10:00');
        // echo $date->getTimestamp();
        // $rfc_1123_date = date('c', $date->getTimestamp());
        // echo $rfc_1123_date.'      ';
    }

    // public function testGoogleContact(){
    //     $client = new Google_Client();
    //     $dirname = dirname(__FILE__);
    //     $client->setAuthConfigFile($dirname . '/client_secrets.json');
    //     $client->setScopes(array(
    //         'https://apps-apis.google.com/a/feeds/groups/',
    //         'https://apps-apis.google.com/a/feeds/alias/',
    //         'https://apps-apis.google.com/a/feeds/user/',
    //         'https://www.google.com/m8/feeds/',
    //         'https://www.google.com/m8/feeds/user/',
    //         Google_Service_Calendar::CALENDAR
    //     ));
    //     $client->setAccessType('offline');
    //     $client->setAccessToken($this->accessToken);
    //     if($client->isAccessTokenExpired()){
    //         $refreshToken = $client->getRefreshToken();
    //         $client->refreshToken($refreshToken);
    //     }
    //     $cls = new GoogleContactHelper($client);
    //     $ret = $cls->fetch();
    //     var_dump($ret);
    //     $this->assertGreaterThan(count($ret), 17);
    // }


    public function testGoogleCalendar(){
        // $client = new Google_Client();
        // $dirname = dirname(__FILE__);
        // $client->setAuthConfigFile($dirname . '/client_secrets.json');
        // $client->setScopes(array(
        //     'https://apps-apis.google.com/a/feeds/groups/',
        //     'https://apps-apis.google.com/a/feeds/alias/',
        //     'https://apps-apis.google.com/a/feeds/user/',
        //     'https://www.google.com/m8/feeds/',
        //     'https://www.google.com/m8/feeds/user/',
        //     Google_Service_Calendar::CALENDAR
        // ));
        // $client->setAccessType('offline');
        // $client->setAccessToken($this->accessToken);
        // if($client->isAccessTokenExpired()){
        //     $refreshToken = $client->getRefreshToken();
        //     $client->refreshToken($refreshToken);
        // }
        // $cls = new GoogleCalendarHelper($client);
        // $ret = $cls->fetch();
        // file_put_contents($dirname. '/google_calendar.json', json_encode($ret));
        // // var_dump($ret);
        // $this->assertGreaterThan(count($ret), 5);

    }





















}
