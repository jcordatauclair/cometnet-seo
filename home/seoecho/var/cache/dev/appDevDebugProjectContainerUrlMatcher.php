<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appDevDebugProjectContainerUrlMatcher.
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appDevDebugProjectContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        if (0 === strpos($pathinfo, '/css/8adc786')) {
            // _assetic_8adc786
            if ($pathinfo === '/css/8adc786.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '8adc786',  'pos' => NULL,  '_format' => 'css',  '_route' => '_assetic_8adc786',);
            }

            if (0 === strpos($pathinfo, '/css/8adc786_part_1_')) {
                // _assetic_8adc786_0
                if ($pathinfo === '/css/8adc786_part_1_default_1.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '8adc786',  'pos' => 0,  '_format' => 'css',  '_route' => '_assetic_8adc786_0',);
                }

                // _assetic_8adc786_1
                if ($pathinfo === '/css/8adc786_part_1_jquery.circliful_2.css') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '8adc786',  'pos' => 1,  '_format' => 'css',  '_route' => '_assetic_8adc786_1',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/js/d8d8b6f')) {
            // _assetic_d8d8b6f
            if ($pathinfo === '/js/d8d8b6f.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'd8d8b6f',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_d8d8b6f',);
            }

            if (0 === strpos($pathinfo, '/js/d8d8b6f_part_1_')) {
                if (0 === strpos($pathinfo, '/js/d8d8b6f_part_1_default_')) {
                    // _assetic_d8d8b6f_0
                    if ($pathinfo === '/js/d8d8b6f_part_1_default_1.js') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => 'd8d8b6f',  'pos' => 0,  '_format' => 'js',  '_route' => '_assetic_d8d8b6f_0',);
                    }

                    // _assetic_d8d8b6f_1
                    if ($pathinfo === '/js/d8d8b6f_part_1_default_2.js') {
                        return array (  '_controller' => 'assetic.controller:render',  'name' => 'd8d8b6f',  'pos' => 1,  '_format' => 'js',  '_route' => '_assetic_d8d8b6f_1',);
                    }

                }

                // _assetic_d8d8b6f_2
                if ($pathinfo === '/js/d8d8b6f_part_1_jquery.circliful.min_3.js') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => 'd8d8b6f',  'pos' => 2,  '_format' => 'js',  '_route' => '_assetic_d8d8b6f_2',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/images')) {
            if (0 === strpos($pathinfo, '/images/291409c')) {
                // _assetic_291409c
                if ($pathinfo === '/images/291409c.gif') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '291409c',  'pos' => NULL,  '_format' => 'gif',  '_route' => '_assetic_291409c',);
                }

                // _assetic_291409c_0
                if ($pathinfo === '/images/291409c_loaderGoogle_1.gif') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '291409c',  'pos' => 0,  '_format' => 'gif',  '_route' => '_assetic_291409c_0',);
                }

            }

            if (0 === strpos($pathinfo, '/images/44df5c8')) {
                // _assetic_44df5c8
                if ($pathinfo === '/images/44df5c8.gif') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '44df5c8',  'pos' => NULL,  '_format' => 'gif',  '_route' => '_assetic_44df5c8',);
                }

                // _assetic_44df5c8_0
                if ($pathinfo === '/images/44df5c8_loaderApprentissageEcho_1.gif') {
                    return array (  '_controller' => 'assetic.controller:render',  'name' => '44df5c8',  'pos' => 0,  '_format' => 'gif',  '_route' => '_assetic_44df5c8_0',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/css/d7cfe60')) {
            // _assetic_d7cfe60
            if ($pathinfo === '/css/d7cfe60.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'd7cfe60',  'pos' => NULL,  '_format' => 'css',  '_route' => '_assetic_d7cfe60',);
            }

            // _assetic_d7cfe60_0
            if ($pathinfo === '/css/d7cfe60_accueil_1.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'd7cfe60',  'pos' => 0,  '_format' => 'css',  '_route' => '_assetic_d7cfe60_0',);
            }

        }

        if (0 === strpos($pathinfo, '/images/7a67317')) {
            // _assetic_7a67317
            if ($pathinfo === '/images/7a67317.png') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '7a67317',  'pos' => NULL,  '_format' => 'png',  '_route' => '_assetic_7a67317',);
            }

            // _assetic_7a67317_0
            if ($pathinfo === '/images/7a67317_logo_1.png') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '7a67317',  'pos' => 0,  '_format' => 'png',  '_route' => '_assetic_7a67317_0',);
            }

        }

        if (0 === strpos($pathinfo, '/css/2039c79')) {
            // _assetic_2039c79
            if ($pathinfo === '/css/2039c79.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '2039c79',  'pos' => NULL,  '_format' => 'css',  '_route' => '_assetic_2039c79',);
            }

            // _assetic_2039c79_0
            if ($pathinfo === '/css/2039c79_main_1.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '2039c79',  'pos' => 0,  '_format' => 'css',  '_route' => '_assetic_2039c79_0',);
            }

        }

        if (0 === strpos($pathinfo, '/js/0384b19')) {
            // _assetic_0384b19
            if ($pathinfo === '/js/0384b19.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '0384b19',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_0384b19',);
            }

            // _assetic_0384b19_0
            if ($pathinfo === '/js/0384b19_main_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '0384b19',  'pos' => 0,  '_format' => 'js',  '_route' => '_assetic_0384b19_0',);
            }

        }

        if (0 === strpos($pathinfo, '/_')) {
            // _wdt
            if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_wdt')), array (  '_controller' => 'web_profiler.controller.profiler:toolbarAction',));
            }

            if (0 === strpos($pathinfo, '/_profiler')) {
                // _profiler_home
                if (rtrim($pathinfo, '/') === '/_profiler') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_profiler_home');
                    }

                    return array (  '_controller' => 'web_profiler.controller.profiler:homeAction',  '_route' => '_profiler_home',);
                }

                if (0 === strpos($pathinfo, '/_profiler/search')) {
                    // _profiler_search
                    if ($pathinfo === '/_profiler/search') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchAction',  '_route' => '_profiler_search',);
                    }

                    // _profiler_search_bar
                    if ($pathinfo === '/_profiler/search_bar') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchBarAction',  '_route' => '_profiler_search_bar',);
                    }

                }

                // _profiler_info
                if (0 === strpos($pathinfo, '/_profiler/info') && preg_match('#^/_profiler/info/(?P<about>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_info')), array (  '_controller' => 'web_profiler.controller.profiler:infoAction',));
                }

                // _profiler_phpinfo
                if ($pathinfo === '/_profiler/phpinfo') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:phpinfoAction',  '_route' => '_profiler_phpinfo',);
                }

                // _profiler_search_results
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/search/results$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_search_results')), array (  '_controller' => 'web_profiler.controller.profiler:searchResultsAction',));
                }

                // _profiler
                if (preg_match('#^/_profiler/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler')), array (  '_controller' => 'web_profiler.controller.profiler:panelAction',));
                }

                // _profiler_router
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/router$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_router')), array (  '_controller' => 'web_profiler.controller.router:panelAction',));
                }

                // _profiler_exception
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception')), array (  '_controller' => 'web_profiler.controller.exception:showAction',));
                }

                // _profiler_exception_css
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception\\.css$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception_css')), array (  '_controller' => 'web_profiler.controller.exception:cssAction',));
                }

            }

            // _twig_error_test
            if (0 === strpos($pathinfo, '/_error') && preg_match('#^/_error/(?P<code>\\d+)(?:\\.(?P<_format>[^/]++))?$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_twig_error_test')), array (  '_controller' => 'twig.controller.preview_error:previewErrorPageAction',  '_format' => 'html',));
            }

        }

        // seoecho_homepage
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'seoecho_homepage');
            }

            return array (  '_controller' => 'SEOecho\\echoSEOBundle\\Controller\\DefaultController::indexAction',  '_route' => 'seoecho_homepage',);
        }

        // seoecho_validFormMcUrl
        if ($pathinfo === '/validFormMcUrl') {
            return array (  '_controller' => 'SEOecho\\echoSEOBundle\\Controller\\DefaultController::validFormMcUrlAction',  '_route' => 'seoecho_validFormMcUrl',);
        }

        // seoecho_apprentissageEcho
        if ($pathinfo === '/apprentissageEcho') {
            return array (  '_controller' => 'SEOecho\\echoSEOBundle\\Controller\\EchoController::apprentissageAction',  '_route' => 'seoecho_apprentissageEcho',);
        }

        // crawl_google_python_homepage
        if ($pathinfo === '/crawlGooglePython') {
            return array (  '_controller' => 'SEOecho\\CrawlGooglePythonBundle\\Controller\\DefaultController::indexAction',  '_route' => 'crawl_google_python_homepage',);
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
