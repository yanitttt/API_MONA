<?php
require_once __DIR__ . '/../Endpoint.php';

class paye_panier extends Endpoint {
    public static function PUT   () {
        global $db;
        $data = self::check_properties(
            ['id_utilisateur'],
            json_decode(file_get_contents('php://input'), true)
        );
        $qry = $db
            ->prepare("update panier set paye = true where id_utilisateur = ?");
        if ($qry->execute([$data['id_utilisateur']]))
            die(json_encode(["message" => static::class . " successfully updated"]));
        die(json_encode(["error" => static::class . ", update error"]));
    }


    public static function POST  () { die(json_encode(['error' => "unauthorized method"])); }
    public static function GET   () { die(json_encode(['error' => "unauthorized method"])); }
    public static function DELETE() { die(json_encode(['error' => "unauthorized method"])); }
}