<?php
namespace ITime\Test;
use ITime\UniCalendar\ITimeFactory;
use ITime\Google\GoogleContactHelper;
use \Google_Client;
class CalendarTest extends \PHPUnit_Framework_TestCase{

    public function testUniMelb(){
        // $cls = ITimeFactory::create(ITimeFactory::$LIB_UNIMELB);
        // $ret = $cls->login('mingyanx', 'xmy15234');
        // $ret = $cls->fetch();
        // $this->assertEquals($ret->status, 1);
    }


    public function testMonash(){
        // $cls = ITimeFactory::create(ITimeFactory::$LIB_MONASH);
        // $ret = $cls->login('xli531', 'Lx19930102');
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

    public function testGoogleContact(){
        $cls = new GoogleContactHelper();
        $accessToken = '{"access_token":"ya29.CjFQA1pJNW_kpIrCMinn_I6F8JiyXwYPZzG05Kp4kLvfwqeXoAsRkQ3-EROE435mACX3","token_type":"Bearer","expires_in":3579,"id_token":"eyJhbGciOiJSUzI1NiIsImtpZCI6ImVmMDgyMWExNWU4M2Q3YjM5MjU4ZTgyZTlmNWVhYjFlNzIzOGY4OTUifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6Ik90azNvX1dpaWJHdXFmWnhXVGw3dUEiLCJhdWQiOiIxMDU4Nzk0ODQwMTE2LThoMmE0MGp2N2dvNWo4YjM3NmR1b3F1N3I5cGhnM29iLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTEyMzUzMjQ3MDczODc0MDc5OTQ1IiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF6cCI6IjEwNTg3OTQ4NDAxMTYtOGgyYTQwanY3Z281ajhiMzc2ZHVvcXU3cjlwaGczb2IuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJlbWFpbCI6ImpvaG5jZHlpbkBnbWFpbC5jb20iLCJpYXQiOjE0NzI2MjY2NzUsImV4cCI6MTQ3MjYzMDI3NX0.AfT2vh7Z9vUBDcSwEZnhjeqIq6iQCIhj3ZRaNqYTv1z7CSyj2BsSv4uTSsnMHsR7OOuTdZtEYhhn_wbE-EpaOt299SLj5LgiycUbCh1lr3I-PNxOZsuL6cSIvp4Ysv5RD135m73ctBFnyMlgrLTwn8Oos8V4-u9z9K14rUwhsnBxfwZymnph7ltSnLxV6XXLBMOGbl8hA2YrTUiZUUb2SLDqzuZ1o6g14e02x-UIOIz8VWW4svCuQvGQqnn_ddhRQC436nXbSRxDGZbKAQTgpAetoLG-fsdu_MfTRLQ2hgE9TQgV_eMQMF5HrgD-ryIVm1hG6ZTxFTJbfNyD18PmAw","created":1472626675}';

        $client = new Google_Client();
        $dirname = dirname(__FILE__);
        $client->setAuthConfigFile($dirname . '/client_secrets.json');
        $client->setScopes(array(
            'https://apps-apis.google.com/a/feeds/groups/',
            'https://apps-apis.google.com/a/feeds/alias/',
            'https://apps-apis.google.com/a/feeds/user/',
            'https://www.google.com/m8/feeds/',
            'https://www.google.com/m8/feeds/user/',
        ));
        $client->setAccessType('offline');
        $client->setAccessToken($accessToken);
        $ret = $cls->fetchContact($client);
        var_dump($ret);
        $this->assertEquals(count($ret), 17);
    }






















}