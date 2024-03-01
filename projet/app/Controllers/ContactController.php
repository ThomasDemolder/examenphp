<?php 
namespace App\Controllers;

use Core\{
    GestionVue,
    GestionFormulaire,
    GestionFrequenceRequetes,
    GestionJetonCsrf,
    GestionMessage,
    GestionMail
};

use App\Models\ContactModel;

class ContactController
{
    // Les informations de la page nécessaire au bon fonctionnement de la vue :
    private static $pageInfos = [
        'vue' => 'contact',
        'titre' => "Contact",
        'description' => "Description de la page de contact...",
        'baseUrl' => BASE_URL . '/' . 'contact' . '/'
    ];

    // index : Afficher la liste des utilisateurs (il s'agit de la partie chargée par défaut) :
    public static function index(?array $args = []): void
    {
        // Générer un nouveau jeton CSRF pour l'ajouter au formulaire de la vue.
        $args['jetonCSRF'] = GestionJetonCsrf::generer();

        // Appeler la vue.
        GestionVue::afficher_vue(self::$pageInfos, 'index', $args);
    }

    public static function envoyer(): void
    {
        // Vérifier qu'il y a eu une tentative de soumission de formulaire avec la méthode "POST".
        // Vérifier si la fréquence des requêtes est bien inférieur à 3 requêtes par seconde.
        // Vérifier si le jeton CSRF est valide.
        if ($_SERVER["REQUEST_METHOD"] == "POST" && GestionFrequenceRequetes::est_respecteLimitationRequetes(1, 3) && GestionJetonCsrf::est_valide())
        {
            // Récupérer les règles pour les champs du formulaire.
            $champsConfig = ContactModel::obtenir_champsConfig();

            // Vérifier la validité des entrées utilisateur.
            $resultat = GestionFormulaire::verifier_validiteChamps($champsConfig, $_POST);

            // Tenter d'envoyer le mail si aucune erreur n'a été trouvée :
            if (count($resultat['erreurs']) === 0 && self::envoyer_messageParMail($champsConfig))
            {
                // Mail envoyé avec succès...
                $resultat['messageValidation'] = GestionMessage::obtenir_messageValidation('form', 'envoi_succes');
            }
            else
            {
                // Echec de l'envoi du mail...
                $resultat['messageValidation'] = GestionMessage::obtenir_messageValidation('form', 'champs_echec', false);
            }

            self::index($resultat);
        }
        else
        {
            self::index();
        }
    }

    private static function envoyer_messageParMail(array $champsConfig): bool
    {
        $expediteur = "demolderthomas17@gmail.com";
        $destinataire = "thomasdemolder@icloud.com";
        $sujet = "Examen - Test d'envoi de e-mails";

        // Configurer l'en-tête.
        $entete = GestionMail::generer_entete($expediteur, $destinataire, $sujet);

        // Monter le message dans une liste non-ordonnée HTML :
        ob_start();
        ?>
            <ul>
                <?php
                // Parcourir les noms de champ à partir du tableau de configuration des champs "$champsConfig" pour afficher une liste non-ordonnée dans le mail avec les valeurs entrées par l'utilisateur:
                foreach($champsConfig as $nomDuChamp => $osef)
                {
                    ?>
                    <li><?=$nomDuChamp?>: <?=nl2br(htmlentities($_POST[$nomDuChamp] ?? ''))?></li>
                    <?php
                }
                ?>
            </ul>
        <?php
        $message = ob_get_clean();

        return mail($destinataire, $sujet, $message, $entete);
    }
}
?>