<?php

abstract class Endpoint {
    public static function GET () {
        // fetches data by filtering them on the fields given in the url
        if (!empty($_GET))
            die(json_encode(static::fetchByFilters($_GET)));
        // fetch all data if no filter is given
        die(json_encode(static::fetchAll()));
    }
    public static function POST() {
        $data = static::check_properties(
            array_diff(static::$schema, ['id']),
            json_decode(file_get_contents('php://input'), true)
        );
        $id = static::save($data);
        if ($id)
            die(json_encode([
                "message" => static::class . " successfully created",
                "id" => $id
            ]));
        die(json_encode(["error" => static::class . ", insert error"]));
    }
    public static function PUT () {
        $data = static::check_properties(
            static::$schema,
            json_decode(file_get_contents('php://input'), true)
        );
        if (static::update($data))
            die(json_encode(["message" => static::class . " successfully updated"]));
        die(json_encode(["error" => static::class . ", update error"]));
    }
    public static function DELETE() {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data && $data['id']) {
            if (static::del($data['id']))
                die(json_encode(["message" => "{$data['id']} successfully deleted in table " . static::class]));
            die(json_encode(["error" => static::class . ", delete error"]));
        }
    }

    public static function check_properties(array $expected, array $given) {
        foreach ($expected as $property) {
            if (!isset($given[$property])) {
                $error = "property $property is required";
                error_log($error);
                http_response_code(400);
                die(json_encode(["error" => $error]));
            }
        }
        return $given;
    }
}