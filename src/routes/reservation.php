<?php
require_once __DIR__ . '/../Model.php';

class reservation extends Model {
    public $id;
    public $date;
    public $id_evenement;
    public $id_utilisateur;

    protected static array $schema =
        [
            'id', 'date', 'id_evenement', 'id_utilisateur',
        ];
}