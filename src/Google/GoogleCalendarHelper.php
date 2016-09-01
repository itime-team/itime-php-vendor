<?php 
namespace ITime\Google;

use \Google_Service_Calendar;

class GoogleCalendarHelper{
    private $client;
    private $service;
    public function __construct($client){
        $this->client = $client;
        date_default_timezone_set('Australia/Melbourne');
        $this->service = new Google_Service_Calendar($this->client);
    }

    public function fetch(){
        $service = $this->service;
        $calendarArr = [];
        $calendarList = $service->calendarList->listCalendarList();
        while(true) {
            foreach ($calendarList->getItems() as $entry) {
                // don't import national holiday
                if(strpos($entry->id, '#holiday@') !== false){
                    continue;
                }
                $calObj = (object)[];
                $calObj->id = $entry->id;
                $calObj->title = $entry->summary;
                $calObj->events = $this->listEvent($entry->id);
                array_push($calendarArr, $calObj);
            }
            $pageToken = $calendarList->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $calendarList = $service->calendarList->listCalendarList($optParams);
            } else {
                break;
            }
        }
        return $calendarArr;
    }

    public function listEvent($calendarId){
        $service = $this->service;
        $events = $service->events->listEvents($calendarId);
        $eventArr = [];
        while(true){
            foreach($events->getItems() as $entry) {
                $evtObj = (object)[];
                $evtObj->id = $entry->id;
                $evtObj->eventId = $entry->id;
                $evtObj->title = $entry->summary;
                $evtObj->status = $entry->status;
                if($entry->status != 'cancelled'){
                    $evtObj->startTime = $entry->start->dateTime;
                    $evtObj->endTime = $entry->end->dateTime;
                }
                $evtObj->address = $entry->location;
                $evtObj->hostEventId = $entry->recurringEventId;
                if($entry->recurrence != null){
                    $evtObj->repeatType = $entry->recurrence[0];
                }else{
                    $evtObj->repeatType = null;
                }
                // parse attendees
                $evtObj->attendees = [];
                foreach($entry->attendees as $atd){
                    $atdObj = (object)[];
                    $atdObj->email = $atd->email;
                    $atdObj->responseStatus = $atd->responseStatus;
                    array_push($evtObj->attendees, $atdObj);
                }
                array_push($eventArr, $evtObj);
            }
            $pageToken = $events->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $events = $service->events->listEvents($calendarId, $optParams);
            } else {
                break;
            }
        }
        return $eventArr;
    }







}

