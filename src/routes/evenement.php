<?php
require_once __DIR__ . '/../Model.php';

class evenement extends Model {
    public $id;
    public $id_user;
    public $heure_fin;
    public $date_debut;
    public $date_fin;
    public $heure_debut;
    public $exterieur;
    public $nb_places;
    public $prix;
    public $note;
    public $date_ajout;
    public $derniere_maj;
    public $nb_tickets_vendus;
    public $statut_approbation;
    public $besoin_parking;
    public $niveau_sonore_prev;
    public $titre;
    public $budget_detaille;
    public $equipements_specifiques;
    public $id_monument;
    public $exigences_sanitaires;
    public $type_evenement;
    public $besoins_logistiques;
    public $description;
    public $objet_requis;
    public $statut;
    public $type;
    public $conditions_annulation;

    protected static array $schema =
        [
            'id', 'id_user', 'heure_fin', 'date_debut', 'date_fin', 'heure_debut', 'exterieur', 'nb_places', 'prix', 'note', 'date_ajout', 'derniere_maj', 'nb_tickets_vendus', 'statut_approbation', 'besoin_parking', 'niveau_sonore_prev', 'titre', 'budget_detaille', 'equipements_specifiques', 'id_monument', 'exigences_sanitaires', 'type_evenement', 'besoins_logistiques', 'description', 'objet_requis', 'statut', 'type', 'conditions_annulation'
        ];
}