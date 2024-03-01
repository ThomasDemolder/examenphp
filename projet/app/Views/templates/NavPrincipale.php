<?php
namespace App\Views\Templates;

use Core\GestionAuthentification;

class NavPrincipale
{
    public static function creer()
    {
        return self::creer_item(BASE_URL . '/', 'Accueil') .
            self::creer_item(BASE_URL . '/contact', 'Contact') .
            self::creer_item(BASE_URL . '/inscription', 'inscription') .
            (GestionAuthentification::est_connecte() ?
            self::creer_item(BASE_URL . '/profil', 'profil') :
            self::creer_item(BASE_URL . '/connexion', 'connexion'));
    }

    private static function creer_item(string $segmentUrl, string $nomPage): string
    {
        $estPageActuelle = $_SERVER['SCRIPT_NAME'] === $segmentUrl;
        $classCss = $estPageActuelle ? 'active' : '';
        ob_start();?>
        <li><a class="<?=$classCss?>" href="<?=$segmentUrl?>"><?=$nomPage?></a></li>
        <?php return ob_get_clean();
    }
}