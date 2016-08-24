<?php
namespace ITime\Calendar\Test;
use ITime\Calendar\ITimeFactory;
class CalendarTest extends \PHPUnit_Framework_TestCase{

    public function testUniMelb(){
        // $cls = ITimeFactory::create(ITimeFactory::$LIB_UNIMELB);
        // $result = $cls->login('mingyanx', 'xmy15234');
        // $result = $cls->fetch();
        // $this->assertEquals($result, 'fetch');
    }


    public function testMonash(){
        $cls = ITimeFactory::create(ITimeFactory::$LIB_MONASH);
        $result = $cls->login('xli531', 'Lx19930102');
        $result = $cls->fetch();
        $this->assertEquals($result, 'fetch');
    }
}