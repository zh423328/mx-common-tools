<?php

namespace MxCommon\Tool\utils;

class utils
{
    //是否是日期
    static function is_date($date)
    {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }
        return false;
    }

    //是否是一个日期带时间的
    static function is_datetime($dateTime)
    {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
            //debuginfo("is_datetime preg matches:".json_encode($matches));
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }

        return false;
    }


    //是否是一个12:59:59类似的时间
    static function is_time($time)
    {
        if (preg_match("/^([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $time, $matches)) {
            return true;
        }

        return false;
    }

    static public function array_keys($arr)
    {
        $keys = array();
        foreach ($arr as $key => $val) {
            $keys[] = $key;
        }
        sort($keys);
        return $keys;
    }

    static public function array_vals($arr)
    {
        $vals = array();
        foreach ($arr as $key => $val) {
            $vals[] = $val;
        }
        return $vals;
    }

    static function curlpost($url, $postdata, $headers)
    {

        $start_time = explode(" ", microtime());
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $hs = [
            'X-ptype: like me',
            'Expect: ',
        ];
        if (!empty($headers)) {
            $hs = array_merge($hs, $headers);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $hs);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $pos = strpos($url, 'https');
        if ($pos !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $pos = strpos($url, 'https');
        if ($pos !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_close($ch);
        $end_time = explode(" ", microtime());

        $cost = intval(($end_time[0] + $end_time[1] - $start_time[1] - $start_time[0]) * 1000);

        if ($code != 200) {
            return null;
        }

        return $content;
    }

    static function curlpostjson($url, $params, $headers = [])
    {
        $postdata = json_encode($params);
        $headers = array_merge($headers, [
            'Content-Type: application/json',
        ]);
        $content = self::curlpost($url, $postdata, $headers);
        if (!$content) {
            return false;
        }
        $json = json_decode($content, true);
        if (!$json) {
            return false;
        }

        return $json;
    }


    static function curlget($url, $params, $headers = [])
    {
        $start_time = explode(" ", microtime());
        $ch = curl_init();
        $querys = null;
        if (!empty($params)) {
            $querys = [];
            foreach ($params as $k => $v) {
                $querys[] = "$k=" . rawurlencode($v);
            }
            $querys = implode('&', $querys);
            $url = $url . "?" . $querys;
        }
        $hs = [
            'X-ptype: like me',
            'Expect: ',
        ];
        if (!empty($headers)) {
            $hs = array_merge($hs, $headers);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $hs);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $pos = strpos($url, 'https');
        if ($pos !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        $end_time = explode(" ", microtime());
        $cost = intval(($end_time[0] + $end_time[1] - $start_time[1] - $start_time[0]) * 1000);
        if ($code != 200) {
            return null;
        }

        return $content;
    }

}