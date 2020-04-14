<?php

function content_replace($content)
{
    $content = html_entity_decode($content);
    if(strpos($content, '../../../../') != null){
        $content = str_replace('../../../../', url('/') . '/', $content);
    }
    if(strpos($content, '../../../') != null){
        $content = str_replace('../../../', url('/') . '/', $content);
    }
    if(strpos($content, '../../') != null){
        $content = str_replace('../../', url('/') . '/', $content);
    }
    if(strpos($content, '../') != null){
        $content = str_replace('../', url('/') . '/', $content);
    }
    return $content;
}

function encode($str) {
    $key = config('ninex.encrypt_key');
    $cipher = config('ninex.cipher');
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($str, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
        return $ciphertext;
    } else {
        throw new RuntimeException('The cipher is not supported.');
    }
}

function decode($str) {
    $key = config('ninex.encrypt_key');
    $cipher = config('ninex.cipher');
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $c = base64_decode($str);
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        if (@hash_equals($hmac, $calcmac)) {
            return $original_plaintext;
        }
        return '';
    } else {
        throw new RuntimeException('The cipher is not supported.');
    }
}

function client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP']))
    $ipaddress = $_SERVER['HTTP_CF_CONNECTING_IP'];
    else if (isset($_SERVER['HTTP_CLIENT_IP']))
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
    $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
    $ipaddress = '127.0.0.1';
    return $ipaddress;
}

function random_string($length = 10) {
    $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function word_limit($str = '', $limit = 0, $split = '...'){
    if(strpos($str, ' ')){
        $str_arr = (array) explode(' ', $str);
        $str_arr = array_filter($str_arr);
        if(count($str_arr) > $limit){
            $new_str = array_slice($str_arr, 0, $limit);
            return implode(' ', $new_str) . $split;
        }else{
            return $str;
        }
    }else{
        return $str;
    }
}

function time_ago($time = null){
    $time_devide = time() - intval($time);
    $result = 'Vừa xong';
    if($time == null || $time == 0){
        $result = 'Không xác định';
    }else{
        if($time_devide >= 60 && $time_devide < 3600){
            $result = round($time_devide/60) . ' phút trước';
        }elseif($time_devide >= 3600 && $time_devide < 86400){
            $result = round($time_devide/60/60) . ' giờ trước';
        }elseif($time_devide >= 86400 && $time_devide < 2592000){
            $result = round($time_devide/60/60/24) . ' ngày trước';
        }elseif($time_devide >= 2592000 && $time_devide < 31104000){
            $result = round($time_devide/60/60/24/30) . ' tháng trước';
        }elseif($time_devide >= 31104000){
            $result = round($time_devide/60/60/24/30/12) . ' năm trước';
        }
    }
    return $result;
}
