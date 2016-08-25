<?php
namespace ITime\Calendar\Test;
use ITime\Calendar\ITimeFactory;
class CalendarTest extends \PHPUnit_Framework_TestCase{

    public function testUniMelb(){
        $cls = ITimeFactory::create(ITimeFactory::$LIB_UNIMELB);
        $ret = $cls->login('mingyanx', 'xmy15234');
        $ret = $cls->fetch();
        $this->assertEquals($ret->status, 1);
    }


    public function testMonash(){
        $cls = ITimeFactory::create(ITimeFactory::$LIB_MONASH);
        $ret = $cls->login('xli531', 'Lx19930102');
        $ret = $cls->fetch();
        $this->assertEquals($ret->status, 1);
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
}