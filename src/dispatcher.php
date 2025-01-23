<?php
if (!file_exists(
    // initialize “$endpoint” with the value of the string between the last / and the first ? & initialize “$model_path” with the path of the model called $endpoint
        $route = __DIR__ . '/routes/' . ($endpoint = strtolower(basename(explode('?', $_SERVER['REQUEST_URI'])[0]))) . '.php'
    )) {
    http_response_code(404);
    die(json_encode(['error' => 'unknown endpoint']));
}
require_once $route;
try {
    // checks that the method returned by “$_SERVER['REQUEST_METHOD']” exists in class “$endpoint”.
    if (!method_exists($endpoint, $_SERVER['REQUEST_METHOD'])) {
        http_response_code(405);
        die(json_encode(['error' => 'unauthorized method']));
    }
    $endpoint::{$_SERVER['REQUEST_METHOD']}(); // call the static method “$_SERVER['REQUEST_METHOD']” of the class “$endpoint”
} catch (Exception $err) {
    http_response_code(500);
    die(json_encode(["error" => "runtime error: . {$err->getMessage()}"]));
}