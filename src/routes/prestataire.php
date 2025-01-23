<?php
require_once __DIR__ . '/../Model.php';

class prestataire extends Model {
    public $id;
    public $id_utilisateur;
    public $num_siret;

    protected static array $schema =
        [
            'id', 'id_utilisateur', 'num_siret',
        ];
}