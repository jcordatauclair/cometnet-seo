<?php
namespace SEOecho\echoSEOBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class EchoController extends Controller
{
  public function refreshAction()
  {
    echo 'ça marche :)';
    return new Response();
  }
}
