<?php

namespace ITime\Calendar;

class ITimeFactory {

    public static $LIB_UNIMELB = 'LibUniMelb';
    public static $LIB_MONASH = 'LibMonash';

    function __construct(){

    }

    /**
     * [create description]
     * @param  [string] $className [description]
     * @return [ITimeCalendar]   [description]
     */
    public static function create($className){
        $instance = null;
        try{
            $reflect = new \ReflectionClass('ITime\\Calendar\\'.$className);
            $instance = $reflect->newInstance();
        } catch (Exception $e){
            var_dump($e);
            $instance = null;
        }
        return $instance;
    }
}