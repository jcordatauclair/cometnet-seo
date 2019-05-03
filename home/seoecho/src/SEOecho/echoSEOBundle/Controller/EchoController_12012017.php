<?php

namespace SEOecho\echoSEOBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\DomCrawler\Crawler;

class EchoController extends Controller {
  
   private $balisesARecuperer = array('title', 'h1', 'h2', 'h3', 'h4', 'h5', 'b', 'strong', 'a');
  /*
   * function apprentissage chargée de passer à Echo des résultats Google
   * @param: (json) $resultatsGoogleAsJson les résultats Google
   */
  public function apprentissageAction(Request $request) {
    //$requete = 'Hôtels Villard-de-lans vercors';
    
    $tabResGoogle=array();
    $requete = $request->get('motsCles');
    $requeteClean = $this->nettoyageRequete($requete);

    // j'explode ma requete pour récupérer séparement chaque mot-clé
    $listeMotsCles = explode(" ", $requete);
    $listeMotsClesClean = explode(" ", $requeteClean);

    // je récupère  ici une variable de SESSION car j'ai une limit de la valeur de conf "php_value post_max_size"
    //$resultatsGoogle = $request->get('resultatsGoogle');
    //print_r($_SESSION['SEOecho_resultatsGoogle']);
    $resultatsGoogle = $_SESSION['SEOecho_resultatsGoogle'];
    
    $resultatsGoogle = $this->nettoyageHtmlBalises($resultatsGoogle);
    //estimation du taux de performance s'appuie sur echov
    // pour chaque résultats
    foreach ($resultatsGoogle as $unResultat) {
      
      //$chaineEcho = $unResultat['position'].' '.$unResultat['pertinence'];
     
      if(isset($unResultat['balises']['title']))    $chaineEcho  = $this->checkOptim_title($unResultat['balises']['title'], $listeMotsCles, $listeMotsClesClean);
      if(isset($unResultat['balises']['h1']))       $chaineEcho .= $this->checkOptim_h1($unResultat['balises']['h1'], $listeMotsCles, $listeMotsClesClean);
      if(isset($unResultat['balises']['h2']))       $chaineEcho .= $this->checkOptim_h2($unResultat['balises']['h2'], $listeMotsCles, $listeMotsClesClean);
      if(isset($unResultat['balises']['h3']))       $chaineEcho .= $this->checkOptim_h3($unResultat['balises']['h3'], $listeMotsCles, $listeMotsClesClean);
      if(isset($unResultat['balises']['h4']))       $chaineEcho .= $this->checkOptim_h4($unResultat['balises']['h4'], $listeMotsCles, $listeMotsClesClean);
      if(isset($unResultat['balises']['h5']))       $chaineEcho .= $this->checkOptim_h5($unResultat['balises']['h5'], $listeMotsCles, $listeMotsClesClean);
      if(isset($unResultat['balises']['b']))        $chaineEcho .= $this->checkOptim_b($unResultat['balises']['b'], $listeMotsCles, $listeMotsClesClean);
      if(isset($unResultat['balises']['strong']))   $chaineEcho .= $this->checkOptim_strong($unResultat['balises']['strong'], $listeMotsCles, $listeMotsClesClean);
      if(isset($unResultat['balises']['a']))        $chaineEcho .= $this->checkOptim_a($unResultat['balises']['a'], $listeMotsCles, $listeMotsClesClean);
      
      $tabResGoogle[]=array('ID'=>$unResultat['url'],
                            'position'=>$unResultat['position'],
                            'pertinence'=>$unResultat['pertinence'],
                            'chainedespredicteurs'=>$chaineEcho);

    }
    
    $txperf=$this->getTxPerf($tabResGoogle);
    
    //estimation de la position de la page avec un score et une probabilité de pertinance s'appuie sur echov
    //$siteCible="http://www.hotel-les-playes.com";
    $siteCible=$request->get('url');
    //dump($siteCible);exit;
    
    
    $tabSiteCible=$this->classificationDom($siteCible);
    
    if(isset($tabSiteCible['title']))    $chaineEcho  = $this->checkOptim_title($tabSiteCible['title'], $listeMotsCles, $listeMotsClesClean);
    if(isset($tabSiteCible['h1']))       $chaineEcho .= $this->checkOptim_h1($tabSiteCible['h1'], $listeMotsCles, $listeMotsClesClean);
    if(isset($tabSiteCible['h2']))       $chaineEcho .= $this->checkOptim_h2($tabSiteCible['h2'], $listeMotsCles, $listeMotsClesClean);
    if(isset($tabSiteCible['h3']))       $chaineEcho .= $this->checkOptim_h3($tabSiteCible['h3'], $listeMotsCles, $listeMotsClesClean);
    if(isset($tabSiteCible['h4']))       $chaineEcho .= $this->checkOptim_h4($tabSiteCible['h4'], $listeMotsCles, $listeMotsClesClean);
    if(isset($tabSiteCible['h5']))       $chaineEcho .= $this->checkOptim_h5($tabSiteCible['h5'], $listeMotsCles, $listeMotsClesClean);
    if(isset($tabSiteCible['b']))        $chaineEcho .= $this->checkOptim_b($tabSiteCible['b'], $listeMotsCles, $listeMotsClesClean);
    if(isset($tabSiteCible['strong']))   $chaineEcho .= $this->checkOptim_strong($tabSiteCible['strong'], $listeMotsCles, $listeMotsClesClean);
    if(isset($tabSiteCible['a']))        $chaineEcho .= $this->checkOptim_a($tabSiteCible['a'], $listeMotsCles, $listeMotsClesClean);
      
    $tabResSiteCible[]=array('ID'=>$siteCible,
                            'position'=>0,
                            'pertinence'=>0,
                            'chainedespredicteurs'=>$chaineEcho);
    
    $positionprevue=$this->getPosition($tabResGoogle,$tabResSiteCible,$listeMotsClesClean);
    
    //Conseils sur les termes predicteurs s'appuie sur echobt. Classement des termes predicteurs en fonction de leur score - equivalent à l'environnement semantique
    //d'une recherche
    $lespredicteurs=$this->getMeilleursPredicteurs($tabResGoogle);
    
    //dump($lespredicteurs);exit;
    
    /*// POUR DEV VALEURS EN DUR
    $txperf = "0,60\n";
    $positionprevue = "http:\/\/vercors.org:0,020016:0,375000;\n";
    $lespredicteurs = "hotels_a villard_a lans_a de_title villard_title de_strong villard_h1 lans_title de_h1 de_h4 villard_strong de_h2 de_h3 lans_h1 lans_h2 lans_h3 de_a villard_h3 lans_strong\n";
*/
   
    // je corrige les infos r�cup�r�es par ECHO
    //    --> je supprime les retours � la ligne en fin de chaine
    $txperf =  rtrim($txperf);
    $positionprevue = rtrim($positionprevue);
    $lespredicteurs = rtrim($lespredicteurs);
    
    
    //    --> j'explode le retour "positionprevue"
    $positionprevueExplode = explode (":", $positionprevue);
    $positionprevue=array('protocole'   => $positionprevueExplode[0],
                          'url'         => str_replace("\/\/", "", $positionprevueExplode[1]),
                          'ponderation' => $positionprevueExplode[2],
                          'indiceConf'  => $positionprevueExplode[3]);
    
    // --> je calcule le taux de correspondance des diff�rents predicteurs
    $allPredicteurClasses = $this->calculCorrespondancePredicteurs($tabSiteCible, $lespredicteurs);
    
    
    /*echo 'le taux de performance est '.$txperf .'<br>';
    echo 'la position de la page estimée = '.$positionprevue.'<br>';
    echo 'les termes predicteurs classés :'.$lespredicteurs;*/

    
    //throw new \Exception('Erreur!');    
    //return new Response('');
    return new JsonResponse(array('txPerf' => $txperf, 'posPrevue' => $positionprevue, 'predicteurs' => $allPredicteurClasses));
  }
  
  
  private function calculCorrespondancePredicteurs($tabSiteCible, $lespredicteurs) {
    $classSmithWatermanGotoh = $this->get('smithwatermangotoh');
    
    // j'explode le retour "lespredicteurs"
    $lespredicteursExplode = explode (" ", $lespredicteurs);
    
    foreach ($lespredicteursExplode as $unPredicteur) {
      $unPredicteurExplode = explode ("_", $unPredicteur);

      $pourcentageSimilitude = 0;
      
      $balisesPageCible = array();
      $compteurNbBalises = 0;
      
      if(isset($tabSiteCible[$unPredicteurExplode[1]])) {
        foreach ($tabSiteCible[$unPredicteurExplode[1]] as $uneBalise) {
          
          if(trim($uneBalise['text'])) {
            
            /*similar_text($uneBalise['text'], $unPredicteurExplode[0], $percent);
            $levenshtein = levenshtein($uneBalise['text'], $unPredicteurExplode[0]);
            $classJaroWinkler = $this->get('jarowinkler');
            $classJaroWinkler->compare($uneBalise['text'], $unPredicteurExplode[0])*100);*/
            
            $percent = $classSmithWatermanGotoh->compare(strtolower($uneBalise['text']), strtolower($unPredicteurExplode[0]))*100;
                    
            $pourcentageSimilitude = $pourcentageSimilitude+$percent;
            $balisesPageCible[] = array('terme'=>$uneBalise['text'], 'pourcentageSimilitude'=>$percent);
            $compteurNbBalises++;
          }          
        }
      
        $allPredicteurClasses[]=array('terme'=>$unPredicteurExplode[0], 'balise'=>$unPredicteurExplode[1], 'pourcentageSimilitude'=>$pourcentageSimilitude/$compteurNbBalises, 'balisesPageCible'=>$balisesPageCible);
      }
    }
    
    return $allPredicteurClasses;
  }
          
  /*
  * function nettoyageHtmlBalises chargée de nettoyer les balises des différents résultats google (accents, encodage...)
  * @param: (array) $resultatsGoogle le tableau des résultats Google
  * @return: (array) $resultatsGoogle le tableau des résultats Google modifiés
  */
  private function nettoyageHtmlBalises(&$resultatsGoogle){
    foreach ($resultatsGoogle as $unResultat) {
      foreach($unResultat['balises'] as $uneBalise){
        foreach ($uneBalise as $uneLigneBalise) {
          
          $uneLigneBalise['html'] = html_entity_decode($uneLigneBalise['html']);

          if (mb_detect_encoding($uneLigneBalise['text'])!='UTF-8') {
            $uneLigneBalise['text'] = utf8_encode($uneLigneBalise['text']);
            $uneLigneBalise['html'] = utf8_encode($uneLigneBalise['html']);
          }
          else {
             $uneLigneBalise['text'] = utf8_decode($uneLigneBalise['text']);
            $uneLigneBalise['html'] = utf8_decode($uneLigneBalise['html']);
          }

        }
      }
    }
    return $resultatsGoogle;
  }
    
 /*
  * function nettoyageRequete chargée de supprimer d'une requête les accents, "stop-words..."
  * @param: (string) requete les mot-clés recherchés
  * @return: (string) requete les mot-clés recherchés modifiés
  */
  private function nettoyageRequete(&$requete) {
    // je passe ma requete en minuscule pour ne pas tenir compte des accents aussi en majuscule
    $requete = strtolower($requete);
    
    if(mb_detect_encoding($requete, 'UTF-8', true) != 'UTF-8') $requete = utf8_encode($requete);

    $caracInterdits = array('é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'î', 'ï', 'ô', 'ö', 'ù', 'ü', 'û', '&');
    $caracRemplace =  array('e', 'e', 'e', 'e', 'a', 'a', 'a', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'et');
  
    $requeteClean = str_replace($caracInterdits, $caracRemplace, $requete);
    
    return $requeteClean;
  }
      
 /*
  * function getAllCombinaisons chargée de retourner toutes les concanténations de termes possibles
  * @param: (array) $listeMotsClesArray le tableau de mots-clés
  * @return: (array) $tableau_index_possibles le tableau de mots-clés modifiés
  */  
  private function getAllCombinaisons($listeMotsClesArray){
    $long_tabl = count($listeMotsClesArray); 
    $nbre_combin = pow($long_tabl, $long_tabl); 

    for($i=0; $i<$nbre_combin; $i++) { 
      $chaine_convertie = base_convert($i, 10, $long_tabl); 
      while (strlen($chaine_convertie) < $long_tabl) { 
        $chaine_convertie = '0'.$chaine_convertie; 
      } 
      $tabl_indice_encours = str_split($chaine_convertie); 

      if ($tabl_indice_encours == array_unique($tabl_indice_encours)) { 
        $chaine_finale = ''; 
        foreach ($tabl_indice_encours as $key=>$element) { 
          if ($key!=0) $chaine_finale .= '(.*)'; // je concatene mes possibilités avec un (.*) pour pouvoir l'utiliser dans les regex des onctions checkOptim
          $chaine_finale .= $listeMotsClesArray[$element]; 
        } 
        $tableau_index_possibles[] = $chaine_finale; 
      } 
    } 
    
    return $tableau_index_possibles; 
  }
  
  
  
  
  
  
  private function checkOptim_title($balise, $listeMotsCles, $listeMotsClesClean) {   
    $chaineEcho = '';

    // j'ajoute à mon tableau la liste des combinaisons des différents mots-clés
    $listeMotsClesClean = array_merge($listeMotsClesClean, $this->getAllCombinaisons($listeMotsClesClean));
    
    foreach ($balise as $uneBalise) {
      foreach ($listeMotsClesClean as $unMotCleClean) {
        if (preg_match("/\b".$unMotCleClean."\b/", $this->nettoyageRequete($uneBalise['text']))){
          //echo "<b>".$unMotCleClean." IS IN TITLE <i>(".$uneBalise['text'].")</i></b><br />";
          $chaineEcho .= ' '.str_replace('(.*)', '+', $unMotCleClean).'_TITLE';
        }
        //else echo "<b>".$unMotCleClean." IS NOT IN TITLE <i>(".$uneBalise['text'].")</i></b><br />";
      }
    }
    return $chaineEcho;
  }
  
  private function checkOptim_h1($balise, $listeMotsCles, $listeMotsClesClean) {
    $chaineEcho = '';

    // j'ajoute à mon tableau la liste des combinaisons des différents mots-clés
    $listeMotsClesClean = array_merge($listeMotsClesClean, $this->getAllCombinaisons($listeMotsClesClean));    
    
    foreach ($balise as $uneBalise) {
      foreach ($listeMotsClesClean as $unMotCleClean) {
        if (preg_match("/\b".$unMotCleClean."\b/", $this->nettoyageRequete($uneBalise['text']))){
          //echo "<b>".$unMotCleClean." IS IN H1 <i>(".$uneBalise['text'].")</i></b><br />";
          $chaineEcho .= ' '.$unMotCleClean.'_H1';
        }
        //else echo "<b>".$unMotCleClean." IS NOT IN H1 <i>(".$uneBalise['text'].")</i></b><br />";
      }
    }
    return $chaineEcho;
  }

  private function checkOptim_h2($balise, $listeMotsCles, $listeMotsClesClean) {   
    $chaineEcho = '';

    // j'ajoute à mon tableau la liste des combinaisons des différents mots-clés
    $listeMotsClesClean = array_merge($listeMotsClesClean, $this->getAllCombinaisons($listeMotsClesClean));    
    
    foreach ($balise as $uneBalise) {
      foreach ($listeMotsClesClean as $unMotCleClean) {
        if (preg_match("/\b".$unMotCleClean."\b/", $this->nettoyageRequete($uneBalise['text']))){
          //echo "<b>".$unMotCleClean." IS IN H2 <i>(".$uneBalise['text'].")</i></b><br />";
          $chaineEcho .= ' '.$unMotCleClean.'_H2';
        }
        //else echo "<b>".$unMotCleClean." IS NOT IN H2 <i>(".$uneBalise['text'].")</i></b><br />";
      }
    }
    
    return $chaineEcho;
  }
  
  private function checkOptim_h3($balise, $listeMotsCles, $listeMotsClesClean) {   
    $chaineEcho = '';

    // j'ajoute à mon tableau la liste des combinaisons des différents mots-clés
    $listeMotsClesClean = array_merge($listeMotsClesClean, $this->getAllCombinaisons($listeMotsClesClean));    
    
    foreach ($balise as $uneBalise) {
      foreach ($listeMotsClesClean as $unMotCleClean) {
        if (preg_match("/\b".$unMotCleClean."\b/", $this->nettoyageRequete($uneBalise['text']))){
          //echo "<b>".$unMotCleClean." IS IN H3 <i>(".$uneBalise['text'].")</i></b><br />";
          $chaineEcho .= ' '.$unMotCleClean.'_H3';
        }
        //else echo "<b>".$unMotCleClean." IS NOT IN H3 <i>(".$uneBalise['text'].")</i></b><br />";
      }
    }
    return $chaineEcho;
  }
  
  private function checkOptim_h4($balise, $listeMotsCles, $listeMotsClesClean) {   
    $chaineEcho = '';

    // j'ajoute à mon tableau la liste des combinaisons des différents mots-clés
    $listeMotsClesClean = array_merge($listeMotsClesClean, $this->getAllCombinaisons($listeMotsClesClean));    
    
    foreach ($balise as $uneBalise) {
      foreach ($listeMotsClesClean as $unMotCleClean) {
        if (preg_match("/\b".$unMotCleClean."\b/", $this->nettoyageRequete($uneBalise['text']))){
          //echo "<b>".$unMotCleClean." IS IN H4 <i>(".$uneBalise['text'].")</i></b><br />";
          $chaineEcho .= ' '.$unMotCleClean.'_H4';
        }
        //else echo "<b>".$unMotCleClean." IS NOT IN H4 <i>(".$uneBalise['text'].")</i></b><br />";
      }
    }
    return $chaineEcho;
  }
  
  private function checkOptim_h5($balise, $listeMotsCles, $listeMotsClesClean) {   
    $chaineEcho = '';

    // j'ajoute à mon tableau la liste des combinaisons des différents mots-clés
    $listeMotsClesClean = array_merge($listeMotsClesClean, $this->getAllCombinaisons($listeMotsClesClean));    
    
    foreach ($balise as $uneBalise) {
      foreach ($listeMotsClesClean as $unMotCleClean) {
        if (preg_match("/\b".$unMotCleClean."\b/", $this->nettoyageRequete($uneBalise['text']))){
          //echo "<b>".$unMotCleClean." IS IN H5 <i>(".$uneBalise['text'].")</i></b><br />";
          $chaineEcho .= ' '.$unMotCleClean.'_H5';
        }
        //else echo "<b>".$unMotCleClean." IS NOT IN H5 <i>(".$uneBalise['text'].")</i></b><br />";
      }
    }
    return $chaineEcho;
  }
  
  private function checkOptim_b($balise, $listeMotsCles, $listeMotsClesClean) {   
    $chaineEcho = '';

    // j'ajoute à mon tableau la liste des combinaisons des différents mots-clés
    $listeMotsClesClean = array_merge($listeMotsClesClean, $this->getAllCombinaisons($listeMotsClesClean));    
    
    foreach ($balise as $uneBalise) {
      foreach ($listeMotsClesClean as $unMotCleClean) {
        if (preg_match("/\b".$unMotCleClean."\b/", $this->nettoyageRequete($uneBalise['text']))){
          //echo "<b>".$unMotCleClean." IS IN B <i>(".$uneBalise['text'].")</i></b><br />";
          $chaineEcho .= ' '.$unMotCleClean.'_B';
        }
        //else echo "<b>".$unMotCleClean." IS NOT IN B <i>(".$uneBalise['text'].")</i></b><br />";
      }
    }
    return $chaineEcho;
  }
  
  private function checkOptim_strong($balise, $listeMotsCles, $listeMotsClesClean) {   
    $chaineEcho = '';

    // j'ajoute à mon tableau la liste des combinaisons des différents mots-clés
    $listeMotsClesClean = array_merge($listeMotsClesClean, $this->getAllCombinaisons($listeMotsClesClean));    
    
    foreach ($balise as $uneBalise) {
      foreach ($listeMotsClesClean as $unMotCleClean) {
        if (preg_match("/\b".$unMotCleClean."\b/", $this->nettoyageRequete($uneBalise['text']))){
          //echo "<b>".$unMotCleClean." IS IN STRONG <i>(".$uneBalise['text'].")</i></b><br />";
          $chaineEcho .= ' '.$unMotCleClean.'_STRONG';
        }
        //else echo "<b>".$unMotCleClean." IS NOT IN STRONG <i>(".$uneBalise['text'].")</i></b><br />";
      }
    }
    return $chaineEcho;
  }
  
  private function checkOptim_a($balise, $listeMotsCles, $listeMotsClesClean) {   
    $chaineEcho = '';

    // j'ajoute à mon tableau la liste des combinaisons des différents mots-clés
    $listeMotsClesClean = array_merge($listeMotsClesClean, $this->getAllCombinaisons($listeMotsClesClean));    
    
    foreach ($balise as $uneBalise) {
      foreach ($listeMotsClesClean as $unMotCleClean) {
        if (preg_match("/\b".$unMotCleClean."\b/", $this->nettoyageRequete($uneBalise['text']))){
          //echo "<b>".$unMotCleClean." IS IN A <i>(".$uneBalise['text'].")</i></b><br />";
          $chaineEcho .= ' '.$unMotCleClean.'_A';
        }
        //else echo "<b>".$unMotCleClean." IS NOT IN A <i>(".$uneBalise['text'].")</i></b><br />";
      }
    }
    return $chaineEcho;
  }
  
  /**
   * gettxperf fonction du controlleur permettant de recuperer le taux de performance pour une liste de mots clés
   * @param un tableau $tabResG les resultats google pour une liste d'expressions 
   * @return: un int le taux de performance
   */
  
  private function getTxPerf($tabResG){
      //appel du service responsable de l'interrogation de Echov
      //$txperf=10;
      $echov = $this->get('echov');
      //dump("ok");exit;
      $txperf=$echov->getTxPerfs($tabResG);
      //echo 'jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj';
      return $txperf;
      
  }
  
  /**
   * getposition fonction du controlleur permettant de recuperer la position prevue pour une liste de mots cles et la page à tester
   * @param un tableau $tabRes les resultats google pour une liste d'expressions,la page à tester, les mots cles
   * @return: un int position de la page à tester
   */
  
  private function getPosition($tabResG,$tabPageatester,$lesmotscles){
      $echos=$this->get('echos');
      $positionprevue=$echos->getpositiongoogle($tabResG,$tabPageatester,$lesmotscles);
      return $positionprevue;
  }
  
   /**
    * getmeilleurspredicteurs fonction du controlleur permettant de recupere les scores de tous les termes predicteurs à partir des resultats google
    * @param : $tab des resultats google
    * @return une chaine avec un score pour chaque terme predicteur
    */
  private function getMeilleursPredicteurs($tabResG){
      $echobt=$this->get('echobt');
      $chainedepredicteurs=$echobt->getmeilleurpredicteurs($tabResG);
      return $chainedepredicteurs;
  }
  
   /* ATTENTION : COPIER/COLLER de ParsingController - A transformer en service !!!
   * function chargée de classer les différentes balises souhaitées en récupérant la valeur HTML, les attributs...
   * @param: (string) $fichierHTML le path ou l'url du fichier
   * @return: (array) les différentes balises du fichier triées
   */
  private  function classificationDom($fichierHTML) {
    $htmlPage = file_get_contents ($fichierHTML);
    $crawlerPage = new Crawler($htmlPage);

    $allBalises = array();

    foreach ($this->balisesARecuperer as $uneBaliseARecuperer) {
      foreach($crawlerPage->filter($uneBaliseARecuperer) as $key=>$uneBalise) {
        $textHTML='';
        foreach ($uneBalise->childNodes as $child) {
          $textHTML .= $uneBalise->ownerDocument->saveHTML($child);
        }

        $allBalises[$uneBaliseARecuperer][$key]['html'] = htmlentities($textHTML);   
        $allBalises[$uneBaliseARecuperer][$key]['text'] = strip_tags($textHTML);

        foreach($uneBalise->attributes as $unAttribut) {
          $allBalises[$uneBaliseARecuperer][$key][$unAttribut->name] = $unAttribut->textContent;
        }
      }
    }

    return $allBalises;
  }
}