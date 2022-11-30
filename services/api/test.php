<?php

include '../utils/token.php';
include '../utils/string.php';

$token =  null;

$i = 5;
$message = $token ?? "Not found" . $i;


echo json_encode(array("message" => $message));
// echo Token::getHeader($token);
// echo json_decode(Token::getPayload($token), true)["access_level"];
