<?php

include '../utils/token.php';

$token =  "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNyIsImZpcnN0bmFtZSI6IkFsdGVycmEiLCJsYXN0bmFtZSI6IkZlcnJvaiIsImFjY2Vzc19sZXZlbCI6IjIifQ.FqwwQmHDaDbkI3OVPY2HDd3IBw-Ct9TUjHrGb1vQ5-g";

echo Token::verifyToken($token) ? "gung" : "ho";
// echo Token::getHeader($token);
// echo json_decode(Token::getPayload($token), true)["access_level"];
