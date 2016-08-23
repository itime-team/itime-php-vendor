<?php 
namespace ITime\Calendar;
 
interface ITimeCalendar{
    /**
     * [login to the university system with univeristy official account]
     * @param  [string] $userId   [university account]
     * @param  [string] $password [university password]
     * @return [array]          []
     */
    public function login($userId, $password);

    /**
     * [fetch the calendar of university]
     * @return [array]
     */
    public function fetch();
 }