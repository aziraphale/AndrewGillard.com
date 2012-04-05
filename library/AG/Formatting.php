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
          ["media"]=>
          object(SimpleXMLElement)#201 (1) {
            ["creative"]=>
            object(SimpleXMLElement)#204 (10) {
              ["expanded_url"]=> string(62) "http://twitter.com/lorddeath/status/187552521114492928/photo/1"
              ["type"]=> string(5) "photo"
              ["url"]=> string(20) "http://t.co/o8Sr2AgJ"
              ["media_url"]=> string(38) "http://p.twimg.com/AppSBjYCIAA8wtc.png"
              ["display_url"]=> string(24) "pic.twitter.com/o8Sr2AgJ"
            }
          }
          ["urls"]=>
          object(SimpleXMLElement)#212 (1) {
            ["url"]=>
            object(SimpleXMLElement)#214 (4) {
              ["@attributes"]=>
              ["url"]=> string(20) "http://t.co/HKoIpQk2"
              ["display_url"]=> string(18) "twitpic.com/91m4s3"
              ["expanded_url"]=> string(25) "http://twitpic.com/91m4s3"
            }
          }
          ["hashtags"]=> object(SimpleXMLElement)#213 (0) {}
        }
        */
        $urls = $photos = array();
        if (isset($entities)) {
            if (isset($entities->urls)) {
                if (isset($entities->urls->url)) {
                    $urls = $entities->urls->url;
                }
            }
            if (isset($entities->media)) {
                if (isset($entities->media->creative)) {
                    $urls = $entities->media->creative;
                }
            }
        }
        foreach ($urls as $u) {
            if (empty($u->url) || empty($u->display_url))
                continue;
            $html = str_replace($u->url, "<a href=\"{$u->url}\">{$u->display_url}</a>", $html);
        }
        foreach ($photos as $p) {
            if (empty($p->url) || empty($p->display_url))
                continue;
            $html = str_replace($p->url, "<a href=\"{$p->url}\">{$p->display_url}</a>", $html);
        }
        return $html;
    }
    
    public static function wordwrapIgnoringUrls($string, $maxLength=20, $splitStr="&shy;") {
        /** @todo Yeah, the "ignoring urls" part is gonna be tricky... */
        $string = preg_replace('/\b\w{'.$maxLength.'}\B/', "\$0$splitStr", $string);
        return $string;
    }
}

?>
