<?php
require_once __DIR__ . '/../Model.php';

class client extends Model {
    public $id;
    public $id_utilisateur;

    protected static array $schema =
        [
            'id', 'id_utilisateur'
        ];
}