<?php
namespace ITime\Calendar\Test;
use ITime\Calendar\ITimeFactory;
class CalendarTest extends \PHPUnit_Framework_TestCase{

    public function testSay(){
        $cls = new ITimeFactory();
        $r = $cls->say();
        $this->assertEquals($r, 'yinchuandong');
    }
}