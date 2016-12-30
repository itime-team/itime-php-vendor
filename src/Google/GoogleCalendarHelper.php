<?php 
namespace ITime\Google;

use \Google_Service_Calendar;

class GoogleCalendarHelper {
    private $client;
    private $service;
    
    public function __construct($client) {
        $this->client = $client;
        date_default_timezone_set('UTC'); // var_dump(date_default_timezone_get());
        $this->service = new Google_Service_Calendar($this->client);
    }

    public function fetchCalendars() {
        $service = $this->service;
        $calendars = [];
        $calendarList = $service->calendarList->listCalendarList();
        while(true) {
            foreach ($calendarList->getItems() as $item) {
                // don't import holiday and contacts
                if((strpos($item->id, '#holiday@') !== false) or (strpos($item->id, '#contacts@') !== false)){
                    continue;
                }
                $calendar = [];
                $calendar['iCalUID'] = $item->id;
                $calendar['summary'] = $item->summary;
                array_push($calendars, $calendar);
            }
            $pageToken = $calendarList->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $calendarList = $service->calendarList->listCalendarList($optParams);
            } else {
                break;
            }
        }
        return $calendars;
    }

    public function fetchEvents($iCalUID, $syncToken){
        $service = $this->service;
        if ($syncToken == '') {
            $eventList = $service->events->listEvents($iCalUID);
        }
        else
            $eventList = $service->events->listEvents($iCalUID, ['syncToken' => $syncToken]);
        $syncToken = $eventList->getNextSyncToken();
        $events = [];
        while (true) {
            foreach ($eventList->getItems() as $item) {
                $event = [];
                $event['invitee'] = [];
                $event['iCalUID'] = $iCalUID;
                $event['eventId'] = $item->id;
                $event['status'] = $item->status;
                if ($item->status != 'cancelled') {
                    $event['startTime'] = strtotime($item->start->dateTime)*1000;
                    $event['endTime'] = strtotime($item->end->dateTime)*1000;
                } else {
                    $currentTime = round(microtime(true) * 1000);
                    $event['startTime'] = $event['endTime'] = $currentTime;
                }
                if ($item->summary == null) {
                    $event['summary'] = '';
                } else {
                    $event['summary'] = $item->summary;
                }
                if ($item->location == null) {
                    $event['location'] = '';
                } else {
                    $event['location'] = $item->location;
                }
                if ($item->recurringEventId == null) {
                    $event['recurringEventId'] = '';
                } else {
                    $event['recurringEventId'] = $item->recurringEventId;
                }
                if ($item->recurrence == null) {
                    $event['recurrence'] = [];
                } else {
                    $event['recurrence'] = $item->recurrence;
                }
                $event['eventType'] = 'solo';
                array_push($events, $event);
            }
            $pageToken = $eventList->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $eventList = $service->events->listEvents($iCalUID, $optParams, ['syncToken' => $syncToken]);
                $syncToken = $eventList->getNextSyncToken();
            } else {
                break;
            }
        }
        return array($events, $syncToken);
    }
}

