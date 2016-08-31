<?php 
namespace ITime\UniCalendar;
 
interface ITimeCalendar{
    /**
     * [login to the university system with univeristy official account]
     * @param  [string] $userId   [university account]
     * @param  [string] $password [university password]
     * @return [ITime\Calendar\ITimeRet]          []
     */
    public function login($userId, $password);

    /**
     * [fetch the calendar of university]
     * @return [ITime\Calendar\ITimeRet]
     */
    public function fetch();
 }