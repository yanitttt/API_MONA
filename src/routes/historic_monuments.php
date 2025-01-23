<?php
require_once __DIR__ . '/../Model.php';

class historic_monuments extends Model {
    public $id;
    public $reference;
    public $nom;
    public $type;
    public $region;
    public $departement;
    public $commune;
    public $histoire;
    public $description;
    public $adresse;
    public $auteur;
    public $datation;
    public $siecle;
    public $etablissement_affectataire;
    public $latitude;
    public $longitude;
    public $statut_juridique;
    public $dernieremaj;
    public $dateajout;
    public $copyright;
    public $photo_url;

    public static function GET() {
        if (!empty($_GET)) {
            // fetches data by filtering them on the field $col given in the url
            $col = array_key_first($_GET);
            $filter = [];

            // Handle special filters
            if ($col === "nom")
                $filter["nom"] = "nom ilike :nom";
            elseif ($col === "capacite_maximale")
                $filter["capacite_maximale"] = "capacite_maximale >= :capacite_maximale";
            global $db;
            self::check_exists_fields($_GET);

            // Build standard filters query
            $standardQueryPart = implode(' and ', array_map(function($column) {
                return "$column = :$column";
            }, array_keys(array_diff_key($_GET, $filter))));
            // Add special filters to the query
            $specialQueryPart = implode(' and ', $filter);

            // Combine the standard and special query parts
            $qry = "select * from " . static::class . " where ";
            if (!empty($standardQueryPart) && !empty($specialQueryPart))
                $qry .= $standardQueryPart . ' and ' . $specialQueryPart;
            elseif (!empty($standardQueryPart))
                $qry .= $standardQueryPart;
            elseif (!empty($specialQueryPart))
                $qry .= $specialQueryPart;

            // Prepare and bind values to the query
            $qry = $db->prepare($qry);
            foreach ($_GET as $key => $value) {
                if ($key === "nom")
                    $qry->bindValue(":$key", '%' . $value . '%');
                else
                    $qry->bindValue(":$key", $value);
            }
            $qry->execute();
            die(json_encode($qry->fetchAll()));
        }
        // fetch all data if no filter is given
        die(json_encode(self::fetchAll()));
    }

    protected static array $schema =
        [
            'id', 'reference', 'nom', 'type', 'region', 'departement', 'commune', 'histoire', 'description', 'adresse', 'auteur', 'datation', 'siecle', 'etablissement_affectataire', 'latitude', 'longitude', 'statut_juridique', 'dernieremaj', 'dateajout', 'copyright', 'photo_url',
        ];
}