<?php
require_once __DIR__ . '/../Model.php';

class panier extends Model {
    public $id;
    public $id_utilisateur;
    public $id_evenement;
    public $id_monument;
    public $nb_tickets;
    public $paye;

    protected static array $schema =
        [
            'id', 'id_utilisateur', 'id_evenement', 'id_monument', 'nb_tickets', 'paye'
        ];
}