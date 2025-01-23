<?php
require_once __DIR__ . '/../Model.php';

class activite_utilisateur extends Model {
    public $id;
    public $id_utilisateur;
    public $timestamp;
    public $date_limite_conservation;
    public $action;
    public $description;

    protected static array $schema =
        [
            'id', 'id_utilisateur', 'timestamp', 'date_limite_conservation', 'action', 'description',
        ];
}