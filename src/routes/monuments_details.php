<?php
require_once __DIR__ . '/../Model.php';
require_once __DIR__ . '/../utils/uploader.php';

class monuments_details extends Model {
    public $id;
    public $etat;
    public $monument_id;
    public $utilisateur_id;
    public $cree_le;
    public $mis_a_jour_le;
    public $capacite_maximale;
    public $disponibilite_stationnement;
    public $cout_par_heure;
    public $facture_pdf;
    public $image_monument;
    public $connectivite_internet;
    public $type_evenements_autorises;
    public $zones_accessibles;
    public $contraintes_acoustiques;
    public $risque_environnemental;
    public $materiel_disponible;
    public $repartition_budget;
    public $ressources_demandees;
    public $plan_activites;
    public $impact_environnemental;
    public $niveau_acces_infrastructures;

    public static function GET() {
        if (!empty($_GET)) {
            // fetches data by filtering them on the field $col given in the url
            $col = array_key_first($_GET);
            $filter = [];

            // Handle special filters
            if ($col === "capacite_maximale")
                $filter["capacite_maximale"] = "capacite_maximale >= :capacite_maximale";
            global $db;
            self::check_exists_fields($_GET);

            // Build standard filters query
            $standardQueryPart = implode(' AND ', array_map(function($column) {
                return "$column = :$column";
            }, array_keys(array_diff_key($_GET, $filter))));
            // Add special filters to the query
            $specialQueryPart = implode(' AND ', $filter);

            // Combine the standard and special query parts
            $qry = "select * from " . static::class . " where ";
            if (!empty($standardQueryPart) && !empty($specialQueryPart))
                $qry .= $standardQueryPart . " and " . $specialQueryPart;
            elseif (!empty($standardQueryPart))
                $qry .= $standardQueryPart;
            elseif (!empty($specialQueryPart))
                $qry .= $specialQueryPart;

            // Prepare and bind values to the query
            $qry = $db->prepare($qry);
            foreach ($_GET as $key => $value)
                $qry->bindValue(":$key", $value);
            $qry->execute();
            die(json_encode($qry->fetchAll()));
        }
        // fetch all data if no filter is given
        die(json_encode(self::fetchAll()));
    }

    public static function POST() {
        try {
            $data = self::check_properties(array_diff(self::$schema, ['mis_a_jour_le', 'cree_le', 'etat', 'facture_pdf', 'image_monument', 'id']), $_POST);
            $facture_name = uploader::upload($_FILES['facture_pdf']);
            $image_name   = uploader::upload($_FILES['image_monument']);
            $data['facture_pdf']    = $facture_name;
            $data['image_monument'] = $image_name;
            $id = self::save($data);

            if ($id) {
                die(json_encode([
                    "message" => "monuments_details successfully created",
                    "id" => $id
                ]));
            }

            error_log("Erreur : insertion dans la base de données échouée pour les données : " . json_encode($data));
            die(json_encode(["error" => "monuments_details, insert error"]));
        } catch (Exception $e) {
            error_log("Erreur inattendue dans POST : " . $e->getMessage());
            http_response_code(500);
            die(json_encode(["error" => "Erreur serveur interne."]));
        }
    }


    protected static array $schema =
        [
            'id', 'etat', 'image_monument', 'monument_id', 'utilisateur_id', 'cree_le', 'mis_a_jour_le', 'capacite_maximale', 'disponibilite_stationnement', 'cout_par_heure', 'facture_pdf', 'connectivite_internet', 'type_evenements_autorises', 'zones_accessibles', 'contraintes_acoustiques', 'risque_environnemental', 'materiel_disponible', 'repartition_budget', 'ressources_demandees', 'plan_activites', 'impact_environnemental', 'niveau_acces_infrastructures'
        ];
}