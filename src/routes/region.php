<?php
require_once __DIR__ . '/../Endpoint.php';

class region extends Endpoint {
    public static function GET() {
        global $db;
        die(json_encode(
            $db
                ->query("select distinct region from historic_monuments")
                ->fetchAll()
        ));
    }

    public static function POST() { die(json_encode(['error' => "unauthorized method"])); }
    public static function PUT () { die(json_encode(['error' => "unauthorized method"])); }
}