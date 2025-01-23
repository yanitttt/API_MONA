<?php
require_once __DIR__ . '/../Model.php';

class monument_disponibilites extends Model {
    public $id;
    public $monument_id;
    public $date_debut;
    public $date_fin;

    protected static array $schema =
        [
            'id', 'monument_id', 'date_debut', 'date_fin',
        ];
}