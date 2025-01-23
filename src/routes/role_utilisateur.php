<?php
require_once __DIR__ . '/../Model.php';

class role_utilisateur extends Model {
    public $id;
    public $nom;

    protected static array $schema =
        [
            'id', 'nom'
        ];
}