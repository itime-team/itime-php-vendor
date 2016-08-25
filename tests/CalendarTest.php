<?php
namespace ITime\Calendar\Test;
use ITime\Calendar\ITimeFactory;
class CalendarTest extends \PHPUnit_Framework_TestCase{

    public function testUniMelb(){
        $cls = ITimeFactory::create(ITimeFactory::$LIB_UNIMELB);
        $ret = $cls->login('mingyanx', 'xmy15234');
        $ret = $cls->fetch();
        $this->assertEquals($ret['status'], 1);
    }


    // public function testMonash(){
    //     $cls = ITimeFactory::create(ITimeFactory::$LIB_MONASH);
    //     $result = $cls->login('xli531', 'Lx19930102');
    //     $result = $cls->fetch();
    //     $this->assertEquals($result, 'fetch');
    // }
}