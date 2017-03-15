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
        // var_dump('constructing ITime\Test\CalendarTest');
        parent::__construct();
        $dirname = dirname(__FILE__);
        
        // $userTokenFilename = $dirname . '/xiaojiew1_tokens.json';
        // $userTokenFilename = $dirname . '/xiaojiew94_tokens.json';
        // $this->accessToken = file_get_contents($userTokenFilename);
        // $this->eventSyncTokenFilename = $dirname . '/event_sync_tokens.json';
        // $this->eventSyncTokens = json_decode(file_get_contents($this->eventSyncTokenFilename), true);
        // var_dump($this->eventSyncTokens['xiaojiew94@gmail.com']);
        // file_put_contents($eventSyncTokenFilename, json_encode($this->eventSyncTokens));
    }

    public function testUniMelb(){
        $cls = ITimeFactory::create(ITimeFactory::$LIB_UNIMELB);
        // var_dump(gettype($cls), get_class($cls));
        
        // $loginRet = $cls->login('xiaojiew1', 'wxj2016!');
        $loginRet = $cls->login('mingyanx', 'wrong');
        var_dump($loginRet);
        
        $loginRet = $cls->login('mingyanx', 'xmy15234');
        var_dump($loginRet);
        
        // $fetchRet = $cls->fetch();
        // $events = $fetchRet->data;
        // var_dump(count($events));
        
        // $this->assertEquals($loginRet->status, 1);
        // $this->assertEquals($loginRet->info, 'success');
        // $this->assertEquals($fetchRet->status, 1);
        // $this->assertEquals($fetchRet->info, 'success');
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


    public function testGoogleCalendar() {
        return;

        var_dump('testGoogleCalendar');
        $client = new Google_Client();
        $dirname = dirname(__FILE__);
        $client->setAuthConfigFile($dirname . '/client_secrets.json');
        $client->setScopes(array(
            'https://apps-apis.google.com/a/feeds/groups/',
            'https://apps-apis.google.com/a/feeds/alias/',
            'https://apps-apis.google.com/a/feeds/user/',
            'https://www.google.com/m8/feeds/',
            'https://www.google.com/m8/feeds/user/',
            Google_Service_Calendar::CALENDAR
        ));
        $client->setAccessType('offline');
        $client->setAccessToken($this->accessToken);
        if($client->isAccessTokenExpired()){
            // var_dump('refresh token');
            $refreshToken = $client->getRefreshToken();
            $client->refreshToken($refreshToken);
        }

        // add to main project
        $cls = new GoogleCalendarHelper($client);
        $items = $cls->fetchCalendars(); // var_dump($items);

        foreach ($items as $item) {
            $iCalUID = $item['iCalUID']; var_dump('iCalUID: '.$iCalUID);
            $extra = '{"sync_token":""}';
            $extra = json_decode($extra);
            $eventSyncToken = $extra->sync_token;

            // add to main project
            list($events, $eventSyncToken) = $cls->fetchEvents($iCalUID, $eventSyncToken);

            $count = count($events);
            var_dump($count.' events in total');
            foreach ($events as $event) {
                var_dump($event['summary']);
                var_dump($event['startTime']);
                var_dump($event['endTime']);
                var_dump('--------------------------------');
            }
        }

        // list($ret, $syncTokens) = $cls->fetch($this->eventSyncTokens);
        // file_put_contents($this->eventSyncTokenFilename, json_encode($syncTokens));
        // var_dump($ret);
        // file_put_contents($dirname. '/google_calendar.json', json_encode($ret));
        // // var_dump($ret);
        // $this->assertGreaterThan(count($ret), 5);

    }

}
