<?php
namespace SEOecho\echoSEOBundle\Controller;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Crawler;

class EchoController extends Controller
{
    // Tableau contenant les balises à étudier
    private $balisesARecuperer = array('title', 'iurl', 'h1', 'h2', 'h3', 'strong', 'a', 'alt', 'text');

    /**
     * Fonction d'apprentissage chargée de passer à echo les données nécessaires pour fonctionner
     * @param : (json) $request : des mots-clés et une URL
     * @return : le taux de performance, la position estimée de la page de l'utilisateur, les meilleurs termes doublement triés,
     *          les meilleures URLs, les données de la page de l'utilisateur
     */
    public function apprentissageAction(Request $request)
    {
        // Récupération des mots-clés entrés par l'utilisateur
        $requete = $request->get('motsCles');

        // DEBUG:
        // dump($request);
        // exit();

        // Nettoyage des mots-clés
        $requeteClean = $this->nettoyageRequete($requete);

        // Récupération un à un des mots-clés nettoyés
        $listeMotsCles = explode(" ", $requete);
        $listeMotsClesClean = explode(" ", $requeteClean);

        // Variable de session qui sera utile pour recalculer la position plus tard
        $_SESSION["listeMotsClesClean"] = $listeMotsClesClean;

        // DEBUG:
        // dump($listeMotsCles);
        // dump($listeMotsClesClean);
        // exit();

        // Résultats de la recherche Google
        $tabResGoogle = array();

        // Résultats de la recherche Google modifiés : répétition des pages pertinentes
        $tabResGoogleModif = array();

        // Meilleures URLs répertoriées pour la requête donnée
        $bestURLs = array();

        // Données de la page utilisateur
        $tabResSiteCible = array();

        // Récupération du fichier JSON créé à partir du script Python
        $outputFile = file_get_contents("/home/seoecho/src/SEOecho/CrawlGooglePythonBundle/bin/searchEngine/output/output.json");
        $arrayPHP = json_decode($outputFile, true);

        // DEBUG:
        // dump($arrayPHP);
        // exit();

        // Données des pages issues de la requête
        $arrayGoogleResults = array_slice($arrayPHP, 1);

        // Données de la page utilisateur
        $arrayUserSite = array_slice($arrayPHP, 0, 1, true);

        // DEBUG:
        // dump($arrayGoogleResults);
        // dump($arrayUserSite);
        // exit();

        foreach ($arrayGoogleResults as $unResultat) {
            $chaineEcho  = $this->checkOptim_title($unResultat['title']);
            $chaineEcho .= $this->checkOptim_url($unResultat['iurl']);
            $chaineEcho .= $this->checkOptim_h1($unResultat['h1']);
            $chaineEcho .= $this->checkOptim_h2($unResultat['h2']);
            $chaineEcho .= $this->checkOptim_h3($unResultat['h3']);
            $chaineEcho .= $this->checkOptim_strong($unResultat['strong']);
            $chaineEcho .= $this->checkOptim_a($unResultat['a']);
            $chaineEcho .= $this->checkOptim_alt($unResultat['alt']);
            $chaineEcho .= $this->checkOptim_text($unResultat['text']);

            // Création du tableau de résultats Google
            $tabResGoogle[] = array(
                            'ID'=>$unResultat['url'],
                            'position'=>$unResultat['position'],
                            'pertinence'=>$unResultat['relevance'],
                            'chainedespredicteurs'=>$chaineEcho
                            );

            // Récupération des 10 pages les mieux répertoriées
            if (($unResultat['position'] < 10) && (in_array($unResultat['url'], $bestURLs) == false)) {
                array_push($bestURLs, $unResultat['url']);
            }
        }

        // Variable de session qui sera utile pour recalculer la position plus tard
        $_SESSION["tabResGoogle"] = $tabResGoogle;

        // DEBUG:
        // dump($tabResGoogle);
        // dump($bestURLs);
        // exit();


        /**********************************************************************/
        /*                 DUPLICATION DES PAGES PERTINENTES                  */
        /**********************************************************************/
        /* Cette section est réservée à la duplication des pages pertinentes. */
        /* Après de nombreux tests, il est apparu qu'il était primordial de   */
        /* donner davantage d'importance aux pages jugées pertinentes lors du */
        /* calcul des meilleurs termes (echobt) en les dupliquant un certain  */
        /* nombre de fois de manière à ce que les termes qui apparaissent sur */
        /* ces pages prennent plus d'importance en étant également dupliqués  */
        /**********************************************************************/

        // Découpage des pages pour pouvoir les multiplier différemment selon leur position
        $n1 = 10;
        $n2 = 20;
        $n3 = 30;

        // Coefficients multiplicatifs choisis pour chaque groupe de pages précédemment formés
        $coeff1 = 5;  // de la page 1   à la page $n1
        $coeff2 = 3;  // de la page $n1 à la page $n2
        $coeff3 = 2;  // de la page $n2 à la page $n3

        // Initialisation du coefficient multiplicatif
        $coeff = 0;

        // Création du nouveau tableau de résultats Google
        foreach ($tabResGoogle as $unResultat) {
            if ($unResultat['position'] < $n1) {
                while ($coeff < $coeff1) {
                    array_push($tabResGoogleModif, $unResultat);
                    $coeff++;
                }
                $coeff = 0;
            } elseif (($n1 <= $unResultat['position']) and ($unResultat['position'] < $n2)) {
                while ($coeff < $coeff2) {
                    array_push($tabResGoogleModif, $unResultat);
                    $coeff++;
                }
                $coeff = 0;
            } elseif (($n2 <= $unResultat['position']) and ($unResultat['position'] < $n3)) {
                while ($coeff < $coeff3) {
                    array_push($tabResGoogleModif, $unResultat);
                    $coeff++;
                }
                $coeff = 0;
            } else {
                array_push($tabResGoogleModif, $unResultat);
            }
        }

        /**********************************************************************/
        /*                         FIN DE LA SECTION                          */
        /**********************************************************************/


        // DEBUG:
        // dump($tabResGoogleModif);
        // exit();

        $chaineEcho  = $this->checkOptim_title($arrayUserSite[0]['title']);
        $chaineEcho .= $this->checkOptim_url($arrayUserSite[0]['iurl']);
        $chaineEcho .= $this->checkOptim_h1($arrayUserSite[0]['h1']);
        $chaineEcho .= $this->checkOptim_h2($arrayUserSite[0]['h2']);
        $chaineEcho .= $this->checkOptim_h3($arrayUserSite[0]['h3']);
        $chaineEcho .= $this->checkOptim_strong($arrayUserSite[0]['strong']);
        $chaineEcho .= $this->checkOptim_a($arrayUserSite[0]['a']);
        $chaineEcho .= $this->checkOptim_alt($arrayUserSite[0]['alt']);
        $chaineEcho .= $this->checkOptim_text($arrayUserSite[0]['text']);

        // Création du tableau des données de la page utilisateur
        $tabResSiteCible[] = array(
                            'ID'=>$arrayUserSite[0]['url'],
                            'position'=>$arrayUserSite[0]['position'],
                            'pertinence'=>$arrayUserSite[0]['relevance'],
                            'chainedespredicteurs'=>$chaineEcho
                            );

        // DEBUG:
        // dump($tabResSiteCible);
        // exit();

        // Calcul du taux de performance
        $txPerf = $this->getTxPerf($tabResGoogleModif);

        // DEBUG:
        // dump($txPerf);
        // exit();

        // Calcul de la position estimée de la page de l'utilisateur
        $positionPrevue = $this->getPosition($tabResGoogle, $tabResSiteCible, $listeMotsClesClean);

        // DEBUG:
        // dump($positionPrevue);
        // exit();

        // Calcul des meilleurs termes (triés par score)
        $lesPredicteurs = $this->getMeilleursPredicteurs($tabResGoogleModif);

        // DEBUG:
        // dump($lesPredicteurs);
        // exit();

        // Tri des meilleurs termes à la fois par balise et par score et contrôle du nombre de résultats
        $lesPredicteursTries = $this->triPredicteurs($lesPredicteurs);

        // DEBUG:
        // dump($lesPredicteursTries);
        // exit();

        // Nettoyage des résultats
        $txPerf = rtrim($txPerf);
        $positionPrevue = rtrim($positionPrevue);
        $lesPredicteurs = rtrim($lesPredicteurs);

        $positionPrevueExplode = explode(":", $positionPrevue);

        // DEBUG :
        // dump($positionPrevueExplode);
        // exit();

        $positionPrevue = array(
                          'protocole'   => $positionPrevueExplode[0],
                          'url'         => str_replace("\/\/", "", $positionPrevueExplode[1]),
                          'ponderation' => $positionPrevueExplode[2],
                          'indiceConf'  => $positionPrevueExplode[3],
                          'position'    => $positionPrevueExplode[4]
                          );

        // Renvoi des résultats echo ainsi que des données de la page de l'utilisateur et des meilleures URLs
        return new JsonResponse(array(
          'pageUtilisateur' => $arrayUserSite,
          'bestURLs' => $bestURLs,
          'txPerf' => $txPerf,
          'posPrevue' => $positionPrevue,
          'predicteurs' => $lesPredicteursTries));
    }

    /**************************************************************************/
    /*                       TRI DES MEILLEURS TERMES                         */
    /**************************************************************************/
    /* Cette section est réservée au tri des meilleurs termes.                */
    /* Initialement, echobt renvoie un certain nombre de termes (défini dans  */
    /* le fichier Network.java sous la constante nommée 'nb', fichier situé   */
    /* dans le dossier '/home/seoecho/echobt') qui sont triés dans l'ordre    */
    /* décroissant du score qui leur est attribué individuellement. Cela ne   */
    /* tient donc pas compte de l'importance de certaines balises vis-à-vis   */
    /* d'autres, comme par exemple la balise 'title' qui est plus importante  */
    /* que la balise 'strong' pour le référencement. Ainsi, il est primordial */
    /* de pouvoir trier ces balises par importance pour l'utilisateur tout en */
    /* conservant la notion de score au sein d'entre elles. De plus, il faut  */
    /* contrôler le nombre de résultats par balise pour ne pas se retrouver   */
    /* avec 199 termes de la balise 'p', 1 de la balise 'h1' et 0 de toutes   */
    /* les autres par exemple.                                                */
    /**************************************************************************/

    /**
     * Fonction chargée de trier les meilleurs termes à la fois par balise et par score
     * Elle permet aussi de contrôler le nombre de résultats à garder par balise
     * @param : (string) $predicteurs : les meilleurs termes renvoyés par echobt
     * @return : (array) $predicteursTries : les meilleurs termes doublement triés
     */
    private function triPredicteurs($predicteurs)
    {
        // Tableau des meilleurs termes triés par ordre d'importance de balise et par score au sein de celle-ci
        $predicteursTries = array(
          'title' => array(),
          'url' => array(),
          'h1' => array(),
          'h2' => array(),
          'h3' => array(),
          'strong' => array(),
          'a' => array(),
          'alt' => array(),
          'text' => array()
        );

        $predicteursExplode = array();
        $arrayPredicteurs = explode(" ", $predicteurs);

        // Création du tableau des meilleurs termes "explodés"
        foreach ($arrayPredicteurs as $unPredicteur) {
            $unPredicteurExplode = explode("_", $unPredicteur);
            array_push($predicteursExplode, $unPredicteurExplode);
        }

        // DEBUG:
        // dump($predicteursExplode);
        // exit();

        // Initialisation des compteurs
        $cmpTitle = 0;
        $cmpUrl = 0;
        $cmpH1 = 0;
        $cmpH2 = 0;
        $cmpH3 = 0;
        $cmpStrong = 0;
        $cmpA = 0;
        $cmpAlt = 0;
        $cmpText = 0;

        // Nombre de meilleurs termes à afficher pour chaque balise (à mettre en lien avec le nombre de résultats renvoyés par echobt)
        $nbTitle = 8;
        $nbUrl = 5;
        $nbH1 = 10;
        $nbH2 = 10;
        $nbH3 = 10;
        $nbStrong = 5;
        $nbA = 15;
        $nbAlt = 15;
        $nbText = 30;

        // Création du tableau des meilleurs termes doublement triés et contrôlés selon le nombre de résultats sélectionnés par balise
        foreach ($predicteursExplode as $unPredicteur) {
            if (($unPredicteur[1] == 'title') && ($cmpTitle < $nbTitle)) {
                array_push($predicteursTries['title'], $unPredicteur[0]);
                $cmpTitle++;
            } elseif (($unPredicteur[1] == 'url') && ($cmpUrl < $nbUrl)) {
                array_push($predicteursTries['url'], $unPredicteur[0]);
                $cmpUrl++;
            } elseif (($unPredicteur[1] == 'h1') && ($cmpH1 < $nbH1)) {
                array_push($predicteursTries['h1'], $unPredicteur[0]);
                $cmpH1++;
            } elseif (($unPredicteur[1] == 'h2') && ($cmpH2 < $nbH2)) {
                array_push($predicteursTries['h2'], $unPredicteur[0]);
                $cmpH2++;
            } elseif (($unPredicteur[1] == 'h3') && ($cmpH3 < $nbH3)) {
                array_push($predicteursTries['h3'], $unPredicteur[0]);
                $cmpH3++;
            } elseif (($unPredicteur[1] == 'strong') && ($cmpStrong < $nbStrong)) {
                array_push($predicteursTries['strong'], $unPredicteur[0]);
                $cmpStrong++;
            } elseif (($unPredicteur[1] == 'a') && ($cmpA < $nbA)) {
                array_push($predicteursTries['a'], $unPredicteur[0]);
                $cmpA++;
            } elseif (($unPredicteur[1] == 'alt') && ($cmpAlt < $nbAlt)) {
                array_push($predicteursTries['alt'], $unPredicteur[0]);
                $cmpAlt++;
            } elseif (($unPredicteur[1] == 'text') && ($cmpText < $nbText)) {
                array_push($predicteursTries['text'], $unPredicteur[0]);
                $cmpText++;
            }
        }

        return $predicteursTries;
    }

    /**************************************************************************/
    /*                           FIN DE LA SECTION                            */
    /**************************************************************************/


    /**
     * Fonction chargée de nettoyer une requête donnée
     * @param : (string) $requete : une requête
     * @return : (string) $requeteClean : cette requête nettoyée
     */
    private function nettoyageRequete(&$requete)
    {
        /* Passage de la requête en minuscule pour ne pas tenir compte des
           accents aussi en majuscule */
        $requete = strtolower($requete);

        if (mb_detect_encoding($requete, 'UTF-8', true) != 'UTF-8') {
            $requete = utf8_encode($requete);
        }

        $caracInterdits = array('é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'î', 'ï', 'ô', 'ö', 'ù', 'ü', 'û', '&');
        $caracRemplace =  array('e', 'e', 'e', 'e', 'a', 'a', 'a', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'et');

        $requeteClean = str_replace($caracInterdits, $caracRemplace, $requete);

        return $requeteClean;
    }


    /**************************************************************************/
    /*                               CHECKOPTIM                               */
    /**************************************************************************/
    /* Cette section est réservée aux checkOptim.                             */
    /* Ces fonctions permettent d'attribuer un suffixe à chaque terme         */
    /* récupéré depuis les données des pages apparaissant dans les résultats  */
    /* de Google. Ce suffixe nous permet en fait de ne pas perdre             */
    /* l'information liée à la balise associée au terme courant pour ensuite  */
    /* pouvoir à la fois optimiser balise par balise (au lieu d'optimser      */
    /* tous les termes en même temps) grâce à echobt puis de dire à           */
    /* l'utilisateur sur quelle balise il doit travailler.                    */
    /**************************************************************************/

    // Balise "title"
    private function checkOptim_title($balise)
    {
        $chaineEcho = '';

        // Pour chaque balise
        foreach ($balise as $uneBalise) {
            // On sépare les termes un à un
            $listeTermes = explode(' ', $uneBalise);

            // Pour chaque terme apparaissant dans cette balise
            foreach ($listeTermes as $unTerme) {
                // Un suffixe identifiant la balise associée au terme est ajouté
                $chaineEcho .= ' '.$unTerme.'_TITLE';
            }
        }

        // DEBUG :
        // dump($chaineEcho);
        // exit();

        return $chaineEcho;
    }

    // Mots-clés constituant l'URL
    private function checkOptim_url($balise)
    {
        $chaineEcho = '';

        foreach ($balise as $uneBalise) {
            $listeTermes = explode(' ', $uneBalise);
            foreach ($listeTermes as $unTerme) {
                $chaineEcho .= ' '.$unTerme.'_URL';
            }
        }

        return $chaineEcho;
    }

    // Balise "h1"
    private function checkOptim_h1($balise)
    {
        $chaineEcho = '';

        foreach ($balise as $uneBalise) {
            $listeTermes = explode(' ', $uneBalise);
            foreach ($listeTermes as $unTerme) {
                $chaineEcho .= ' '.$unTerme.'_H1';
            }
        }

        return $chaineEcho;
    }

    // Balise "h2"
    private function checkOptim_h2($balise)
    {
        $chaineEcho = '';

        foreach ($balise as $uneBalise) {
            $listeTermes = explode(' ', $uneBalise);
            foreach ($listeTermes as $unTerme) {
                $chaineEcho .= ' '.$unTerme.'_H2';
            }
        }

        return $chaineEcho;
    }

    // Balise "h3"
    private function checkOptim_h3($balise)
    {
        $chaineEcho = '';

        foreach ($balise as $uneBalise) {
            $listeTermes = explode(' ', $uneBalise);
            foreach ($listeTermes as $unTerme) {
                $chaineEcho .= ' '.$unTerme.'_H3';
            }
        }

        return $chaineEcho;
    }

    // Balise "strong"
    private function checkOptim_strong($balise)
    {
        $chaineEcho = '';

        foreach ($balise as $uneBalise) {
            $listeTermes = explode(' ', $uneBalise);
            foreach ($listeTermes as $unTerme) {
                $chaineEcho .= ' '.$unTerme.'_STRONG';
            }
        }

        return $chaineEcho;
    }

    // Balise "a"
    private function checkOptim_a($balise)
    {
        $chaineEcho = '';

        foreach ($balise as $uneBalise) {
            $listeTermes = explode(' ', $uneBalise);
            foreach ($listeTermes as $unTerme) {
                $chaineEcho .= ' '.$unTerme.'_A';
            }
        }

        return $chaineEcho;
    }

    // Balise "img", propriété "alt"
    private function checkOptim_alt($balise)
    {
        $chaineEcho = '';

        foreach ($balise as $uneBalise) {
            $listeTermes = explode(' ', $uneBalise);
            foreach ($listeTermes as $unTerme) {
                $chaineEcho .= ' '.$unTerme.'_ALT';
            }
        }

        return $chaineEcho;
    }

    // Balise "p"
    private function checkOptim_text($balise)
    {
        $chaineEcho = '';

        foreach ($balise as $uneBalise) {
            $listeTermes = explode(' ', $uneBalise);
            foreach ($listeTermes as $unTerme) {
                $chaineEcho .= ' '.$unTerme.'_TEXT';
            }
        }

        return $chaineEcho;
    }

    /**************************************************************************/
    /*                           FIN DE LA SECTION                            */
    /**************************************************************************/


    /**************************************************************************/
    /*                       APPEL AUX ALGORITHMES ECHO                       */
    /**************************************************************************/
    /* Cette section est réservée aux appels aux algorithmes echo.            */
    /* Chacun de ces algorithmes d'apprentissage machine a une fonction       */
    /* particulière (se référer aux commentaires pour chacun d'entre eux).    */
    /* Les codes sources de ces algorithmes se trouvent dans les dossiers     */
    /* correspondants dans '/home/seoecho'. Les fonctions qui suivent font    */
    /* elles appel à d'autre fonctions aux noms correspondants situées dans   */
    /* le dossier '/home/seoecho/src/SEOecho/echoSEOBundle/Services'.         */
    /**************************************************************************/

    /**
     * Fonction chargée de récupérer le taux de performance pour une requête donnée
     * @param : (array) $tabResG : les resultats Google
     * @return : (int) $txPerf : le taux de performance
     */
    private function getTxPerf($tabResG)
    {
        /* Appel du service responsable de l'interrogation de Echov */
        $echov = $this->get('echov');
        $txPerf = $echov->getTxPerfs($tabResG);
        return $txPerf;
    }

    /**
     * Fonction chargée de récupérer la position prévue d'une page pour des mots-clés et une URL donnés
     * @param : (array) $tabResG, $tabPageatester (string) $lesmotscles : résultats Google, page à tester, mots-clés
     * @return : (int) $positionPrevue : la position estimée de la page à tester
     */

    private function getPosition($tabResG, $tabPageatester, $lesmotscles)
    {
        $echos = $this->get('echos');
        $positionPrevue = $echos->getpositiongoogle($tabResG, $tabPageatester, $lesmotscles);
        return $positionPrevue;
    }

    /**
     * Fonction chargée de récupérer les scores de tous les meilleurs termes à partir des résultats Google
     * @param : (array) $tabResG : les resultats Google
     * @return : (string) $chainedepredicteurs : une chaine avec un score pour chaque terme predicteur
     */
    private function getMeilleursPredicteurs($tabResG)
    {
        $echobt = $this->get('echobt');
        $chainedepredicteurs = $echobt->getmeilleurpredicteurs($tabResG);
        return $chainedepredicteurs;
    }

    /**************************************************************************/
    /*                           FIN DE LA SECTION                            */
    /**************************************************************************/


    /**
     * Fonction chargée de calculer la position de la page de l'utilisateur (notamment après modification des champs de texte)
     * @param : aucun
     * @return : aucun
     */
    public function refreshAction()
    {
        // Récupération de l'objet JSON contenant les nouvelles valeurs
        $newJSON = $_POST["newJSON"];

        // DEBUG:
        // dump($newJSON);
        // exit();

        $newArrayUserSite = json_decode($newJSON, true);

        // DEBUG:
        // dump($newArrayUserSite);
        // exit();

        $chaineEcho  = $this->checkOptim_title($newArrayUserSite['title']);
        $chaineEcho .= $this->checkOptim_url($newArrayUserSite['iurl']);
        $chaineEcho .= $this->checkOptim_h1($newArrayUserSite['h1']);
        $chaineEcho .= $this->checkOptim_h2($newArrayUserSite['h2']);
        $chaineEcho .= $this->checkOptim_h3($newArrayUserSite['h3']);
        $chaineEcho .= $this->checkOptim_strong($newArrayUserSite['strong']);
        $chaineEcho .= $this->checkOptim_a($newArrayUserSite['a']);
        $chaineEcho .= $this->checkOptim_alt($newArrayUserSite['alt']);
        $chaineEcho .= $this->checkOptim_text($newArrayUserSite['text']);

        // Création du tableau des nouvelles données de la page utilisateur
        $newTabResSiteCible[] = array(
                                'ID' => 'pageModif',
                                'position' => 0,
                                'pertinence' => 0,
                                'chainedespredicteurs' => $chaineEcho
                                );

        // DEBUG:
        // dump($newTabResSiteCible);
        // exit();

        // Calcul de la nouvelle position (appel à echo_pos)
        $positionPrevue = $this->getPosition($_SESSION["tabResGoogle"], $newTabResSiteCible, $_SESSION["listeMotsClesClean"]);

        // DEBUG:
        // dump($positionPrevue);
        // exit();

        $positionPrevue = rtrim($positionPrevue);
        $positionPrevueExplode = explode(":", $positionPrevue);

        // DEBUG:
        // dump($positionPrevueExplode);
        // exit();

        $score = rtrim($positionPrevueExplode[3], ";");

        // DEBUG:
        // dump($score);
        // exit();

        // Affichage de la nouvelle position
        echo 'Nouvelle position : <b>'.$score.'</b>';

        return new Response();
    }
}
