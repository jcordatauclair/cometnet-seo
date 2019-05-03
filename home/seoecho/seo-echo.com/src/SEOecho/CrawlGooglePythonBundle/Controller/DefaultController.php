<?php
namespace SEOecho\CrawlGooglePythonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

  // écriture des paramètres puis appel du script Python qui va crawler les pages Google
    public function indexAction(Request $request)

  /******************************** BEFORE ************************************/
  /*
  {
    $req = $request->get('motsCles');
    // je reformate mon mot clé
    $reqFormate = str_replace("+", "%20", urlencode($req));
    // je créé une chaine aléatoire pour nom du dossier qui va contenir les pages crawlees
    $chaine = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ0123456789';
    $nomDossierListeUrl = substr(str_shuffle($chaine), 0, 25);
     //echo '/usr/bin/python '.__DIR__.'/../bin/crawlResultatsGoogle.py '.$nomDossierListeUrl.' '.$reqFormate;
    // appel de l'execution de python ET du script avec arguments
    $command = escapeshellcmd('/usr/bin/python '.__DIR__.'/../bin/crawlResultatsGoogle.py '.$nomDossierListeUrl.' '.$reqFormate);
    $output = shell_exec($command);
    //echo '/usr/bin/python '.__DIR__.'/../bin/crawlResultatsGoogle.py ';
   if (!$output)
      {throw new \Exception('Erreur!');}
    else
      {
      return new Response($nomDossierListeUrl);}

    //return new Response('m3gQa1xS96hLHjCzbEMJplXi2');
  }
  */
  /******************************** BEFORE ************************************/

  /********************************* AFTER ************************************/
    {
        $req=$_REQUEST['motsCles'];
        $url=$_REQUEST['url'];

        $paramFile = '/home/seoecho/src/SEOecho/CrawlGooglePythonBundle/bin/param/parameters.txt';
        $handle = fopen($paramFile, 'w') or die('Cannot open file:  '.$paramFile);

        // url de l'utilisateur
        fwrite($handle, $url."\n");

        // nombre de résultats de la recherche
        $nResults = 5;
        fwrite($handle, $nResults."\n");

        // degré de pertinence
        $efficiency = 3;
        fwrite($handle, $efficiency."\n");

        // mots-clés
        $listeMotsCles = explode(" ", $req);
        foreach ($listeMotsCles as $unMotCle) {
            fwrite($handle, $unMotCle."\n");
        }

        //fwrite($handle, $request);
        fclose($handle);

        chdir(__DIR__.'/../bin/');
        exec('/usr/bin/python scrape.py');

        // $command = escapeshellcmd('/usr/bin/python scrape.py');
        // exec($command);
        // $command = escapeshellcmd('/usr/bin/python '.__DIR__.'/../bin/scrape.py');
        // $output = shell_exec($command);
        //dump($command);exit();
        return new Response();
        /* $reqFormate = str_replace("+", "%20", urlencode($req));
         $chaine = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ0123456789';
         $nomDossierListeUrl = substr(str_shuffle($chaine), 0, 25);
         $command = escapeshellcmd('/usr/bin/python '.__DIR__.'/../bin/crawlResultatsGoogle.py '.$nomDossierListeUrl.' '.$reqFormate);


         $output = shell_exec($command);*/
        /*
        if (!$output) {
            throw new \Exception('Erreur!');
        } else {
            return new Response($nomDossierListeUrl);
        }*/

        /*
        if (!$output) {
            throw new \Exception($output);
        } else {
            return new Response('tout va bien');
        }
        */
    }
    /********************************* AFTER ************************************/
}
