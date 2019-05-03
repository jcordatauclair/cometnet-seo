<?php
/*
 * Ce service va nous permettre d'associer toutes les fonctions liées à Echo dont
 * connaitre la position supposée de la page dans les résultats Google
 * Classe appelée echos car echo mot rservé
 */
namespace SEOecho\echoSEOBundle\Services;

class Echos
{
    /* getpositiongoogle() : fonction permettant de faire du predictif à partir du serveur echo sur la position supposée de la page dans les résultats google
     * @param : un tableau des resultats google avec une pertinence de 1 ou 2 , un tableau constitué des elements à tester sur la page concernee avec une pertinence de 0 (non jugée),
     * les mots cles sur lesquels se fait la demande
     * @return : une position integer
     */
    public function getpositiongoogle($tabResGoogle, $tabResSiteCible, $listeMotsClesClean)
    {
        // DEBUG :
        // dump($tabResGoogle);
        // exit();

        $tabfinal = array_merge($tabResGoogle, $tabResSiteCible);
        $atraiter = "";
        if (count($tabfinal) != 0) {
            $client = stream_socket_client("tcp://localhost:2017", $errno, $errorMessage);
            if ($client === false) {
                throw new UnexpectedValueException("Failed to connect: $errorMessage");
            } else {
                stream_set_timeout($client, 300);
            }
            foreach ($tabfinal as $unres) {
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

            $strlisteMotsClesClean = implode(' ', $listeMotsClesClean);
            $atraiter = $atraiter."0 1 $strlisteMotsClesClean ;1 2 ;mfin\n";

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
}
