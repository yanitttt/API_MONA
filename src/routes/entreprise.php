<?php
require_once __DIR__ . '/../Model.php';

class entreprise extends Model {
    public $id;
    public $note;
    public $id_user;
    public $date_limite_conservation;
    public $siret;
    public $nom;
    public $ville;
    public $description;

    protected static array $schema =
        [
            'id', 'note', 'id_user', 'date_limite_conservation', 'siret', 'nom', 'ville', 'description',
        ];
}