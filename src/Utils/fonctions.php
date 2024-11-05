<?php

function connexionBDD(): PDO {
    return new PDO('mysql:host=localhost;dbname=blog', 'root', '');
}

function uploaderFichier(string $dossier, string $clef): string|false {
    $extension = pathinfo($_FILES[$clef]['name'], PATHINFO_EXTENSION);
    $nom = uniqid() . '.' . $extension;

    if (move_uploaded_file(
        $_FILES[$clef]['tmp_name'], // Point de départ
        $dossier . $nom // Point d'arrivée
    )) {
        return $nom;
    } else {
        return false;
    }
}

function rediriger(string $url) {
    header('location: ' . $url);
    die;
}