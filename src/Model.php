<?php
require_once __DIR__ . '/Endpoint.php';

abstract class Model extends Endpoint {
    protected static array $schema = [];

    public static function from_array(?array $data = null): ?self {
        if (!$data)
            die(json_encode(['error' => 'Invalid or missing data']));
        $res = new static();
        foreach ($data as $key => $value) {
            $res->$key = $value;
        }
        return $res;
    }

    public function to_array(): array {
        return array_map(function ($property) {
            return [$property->getName() => $this->{$property->getName()}];
        }, (new ReflectionClass($this))->getProperties());
    }

    public static function schema(): array { return static::$schema; }

    public static function fetchAll(): array {
        global $db;
        return $db
            ->query(
                'select * from ' . static::class . ' limit 100'
            )->fetchAll();
    }

    public static function fetchBy(string $val, string $col = 'id'): array {
        global $db;
        self::check_exists_fields([$col]);
        $qry = $db
            ->prepare(
                'select * from ' . static::class . " where $col = ? limit 100"
            );
        $qry->execute([$val]);
        return $qry->fetchAll();
    }
    public static function fetchByFilters(array $filters): array {
        global $db;
        self::check_exists_fields($filters);
        $qry = $db
            ->prepare("select * from " . static::class . " where " .
                implode(' and ', array_map(function($column) {
                    return "$column=:$column";
                }, array_keys($filters)))
            );
        foreach ($filters as $key => $value)
            $qry->bindValue(":$key", $value);
        $qry->execute();
        return $qry->fetchAll();
    }

    public static function save(array $data): string|false {
        global $db;
        $properties = array_diff(static::$schema, ['id']);
        $qry = $db
            ->prepare(
                "insert into " . static::class . " (" .
                implode(', ', $properties) .
                ") values (" . implode(', ', array_map(function($column) { // add : before each column name, then split the result with ,
                    return ":$column";
                }, $properties)) . ")"
            );
        foreach ($properties as $column)
            $qry->bindValue(":$column", $data[$column]);
        $qry->execute();
        return $db->lastInsertId();
    }

    public static function update(array $data): bool {
        global $db;
        $qry = $db
            ->prepare(
                "update " . static::class . " set " .
                implode(", ", array_map(function ($column) { // formats values like this: "c1 = :c1, c2 = :c2"
                    return "$column = :$column";
                }, self::schema())) .
                " where id = :id"
            );
        foreach (self::schema() as $column)
            $qry->bindValue(":$column", $data[$column]);
        return $qry->execute();
    }

    public static function del(int $id): bool {
        global $db;
        $qry = $db
            ->prepare(
                "delete from " . static::class . " where id = ?"
            );
        return $qry->execute([$id]);
    }

    protected static function check_exists_fields(array $fields) {
        $properties = array_flip(static::$schema);
        foreach ($fields as $key => $value) {
            if (!array_key_exists($key, $properties))
                die(json_encode(['error' => "Invalid column name: $key"]));
        }
    }
}