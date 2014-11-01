<?php

class FlistApicore {
    private static $username = 'Badgerkitty';
    private static $password = 'M0nk3yCh3353';
    
    const TICKET_CACHE_FILENAME = 'ticket.cache';
    
    private static $ticket;
    
    private static function getTicket() {
        if (!isset(self::$ticket)) {
            if (!file_exists(self::TICKET_CACHE_FILENAME) || !filesize(self::TICKET_CACHE_FILENAME) || filemtime(self::TICKET_CACHE_FILENAME) < (time() - 60*60*24)) {
                $res = self::makeRequest("getApiTicket.php", array('account'=>self::$username,'password'=>self::$password));
                self::$ticket = $res->ticket;
                file_put_contents(self::TICKET_CACHE_FILENAME, self::$ticket);
            } else {
                self::$ticket = file_get_contents(self::TICKET_CACHE_FILENAME);
            }
        }
        return self::$ticket;
    }
    
    private static function refetchTicket() {
        self::$ticket = null;
        unlink(self::TICKET_CACHE_FILENAME);
    }
    
    private static function makeRequest($path, array $data, $retryOnInvalidTicket=true) {
        // Despite the docs saying "POST" they actually only respond to GET args(?!)
        $url = "http://www.f-list.net/json/$path";
        if ($data) {
            $urlArgs = array();
            foreach ($data as $k => $v) {
                $urlArgs[] = "$k=" . urlencode($v);
            }
            $url .= '?' . join('&', $urlArgs);
        }
        
        $params = array();
        $params['http'] = array();
        $params['http']['method'] = 'POST';
//        $params['http']['content'] = $data;
        var_dump($url, $params);
        if ($fp = @fopen($url, 'rb', false, stream_context_create($params))) {
            if (false !== ($res = @stream_get_contents($fp))) {
//                return $res;
            }
        }
        
//        $res = file_get_contents($url);
        $res = json_decode($res);
        
        if ($retryOnInvalidTicket && !empty($res->error) && stripos($res->error, 'Invalid ticket') !== false) {
            self::refetchTicket();
            return self::makeRequest($path, $data, false);
        }
        
        return $res;
    }
    
    public static function callMethod($methodName, array $args) {
        $args['account'] = self::$username;
        $args['ticket'] = self::getTicket();
        
        return self::makeRequest("api/$methodName.php", $args);
    }
}

?>