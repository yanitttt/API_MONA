<?php
class uploader {
    private static array $errors = [
        UPLOAD_ERR_INI_SIZE   => "Le fichier dépasse la directive upload_max_filesize dans php.ini.",
        UPLOAD_ERR_FORM_SIZE  => "Le fichier dépasse la directive MAX_FILE_SIZE spécifiée dans le formulaire HTML.",
        UPLOAD_ERR_PARTIAL    => "Le fichier n'a été que partiellement téléchargé.",
        UPLOAD_ERR_NO_FILE    => "Aucun fichier n'a été téléchargé.",
        UPLOAD_ERR_NO_TMP_DIR => "Il manque un dossier temporaire.",
        UPLOAD_ERR_CANT_WRITE => "Échec de l'écriture du fichier sur le disque.",
        UPLOAD_ERR_EXTENSION  => "Une extension PHP a arrêté le téléchargement du fichier.",
    ];

    public static function upload($file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $uniqueFileName = uniqid('file_', true) . '.' . pathinfo(basename($file['name']), PATHINFO_EXTENSION);

            // Déplacement du fichier
            if (is_uploaded_file($file['tmp_name'])) {
                if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../../uploads/' . $uniqueFileName)) {
                    error_log("Erreur : échec du transfert du fichier '{$file['name']}' sur le serveur.");
                    http_response_code(500);
                    die(json_encode(['success' => false, 'message' => 'Erreur lors du transfert du fichier sur le serveur.']));
                }
            } else {
                error_log("Erreur : fichier non valide '{$file['name']}' (tmp_name invalide).");
                http_response_code(400);
                die(json_encode(['success' => false, 'message' => 'Fichier non valide.']));
            }
        } else {
            $errorMessage = self::getUploadErrorMessage($file['error']);
            error_log("Erreur : {$errorMessage} (code erreur : {$file['error']}).");
            http_response_code(400);
            die(json_encode(['success' => false, 'message' => $errorMessage]));
        }
        return $uniqueFileName;
    }

    private static function getUploadErrorMessage($errorCode) {
        return self::$errors[$errorCode] ?? "Erreur inconnue lors du téléchargement.";
    }
}