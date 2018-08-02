<?php

namespace App\Service;

class Weezevent
{
    private $access_token = '';
    private $api_key = '';

    /**
     * @param {string} $username    // required: Weezevent organizer or partner login.
     * @param {string} $password    // required: Weezevent organizer or partner password.
     * @param {string} $api_key     // required: Your API key.
     */
    function __construct($username, $password, $api_key)
    {
        $this->api_key = $api_key;
        $this->access_token = $this->getAccessToken($username, $password, $api_key);
    }

    /**
     * Authenticate the user and return an access token.
     * 
     * @param {string} $username    // required: Weezevent organizer or partner login.
     * @param {string} $password    // required: Weezevent organizer or partner password.
     * @param {string} $api_key     // required: Your API key.
     * 
     * @return string  Return an access token.
     */
    private function getAccessToken($username, $password, $api_key)
    {
        $url = 'https://api.weezevent.com/auth/access_token';
        $data = [
            'username' => $username,
            'password' => $password,
            'api_key' => $api_key
        ];

        $access_token = $this->request($url, 'POST', $data);
        return $access_token->accessToken;
    }

    /**
     * List of events to which the user has access.
     * 
     * @param {Array} $params // array of optional parameters
     * [
     *  'include_not_published' => true,    // Boolean : Whether or not to include events which are not published. Default is true.
     *  'include_closed'        => false,   // Boolean : Whether or not to include events which are closed. Default is false.
     *  'include_without_sales' => false    // Boolean : Whether or not to include events did not sell any tickets so far. Default is false.
     * ]
     * 
     * @return object Returns all events to which the current user (identified by the access token) has access.
     */
    public function getEvents($params = [])
    {
        $url = 'https://api.weezevent.com/events';
        $data = [
            'access_token' => $this->access_token,
            'api_key' => $this->api_key,
        ];

        $events = $this->request($url, 'GET', $data, $params);
        return $events;
    }

    /**
     * List of dates for one or multiple events
     * 
     * @param {Mixed}   $id_event           // required : Id of event
     * @param {Boolean} $display_passed     // optional : Should we return passed dates. Default false.
     * 
     * @return object Returns a list of dates for one or multiple events
     */
    public function getDates($id_event, $display_passed = false)
    {
        $url = 'https://api.weezevent.com/dates';
        $data = [
            'access_token' => $this->access_token,
            'api_key' => $this->api_key
        ];
        
        $params = [
            'id_event' => $id_event
        ];

        if($display_passed)
        {
            $params['display_passed'] = 'true';
        }


        $dates = $this->request($url, 'GET', $data, $params);
        return $dates;
    }
    
    /**
     * List of price categories for one or multiple events
     * 
     * @param {string} $id_event  // required : Id of event
     * 
     * @return object returns a list of tickets associated with one or multiple events.
     */
    public function getTickets($id_event)
    {
        $url = 'https://api.weezevent.com/tickets';
        $data = [
            'access_token' => $this->access_token,
            'api_key' => $this->api_key
        ];

        $params = [
            'id_event' => $id_event
        ];

        $tikets = $this->request($url, 'GET', $data, $params);
        return $tikets;
    }

    /**
     * Return the scan statistic for one price category
     * 
     * @param {string} id_date      // optional : id of date
     * @param {string} $id_tiket    // required : Id of event
     * 
     * @return object Return the scan statistic for one price category
     */
    public function getTicketStats($id_tiket, $id_date = false)
    {
        $url = 'https://api.weezevent.com/ticket/'.$id_tiket.'/stats';
        $data = [
            'access_token' => $this->access_token,
            'api_key' => $this->api_key,
        ];

        $params = [];

        if($id_date !== false)
        {
            $params['id_date'] = $id_date;
        }
        
        $tiketStats = $this->request($url, 'GET', $data, $params);
        return $tiketStats;
    }

    /**
     *  Return a list of participants
     * 
     *  @param {Array} $params // array of optional parameters
     *  [
     *      'id_event'               => [122255, 456633]       // Array - id of event
     *      'id_ticket'              => [122255, 456633]       // Array - id of price category
     *      'date_ticket'            => [122255, 456633]       // Array – associative array of id_date/id_ticket tuples
     *      'last_update'            => '2018-04-28 14:00:00'  // Date (YYYY-MM-DD HH:MM:SS) - If this parameter is given, the method will return only participants modified after this date. This parameter  is mainly used in an access control scenario.
     *      'last_update_before'     => '2018-04-28 14:00:00'  // Date (YYYY-MM-DD HH:MM:SS) - If this parameter is given, the method will return only participants modified before this date. This parameter is mainly used in an access control scenario.
     *      'include_deleted'        => false                  // Boolean - by default set to false. Indicates whether or not deleted participants are included in the results.
     *      'moderation'             => false	               // Boolean – by default set to false. Indicates whether or not the moderation status of a participant is included in the results (Beta).
     *      'include_unpaid'         => false	               // Boolean - by default set to false. Indicates whether or not unpaid participants are included in the results.
     *      'full'                   => false 	               // Boolean – If set to true, all inscription form answers will be included in the response. Default is false.
     *      'minimized'              => false	               // Boolean - Reduces the amount of transferred data by shortening the labels of the response. Default is false.
     *      'return_count'           => false	               // Boolean - return number of active and deleted participants
     *      'return_count_total'     => false	               // Boolean - return total number of participants
     *      'return_removed'         => false                  // Boolean - return only deleted participants
     *      'max'                    => 100 	               // int - max result by page
     *      'page'                   => 1	                   // int - current page of the response
     *      'transaction_reference'  => C4E265226O63029        // Array - reference of the transaction made by the participant
     *  ] 
     * 
     * @return object Return a list of participants
     */
    public function getParticipants($params = [])
    {
        $url = 'https://api.weezevent.com/participant/list';
        $data = [
            'access_token' => $this->access_token,
            'api_key' => $this->api_key,
        ];
       
        $participants = $this->request($url, 'GET', $data, $params);
        return $participants;
    }

    /**
     * Return the inscription form answers of one participant
     * 
     * @param {string} $id_participant  // required : Participant id
     * 
     * @return object Returns an array of question/answer data for one participant
     */
    public function getParticipantAnswer($id_participant)
    {
        $url = 'https://api.weezevent.com/participant/'.$id_participant.'/answers/';
        $data = [
            'access_token' => $this->access_token,
            'api_key' => $this->api_key,
        ];

        $participantAnswer = $this->request($url, 'GET', $data);
        return $participantAnswer;
    }

    /**
     * Return details for specified event
     * 
     * @param {string} $id_event  // required : Event ID
     * 
     * @return object Returns an array with all details
     */
    public function getEventDetails($id_event)
    {
        $url = 'https://api.weezevent.com/event/'.$id_event.'/details/';
        $data = [
            'access_token' => $this->access_token,
            'api_key' => $this->api_key,
        ];

        $eventDetails = $this->request($url, 'GET', $data);
        return $eventDetails;
    }

    /**
     *  Return a list of participants
     * 
     *  @param {Array} $params // array of optional parameters
     *  [
     *      'date'       => '2012-07-20 00:00:00'   // Date - optional: Retrieve events for unique date
     *      'date_start' => '2012-07-20 00:00:00'	// Date – optional : Retrieve events in a period
     *      'date_end' 	 => '2012-07-20 00:00:00'   // Date – optional : Retrieve events in a period
     *      'category' 	 => 38                      // Integer – optional : Search by category ID
     *      'city' 	     => 'Bordeaux'              // String – optional: Search by city name
     *      'zip_code' 	 => 33000                   // String – optional: Search by postal code
     *      'country' 	 => France || FRA || FR     // String – optional: Search by country (ISO code)
     *      'province' 	 =>                         // String – optional: Search by province
     *      'organizer'  =>	'jhon doe'              // String – optional: Search by an organizer name
     *      'max_result' =>	10                      // Integer – optional: Number of maximal event to find
     *  ] 
     * 
     * 
     * @return object Return a list of participants
     */
    public function SearchEvent($id_event)
    {
        $url = 'https://api.weezevent.com/event/'.$id_event.'/details/';
        $data = [
            'access_token' => $this->access_token,
            'api_key' => $this->api_key,
        ];

        $eventDetails = $this->request($url, 'GET', $data);
        return $eventDetails;
    }

    private function request($url, $method = 'GET', $data = [], $params = [], $header = "Content-type: application/x-www-form-urlencoded;charset=utf-8\r\n", $decode = true)
    {
        $formatParams = '';

        if(count($params) == 1)
        {
            $formatParams = '?'.array_keys($params)[0].'='.array_values($params)[0];
        }
        elseif(count($params) > 1)
        {
            $formatParams = '?';
            foreach($params as $key => $value)
            {
                $formatParams = $formatParams.$key.'='.$value.'&';
            }
            $formatParams = substr($formatParams, 0, -1);
        }
        // dump($url, $method, $data, $params, $formatParams, $header, $decode);

        $options = array(
        'http' => array(
            'header'  => $header,
            'method'  => $method,
            'content' => http_build_query($data)
        )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url.$formatParams, false, $context);
        if ($result === FALSE) 
        { 
            return false;
        }
        else
        { 
            return ($decode) ? json_decode($result) : $result;
        }
    }
}