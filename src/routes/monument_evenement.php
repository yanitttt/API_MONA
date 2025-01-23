<?php
require_once __DIR__ . '/../Endpoint.php';
require_once __DIR__ . '/historic_monuments.php';
require_once __DIR__ . '/monuments_details.php';

class monument_evenement extends Endpoint {
    public static function GET() {
        global $db;

        if (!empty($_GET)) {
            if (isset($_GET['region']) || (isset($_GET['date_debut']) && isset($_GET['date_fin'])) || isset($_GET['prix'])) {
                $monuments_details = monuments_details::fetchAll();

                $ids = array_map('intval', array_column($monuments_details, 'monument_id'));
                if (empty($ids)) die(json_encode([]));

                // Construction de la première requête
                $qry = 'select * from historic_monuments';
                $conditions = [];

                $conditions[] = 'id in (' .
                    implode(',', array_map(function ($index) { // placeholder
                        return ":id_$index";
                    }, array_keys($ids))) . ')';
                if (!empty($_GET['region']))
                    $conditions[] = 'region ilike :region';
                if (!empty($conditions))
                    $qry .= ' where ' . implode(' and ', $conditions);
                $qry = $db->prepare($qry);

                foreach ($ids as $index => $id)
                    $qry->bindValue(":id_$index", $id, PDO::PARAM_INT);
                if (!empty($_GET['region']))
                    $qry->bindValue(':region', "%{$_GET['region']}%");

                $qry->execute();
                $monuments = $qry->fetchAll();

                $ids = array_map('intval', array_column($monuments, 'id'));
                if (empty($ids)) die(json_encode([]));

                // Construction de la deuxième requête
                $qry = 'select * from evenement';
                $conditions = [];

                $conditions[] = 'id_monument in (' .
                    implode(',', array_map(function ($index) {
                        return ":id_$index";
                    }, array_keys($ids))) . ')';

                if (!empty($_GET['date_debut']) && !empty($_GET['date_fin'])) {
                    $conditions[] = 'date_debut between :date_debut and :date_fin';
                    $conditions[] = 'date_fin between :date_debut and :date_fin';
                }
                if (!empty($_GET['prix']))
                    $conditions[] = 'prix <= :prix';
                if (!empty($conditions))
                    $qry .= ' where ' . implode(' and ', $conditions);

                $qry = $db->prepare($qry);

                foreach ($ids as $index => $id)
                    $qry->bindValue(":id_$index", $id, PDO::PARAM_INT);
                if (!empty($_GET['date_debut']) && !empty($_GET['date_fin'])) {
                    $qry->bindValue(':date_debut', $_GET['date_debut']);
                    $qry->bindValue(':date_fin', $_GET['date_fin']);
                }
                if (!empty($_GET['prix']))
                    $qry->bindValue(':prix', $_GET['prix']);

                $qry->execute();
                die(json_encode($qry->fetchAll()));
            }
        }
    }

    public static function POST() { die(json_encode(['error' => "unauthorized method"])); }
    public static function PUT () { die(json_encode(['error' => "unauthorized method"])); }
}