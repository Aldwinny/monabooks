<?php

$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

$payload = json_encode(['user_id' => 1]);

class Token
{
    // THIS IS PRIVATE DATA THAT SHOULD NOT BE SHARED
    private static $secret = "(H+MbQeThWmZq4t7w!z%C*F-J@NcRfUjXn2r5u8x/A?D(G+KbPdSgVkYp3s6v9y$";

    private static function base64UrlGenerate($target)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($target));
    }

    private static function base64UrlDecode($target)
    {
        $decoded =  base64_decode(str_replace(['-', '_', ''], ['+', '/', '='], ($target)));
        return $decoded;
    }

    public static function getToken($id, $firstname, $lastname, $access_level)
    {
        $payload = json_encode(['user_id' => $id, 'firstname' => $firstname, 'lastname' => $lastname, 'access_level' => $access_level]);
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        // Encode Header and Payload to Base64Url String
        $Base64UrlHeader = Token::base64UrlGenerate($header);
        $Base64UrlPayload = Token::base64UrlGenerate($payload);

        // Create signature
        $signature = hash_hmac('sha256', $Base64UrlHeader . "." . $Base64UrlPayload, Token::$secret, true);

        // Encode signature
        $Base64UrlSignature = Token::base64UrlGenerate($signature);

        $jwt = $Base64UrlHeader . "." . $Base64UrlPayload . "." . $Base64UrlSignature;
        return $jwt;
    }

    public static function verifyToken($token)
    {
        $data = explode('.', $token);

        $signature = hash_hmac('sha256', $data[0] . "." . $data[1], Token::$secret, true);

        if ($signature != $data[3]) {
            return false;
        }
        return true;
    }

    public static function getHeader($token)
    {
        return Token::base64UrlDecode(explode(".", $token)[0]);
    }

    public static function getPayload($token)
    {
        return Token::base64UrlDecode(explode(".", $token)[1]);
    }
}
