<?php
/*
 *Ce service va permettre d'associer toutes les fonctions liées à Echov dont le calcul de performance du serveur echo
 *
 */
namespace SEOecho\echoSEOBundle\Services;

//use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class Echov
{
    /* private $container;

      public function __construct(Container $container)
     {
         $this->container = $container;
     }

     /**
      * fonction getTxPerf permet de connaitre le niveau de performance de echo suite à l'apprentissage effectué sur Google pour un ensemble de mots clés
      * donnés
      * @param tableau $tabResGoogle tous les resultats google passés sous forme de tableau
      * @return : int $txPerf qui indique le niveau de performance acquis
      */
    public function getTxPerfs($tabResGoogle)
    {
        $atraiter="";
        /* Connexion au serveur Echov qui sur SEO-echo écoute sur le port 2040 */
        $client = stream_socket_client("tcp://localhost:2040", $errno, $errorMessage);
        if ($client === false) {
            throw new UnexpectedValueException("Failed to connect: $errorMessage");
        }

        //$trans = array("Ã©" => "e", "Ã¨" => "e", "Ã´" => "o", "Ã«"=>"e", "Ãª"=>"e", "Ã "=>"a", "Ã¢" => "a", "Ã¹" => "u", "Ã¼" => "u", "Ã»"=>"u", "Ã´"=>"o");
        //$trans2 = array("-" => " ", "," => " ", "," => " ", "."=>" ", "Â»"=>" ", "Â«"=>" ", "â" => " ", "â" => " ", "\"" => " ", "\\"=>" ", "("=>" ", ")" => " ", ":" => " ", ";" => " " );
        //dump($tabResGoogle);exit;
        foreach ($tabResGoogle as $unres) {
            if ($unres['chainedespredicteurs'] != '') {
                $atraiter = $atraiter."$unres[ID] $unres[pertinence]";
                $predicteurs = strtolower($unres['chainedespredicteurs']);
                $atraiter = $atraiter."$predicteurs;";
            }

            // DEBUG :
            // dump($atraiter);
            // exit();

            // fwrite($client, $atraiter);
            // fflush($client);
        }

        $atraiter = $atraiter."mfin\n";

        // DEBUG :
        // dump($atraiter);
        // exit();

        fwrite($client, $atraiter);
        fflush($client);

        $probas = stream_get_contents($client);
        fclose($client);

        // DEBUG :
        // dump($probas);
        // exit();

        return $probas;
    }
}
