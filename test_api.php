<?php
require __DIR__.'/vendor/autoload.php';
$token = \App\Services\JwtService::generateToken('123');
$opts = ["http" => ["method" => "GET", "header" => "Authorization: Bearer $token\r\n"]];
$context = stream_context_create($opts);
$result = @file_get_contents("http://hoststory-api:8082/servers", false, $context);
if ($result === false) {
    $error = error_get_last();
    echo "ERROR: " . $error['message'] . "\n";
} else {
    echo "RESULT: " . $result . "\n";
}
