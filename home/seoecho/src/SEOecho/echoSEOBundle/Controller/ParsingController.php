<?php
namespace SEOecho\echoSEOBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DomCrawler\Crawler;

class ParsingController extends Controller
{
    /********************************* DLTE ***********************************/

    /*
    * Liste des variables
    */
    private $positionMaxForPertinence = 10;
    private $balisesARecuperer = array('title', 'h1', 'h2', 'h3', 'h4', 'h5', 'b', 'strong', 'a');
    /********************************* DLTE ***********************************/
    /*
     * Par défaut
     * @param: (string) $dossierCrawl le nom du dossier contenant les résultats de Google
     * @return: (json) les résultats de Google triés et classés
     */
    public function indexAction($dossierCrawl)
    {
        /******************************* DLTE *********************************/
        // je créé un tableau de mes résultats avec comme clé la position
        $resultsGoogleAsArray = array();

        // path du dossier contenant les résultats de crawl
        $pathDossierCrawl = $this->get('kernel')->getRootDir().'/../var/SEOechoCrawl/'.$dossierCrawl;

        // je parse le fichier résumant la liste des résultats
        $xmlResumeResultats = file_get_contents($pathDossierCrawl.'/resultatsGG.xml');
        $crawlerResume = new Crawler($xmlResumeResultats);
        $allResultatsGG = $crawlerResume->filterXPath('//root/allresultatsgg//unresultatgg');

        // pour chaque résultat
        foreach ($allResultatsGG as $unResultatGoogle) {
            // je créé des variables dynamiques suivant le nom de l'enfant de mon xml (ex: $url pour l'enfant url, $position pour l'enfant position...)
            foreach ($unResultatGoogle->childNodes as $node) {
                $nomVarNode = $node->nodeName;
                $$nomVarNode = $node->textContent;
            }

            // j'estime la pertinence de mon résultat suivant sa position
            $pertinence = 0;
            if ($position<=$this->positionMaxForPertinence) {
                $pertinence = 1;
            } else {
                $pertinence = 2;
            }

            $resultsGoogleAsArray[$position]=array('url'=>$url, 'position'=>$position, 'pertinence'=>$pertinence);

            // je tri et classe le DOM du fichier contenant le code html de la page et l'ajoute dans le tableau global des résultats
            $resultsGoogleAsArray[$position]['balises'] = $this->classificationDom($pathDossierCrawl.'/'.$fichiersource);
        }

        // je remet mon tableau dans le "bon ordre" (trié par position)
        ksort($resultsGoogleAsArray);

        //throw new \Exception('Erreur!');

        // je créé ici une variable de SESSION car sinon, pour passer mes résultats Google en tant que data AJAX, j'ai une limit de la valeur de conf "php_value post_max_size"
        $_SESSION['SEOecho_resultatsGoogle'] = $resultsGoogleAsArray;
        return new JsonResponse($resultsGoogleAsArray);
    }
    /********************************* DLTE ***********************************/

    /********************************* DLTE ***********************************/
    /*
     * function chargée de classer les différentes balises souhaitées en récupérant la valeur HTML, les attributs...
     * @param: (string) $fichierHTML le path ou l'url du fichier
     * @return: (array) les différentes balises du fichier triées
     */
    private function classificationDom($fichierHTML)
    {
        $htmlPage = file_get_contents($fichierHTML);
        $crawlerPage = new Crawler($htmlPage);

        $allBalises = array();

        foreach ($this->balisesARecuperer as $uneBaliseARecuperer) {
            foreach ($crawlerPage->filter($uneBaliseARecuperer) as $key=>$uneBalise) {
                $textHTML='';
                foreach ($uneBalise->childNodes as $child) {
                    $textHTML .= $uneBalise->ownerDocument->saveHTML($child);
                }

                $allBalises[$uneBaliseARecuperer][$key]['html'] = htmlentities($textHTML);
                $allBalises[$uneBaliseARecuperer][$key]['text'] = strip_tags($textHTML);

                foreach ($uneBalise->attributes as $unAttribut) {
                    $allBalises[$uneBaliseARecuperer][$key][$unAttribut->name] = $unAttribut->textContent;
                }
            }
        }

        return $allBalises;
    }
    /********************************** DLTE **********************************/
  }
