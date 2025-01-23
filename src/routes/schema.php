<?php
require_once __DIR__ . '/../Endpoint.php';

class schema extends Endpoint {
    public static function GET() {
        global $db;
        $tables = $db
            ->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")
            ->fetchAll();
        $schema = [];
        foreach ($tables as $table) {
            $table = $table['table_name'];
            $res = $db
                ->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = '$table'")
                ->fetchAll();
            $columns = [];
            foreach ($res as $column)
                $columns[$column['column_name']] = $column['data_type'];
            $schema[$table] = $columns;
        }
        die(json_encode($schema));
    }

    public static function POST  () { die(json_encode(['error' => "unauthorized method"])); }
    public static function PUT   () { die(json_encode(['error' => "unauthorized method"])); }
    public static function DELETE() { die(json_encode(['error' => "unauthorized method"])); }
}