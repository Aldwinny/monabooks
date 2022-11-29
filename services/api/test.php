<?php

include '../utils/token.php';
include '../utils/string.php';

$token =  "";
$message = "Indicate IO in test.php file";

echo json_encode(array("message" => $message));
// echo Token::getHeader($token);
// echo json_decode(Token::getPayload($token), true)["access_level"];
