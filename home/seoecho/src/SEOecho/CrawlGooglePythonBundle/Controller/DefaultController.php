<?php
namespace SEOecho\CrawlGooglePythonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * Fonction chargée de faire le lien entre le PHP et le Python en récupérant les données du scraping
     * @param : (string) $request : des mots-clés et une URL
     * @return : (array) $resultatsGoogleAsJson : les résultats du scraping liés à ces mots-clés
    */
    public function indexAction(Request $request)
    {
        // DEBUG:
        // dump('motsCles');
        // exit();

        // Utilisation des variables globales (sinon ça ne marche pas !)
        $req = $_REQUEST['motsCles'];
        $url = $_REQUEST['url'];

        // Ouverture du fichier de paramétrage pour pouvoir le modifier
        $paramFile = '/home/seoecho/src/SEOecho/CrawlGooglePythonBundle/bin/param/parameters.txt';
        $handle = fopen($paramFile, 'w') or die('Cannot open file:  '.$paramFile);

        // Écriture de l'URL de l'utilisateur
        fwrite($handle, $url."\n");

        // Écriture du nombre de résultats de la recherche
        $nResults = 200;
        fwrite($handle, $nResults."\n");

        // Écriture du degré de pertinence
        $efficiency = 30;
        fwrite($handle, $efficiency."\n");

        // Écriture des mots-clés
        $listeMotsCles = explode(" ", $req);
        foreach ($listeMotsCles as $unMotCle) {
            fwrite($handle, $unMotCle."\n");
        }

        // Fermeture du fichier de paramétrage
        fclose($handle);

        // Éxécution du script Python (scraping)
        chdir('/home/seoecho/src/SEOecho/CrawlGooglePythonBundle/bin/');
        exec('/usr/bin/python scrape.py');

        // Récupération du fichier de sortie contenant les données à étudier
        $resultatsGoogleAsJson = file_get_contents("/home/seoecho/src/SEOecho/CrawlGooglePythonBundle/bin/searchEngine/output/output.json");

        // DEBUG :
        // dump($resultatsGoogleAsJson);
        // exit();

        return new Response($resultatsGoogleAsJson);
    }
}
