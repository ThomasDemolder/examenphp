<?php
class Autoloader
{
    private static $config;

    public static function init(): void
    {
        self::charger_config();
        self::enregistrer();
    }

    private static function charger_config(): void
	{
		$config = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.json';
        self::$config = json_decode(file_get_contents($config), true)['autoloader'];
	}

    private static function enregistrer(): void
    {
        // La fonction "spl_autoload_register()" est utilisée pour enregistrer des fonctions d'autoload personnalisées.
        // Cette fonction est exécutée lorsqu'une Classe est appelée pour la première fois.
        // On lui passe un tableau en paramètre avec la Classe et la méthode devant être appelées pour gérer le chargement de la Classe.
        // (La constante magique "__CLASS__" renvoie le nom de la classe dans laquelle elle est utilisée.)
        // En résumé, lorsqu'une Classe est appelée pour la première fois, la fonction "spl_autoload_register()" est automatiquement exécutée,
        // ensuite, la méthode "charger" de la Classe actuelle (Autoloader) est lancée et le contenu du "use" déclancheur lui est passé en paramètre.
        spl_autoload_register([__CLASS__, 'charger']);
    }

	private static function charger(string $namespaceDeClasse): void
	{
        // Parcourir toutes les correspondances "namespace / chemin" du fichier de configuration :
        foreach (self::$config as $namespaceConfig => $chemins)
        {
            // Si le namespace présent dans la configuration est trouvé en première position du namespace de la Classe :
            if (strpos($namespaceDeClasse, $namespaceConfig) === 0)
            {
                // Vérifier s'il existe plusieurs chemins pour un même namespace. Si le chemin de configuration est unique, le placer dans un tableau.
                $chemins = is_string($chemins) ? [$chemins] : $chemins;

                // Parcourir les chemins :
                foreach ($chemins as $chemin)
                {
                    // Convertir le namespace par le chemin vers le fichier de la Classe visée :
                    $cheminFichier = self::convertirNamespaceEnCheminFichier($namespaceConfig, $chemin, $namespaceDeClasse);

                    // Vérifier si le fichier contenant la Classe existe :
                    if (file_exists($cheminFichier))
                    {
                        // Importer la Classe.
                        include_once $cheminFichier;

                        // Sortir des deux boucles.
                        break 2;
                    }
                }
            }
        }
	}

    private static function convertirNamespaceEnCheminFichier(string $namespaceConfig, string $chemin, string $namespaceDeClasse): string
	{
        // Remplacer du namespace de la Classe par le chemin correspondant présent dans le fichier de configuration.
        $nomDeClasse = str_replace($namespaceConfig, $chemin, $namespaceDeClasse);

        // Convertir le chemin relatif vers le fichier en chemin absolu.
        $cheminFichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . $nomDeClasse . '.php';

        // Remplacer les "/" par "DIRECTORY_SEPARATOR" pour s'assurer de la compatibilité.
        $cheminFichier = str_replace('/', DIRECTORY_SEPARATOR, $cheminFichier);

        return $cheminFichier;
    }
}