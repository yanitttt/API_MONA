<?php
require_once __DIR__ . '/../Endpoint.php';

class login extends Endpoint {
    public static function POST() {
        $data = json_decode(file_get_contents('php://input'), true);
        foreach (['email', 'mdp'] as $property) {
            if (!isset($data[$property])) {
                http_response_code(400);
                die(json_encode(["error" => "property $property is required"]));
            }
        }
        // check user credentials
        global $db;
        try {
            $qry = $db
                ->prepare("select * from utilisateur where email = ?");
            $qry->execute([$data['email']]);
            $user = $qry->fetch();
            if ($user && password_verify($data['mdp'], $user['mdp'])) {
                unset($data['mdp']); // remove the pass from the data to be sent
                http_response_code(200);
                die(json_encode([
                    "message" => "Successful connection",
                    "utilisateur" => $user
                ]));
            }
        } catch (PDOException $err) {}
        http_response_code(401);
        die("Authentication error" . $err ? ": " . $err->getMessage() : "");
    }

    public static function GET   () { die(json_encode(['error' => "unauthorized method"])); }
    public static function PUT   () { die(json_encode(['error' => "unauthorized method"])); }
    public static function DELETE() { die(json_encode(['error' => "unauthorized method"])); }
}