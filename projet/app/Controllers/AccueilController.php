<?php
namespace App\Controllers;

use Core\GestionVue;

class AccueilController
{
    // Les informations de la page nécessaire au bon fonctionnement de la vue :
    private static $pageInfos = [
        'vue' => 'accueil',
        'titre' => "Page d'Accueil",
        'description' => "Description de la page d'accueil...",
        'baseUrl' => BASE_URL . '/'
    ];

    // index : Afficher la liste des utilisateurs (il s'agit de la partie chargée par défaut) :
    public static function index(): void
    {
        // Afficher la vue "vue_accueil.php".
        GestionVue::afficher_vue(self::$pageInfos, 'index');
    }
}
?>