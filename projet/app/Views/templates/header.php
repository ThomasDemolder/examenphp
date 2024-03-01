<?php
use App\Views\Templates\NavPrincipale;

$navPrincipale = NavPrincipale::creer();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--
        Afficher le contenu de "$metaDescription" et celui de "$pageTitre" si la variable existe sinon afficher une chaîne de caractères vide.
    -->
    <meta name="description" content="<?=$metaDescription ?? '' ?>">
    <link rel="icon" type="image/png" href="<?=BASE_URL?>/public/ressources/picture/A_futuristic_Japanese-inspired_logo,_blending_trad.png">
    <link rel="stylesheet" href="<?=BASE_URL?>/public/ressources/css/style.css">
    <title>Japon 2.0</title>
</head>
<body>
    <img src="<?=BASE_URL?>/public/ressources/picture/wide_background_traditional_futuristic_japanese_inspired.jpeg" alt="background" class="background-image">
    <header>
    <div class="logo-et-titre">
        <img src="<?=BASE_URL?>/public/ressources/picture/futuristic_japanese_inspired_logo.jpeg" alt="Logo" class="logo">
        <h1>Japon 2.0</h1>
    </div>
        <nav>
            <ul>
                <?= $navPrincipale; ?>
            </ul>
        </nav>
    </header>
    <main>
