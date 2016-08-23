<?php
namespace ITime\Calendar\Test;
use ITime\Calendar\ITimeFactory;
class CalendarTest extends \PHPUnit_Framework_TestCase{

    public function testUniMelb(){
        $cls = ITimeFactory::create(ITimeFactory::$LIB_UNIMELB);
        $result = $cls->login('', '');
        $result = $cls->fetch();
        $this->assertEquals($result, 'fetch');
    }


    // public function testUniMelbLogin(){
    //     $cls = ITimeFactory::create(ITimeFactory::$LIB_UNIMELB);
    //     $result = $cls->login('', '');
    //     $this->assertEquals($result, 'login');
    // }
}