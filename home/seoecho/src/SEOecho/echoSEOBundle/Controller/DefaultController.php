<?php
namespace SEOecho\echoSEOBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    // Affichage par défaut de la page d'accueil
    public function indexAction()
    {
        return $this->render('echoSEOBundle:Default:formMcUrl.html.twig');
    }

    /**
     * Fonction chargée de vérifier le formulaire de recherche (mots-clés et URL)
     * @param : (string) $request : des mots-clés
     * @return : aucun
     */
    public function validFormMcUrlAction(Request $request)
    {
        // DEBUG:
        // dump($request);
        // exit();

        $req = strip_tags($request->get('req'));
        $url = $request->get('url');

        $session = $request->getSession();

        // Cas où l'URL entrée n'est pas valide
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $session->getFlashBag()->add('erreur', 'URL non valide');
        }

        if ($session->getFlashBag()->has('erreur')) {
            return $this->render('echoSEOBundle:Default:formMcUrl.html.twig', array('req' => $req, 'url' => $url));
        } else {
            return $this->render('echoSEOBundle:Default:results.html.twig', array('req' => $req, 'url' => $url));
        }
    }
}
