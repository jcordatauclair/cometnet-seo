<?php
/*
 * Ce service va permettre d'associer toutes les fonctions au serveur Echobt dont le calcul des meilleurs termes predicteurs
 */
namespace SEOecho\echoSEOBundle\Services;

class Echobt
{
    /* getmeilleurpredicteurs() : fonction permettant de récupérer les termes predicteurs avec un classement
     * @param : un tableau des resultats google
     * @return : Une classification des termes predicteurs
     */
    public function getmeilleurpredicteurs($tabResGoogle)
    {
        // DEBUG :
        // dump($tabResGoogle);
        // exit();

        $atraiter = "";
        /* Connexion au serveur Echobt qui sur SEO-echo ecoute sur le port 2022 */
        $client = stream_socket_client("tcp://localhost:2022", $errno, $errorMessage);
        if ($client === false) {
            throw new UnexpectedValueException("Failed to connect: $errorMessage");
        }
        stream_set_timeout($client, 300);

        foreach ($tabResGoogle as $unres) {
            if ($unres['chainedespredicteurs'] != '') {
                $atraiter = $atraiter."$unres[ID] $unres[pertinence]";
                $predicteurs = strtolower($unres['chainedespredicteurs']);
                $atraiter = $atraiter."$predicteurs;";

                // DEBUG :
                // dump($atraiter);
                // exit();

                // fwrite($client, $atraiter);
                // fflush($client);
            }
        }

        $atraiter = $atraiter."mfin\n";

        // echo $atraiter;
        // exit();

        // DEBUG :
        // dump($atraiter);
        // exit();

        fwrite($client, $atraiter);
        fflush($client);

        $predicteurs = stream_get_contents($client);
        fclose($client);

        // DEBUG :
        // dump($predicteurs);
        // exit();

        return $predicteurs;
    }
}
