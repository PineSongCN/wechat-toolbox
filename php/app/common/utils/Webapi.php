<?php

declare(strict_types=1);

namespace app\common\utils;

class Webapi
{

    public function post($data)
    {
        $url = $data['url'] ?? '';
        $params = $data['params'] ?? [];
        $isJson = $data['isJson'] ?? 1;
        $header = $data['header'] ?? [];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $header2 = [];
        if ($isJson && empty($header['Content-Type'])) {
            $header['Content-Type'] = 'application/json';
        }
        foreach ($header as $k => $v) {
            $header2[] = $k . ':' . $v;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header2);
        if ($isJson) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        } else {
            $params = $this->_serialize($params);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);
        return $response;
    }

    public function get($data)
    {
        $url = $data['url'] ?? '';
        $params = $data['params'] ?? [];
        $header = $data['header'] ?? [];
        $query = "?";
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $query .= rawurlencode($key) . "=" . rawurlencode(json_encode($value));
            } else {
                $query .= rawurlencode($key) . "=" . rawurlencode((string) $value);
            }
            $query .= "&";
        }
        $query = substr($query, 0, strlen($query) - 1);

        $ch = curl_init();
        $header2 = [];
        foreach ($header as $k => $v) {
            $header2[] = $k . ':' . $v;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header2);
        curl_setopt($ch, CURLOPT_URL, $url . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);
        return $response;
    }

    public function file($data)
    {
        $url = $data['url'] ?? '';
        $params = $data['params'] ?? [];
        $header = $data['header'] ?? [];
        $query = "?";
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $query .= rawurlencode($key) . "=" . rawurlencode(json_encode($value));
            } else {
                $query .= rawurlencode($key) . "=" . rawurlencode((string) $value);
            }
            $query .= "&";
        }
        $query = substr($query, 0, strlen($query) - 1);

        $ch = curl_init();
        $header2 = [];
        foreach ($header as $k => $v) {
            $header2[] = $k . ':' . $v;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header2);
        curl_setopt($ch, CURLOPT_URL, $url . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function _serialize($arr)
    {
        $str = array();
        foreach ($arr as $key => $value) {
            $str[] = $key . '=' . $value;
        }
        $str = implode('&', $str);
        return $str;
    }
}
