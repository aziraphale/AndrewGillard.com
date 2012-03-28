<?php

class AG_Formatting {
    const URL_REGEX = '#((?:https?://|www\.(?!\.)(?=[^.]+\.[^.]+))(?P<main>(?:[-a-z0-9@%_+.~\#!?&/=]|[,:;](?!\s)|\((?P>main)\))+))#i';
    
    public static function linkifyUrls($html) {
        return preg_replace(self::URL_REGEX, '<a href="$1">$1</a>', $html);
    }
    
    public static function linkifyUrlsFromTwitter($html, $entities) {
        /*
        ["entities"]=>
        object(SimpleXMLElement)#210 (3) {
          ["user_mentions"]=> object(SimpleXMLElement)#211 (0) {}
          ["urls"]=>
          object(SimpleXMLElement)#212 (1) {
            ["url"]=>
            object(SimpleXMLElement)#214 (4) {
              ["@attributes"]=>
              array(2) {
                ["end"]=> string(3) "104"
                ["start"]=> string(2) "84"
              }
              ["url"]=> string(20) "http://t.co/HKoIpQk2"
              ["display_url"]=> string(18) "twitpic.com/91m4s3"
              ["expanded_url"]=> string(25) "http://twitpic.com/91m4s3"
            }
          }
          ["hashtags"]=> object(SimpleXMLElement)#213 (0) {}
        }
        */
        $urls = array();
        if (!empty($entities)) {
            if (!empty($entities->urls)) {
                if (!empty($entities->urls->url)) {
                    $urls = $entities->urls->url;
                } else {
                    $urls = $entities->urls;
                }
            } else {
                $urls = $entities;
            }
        }
        foreach ($entities->urls->url as $u) {
            if (empty($u->url) || empty($u->display_url))
                continue;
            $html = str_replace($u->url, "<a href=\"{$u->url}\">{$u->display_url}</a>", $html);
        }
        return $html;
    }
}

?>
