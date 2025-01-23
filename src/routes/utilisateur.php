<?php
require_once __DIR__ . '/../Model.php';

class utilisateur extends Model {
    public $id;
    public $login;
    public $mdp;
    public $email;
    public $nom;
    public $prenom;
    public $role;

    public function getLogin () { return $this->login;  }
    public function getMdp   () { return $this->mdp;    }
    public function getEmail () { return $this->email;  }
    public function getNom   () { return $this->nom;    }
    public function getPrenom() { return $this->prenom; }
    public function getRole  () { return $this->role;   }

    public static function GET() {
        if (array_key_exists('mdp', $_GET))
            die(json_encode(['error' => "you cannot access a user by password"]));
        // fetches data by filtering them on the fields given in the url
        if (!empty($_GET))
            die(json_encode(self::remove_pass(self::fetchByFilters($_GET))));
        // fetch all data if no filter is given
        die(json_encode(self::remove_pass(self::fetchAll())));
    }

    private static function remove_pass($data): ?array {
        foreach ($data as &$row)
            unset($row['mdp']);
        return $data;
    }

    public static function check_properties(array $expected, array $given) {
        parent::check_properties($expected, $given);
        $given['mdp'] = password_hash($given['mdp'], PASSWORD_DEFAULT);
        return $given;
    }

    protected static array $schema =
        [
            'id', 'login', 'mdp', 'email', 'nom', 'prenom', 'role'
        ];
}