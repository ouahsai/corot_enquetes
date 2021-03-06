<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(

            /**
             * Les Routes inquisitor
             * ---------------------
             * home             = ACCUEIL - public/index                 : / ()
             * repondreEnquete  = RÉPONDANT - public/repondre            : /enquete_## (id enquete)
             * listeEnquetes    = MEMBRE - membre/index                  : /liste-enquetes (no param : id membre géré par zfcUser)
             * creerEnquete     = CRÉER ENQUÊTE - membre/creer           : /creer-enquete ()
             * voirEnquete      = VOIR ENQUÊTE - membre/apercu           : /apercu-enquete_## (id enquete)
             * modifierEnquete  = MODIFIER ENQUÊTE - membre/modifier     : /editer-enquete_## (id enquete)
             * supprimerEnquete = SUPPRIMER ENQUÊTE - membre/supprimer   : /supprimer-enquete_## (id enquete)
             * resultatsEnquete = RÉSULTATS - membre/consulter           : /resultats-enquete_## (id enquete)
             * effacerDonnees   = EFFACER DONNÉES - membre/effacerData   : /effacer-donnees_## (id enquete)
             */
            
            /**
             * note : toutes les url commencent par l'adresse de base du site
             *          http://localhost/monsite
             *    ou    http://www.inquisitor.local
             *    ou    http://www.inquisitor.com     ---> par exemple une fois déplyé sur le web
             */

            /* home : route par défaut 
             * url : /
             */
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Public',
                        'action' => 'index',
                    ),
                ),
            ),

            /* repondreEnquete : vers les enquêtes déstinées aux répondants 
             * url : /enquete_##  ---> url simple qui s'adresse à un public large (lors de la diffusion)
             * param : id de l'enquete
             */
            'repondreEnquete' => array(
                'type' => 'Regex',
                'options' => array(
                    //extracteur
                    'regex' => '/enquete_(?<id>[1-9][0-9]*)', 
                    'defaults' => array(
                        'controller' => 'Application\Controller\Public',
                        'action' => 'repondre',
                    ),
                    //generateur
                    'spec' => '/enquete_%id%',
                ),
            ),

            /* listeEnquetes : vers la page 'membre', la liste des enquêtes du membre 
             * url : /liste-enquetes
             * no param : id du membre géré directement par zfcuser
             */
            'listeEnquetes' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/liste-enquetes', 
                    'defaults' => array(
                        'controller' => 'Application\Controller\Membre',
                        'action' => 'index',
                    ),
                ),
            ),

            /* creerEnquete : vers la page de création d'une enquête
             * url : /creer-enquete
             * param : rien c'est une page statique
             */
            'creerEnquete' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/creer-enquete', 
                    'defaults' => array(
                        'controller' => 'Application\Controller\Membre',
                        'action' => 'creer',
                    ),
                ),
            ),

            /* modifierEnquete : vers la page de modification d'une enquête
             * url : /editer-enquete_##.html
             */
            'modifierEnquete' => array(
                'type' => 'Regex',
                'options' => array(
                    'regex' => '/editer-enquete_(?<id>[1-9][0-9]*)', 
                    'defaults' => array(
                        'controller' => 'Application\Controller\Membre',
                        'action' => 'modifier',
                    ),
                    'spec' => '/editer-enquete_%id%',
                ),
            ),

            /* supprimerEnquete : vers la page de suppression d'une enquête (avec confirmation)
             * url : /supprimer-enquete_##
             * param : id de l'enquête
             */
            'supprimerEnquete' => array(
                'type' => 'Regex',
                'options' => array(
                    'regex' => '/supprimer-enquete_(?<id>[1-9][0-9]*)',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Membre',
                        'action' => 'supprimer',
                    ),
                    'spec' => '/supprimer-enquete_%id%',
                ),
            ),

            /* voirEnquete : vers l'aperçu d'une enquête
             * url : /apercu-enquete_##
             * param : id de l'enquête
             */
            'voirEnquete' => array(
                'type' => 'Regex',
                'options' => array(
                    'regex' => '/apercu-enquete_(?<id>[1-9][0-9]*)',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Membre',
                        'action' => 'apercu',
                    ),
                    'spec' => '/apercu-enquete_%id%',
                ),
            ),

            /* resultatsEnquete : vers la page des résultats d'une enquête
             * url : /resultats-enquete_##
             * param : id de l'enquête
             */
            'resultatsEnquete' => array(
                'type' => 'Regex',
                'options' => array(
                    //analyse de mon url
                    'regex' => '/resultats-enquete_(?<id>[1-9][0-9]*)', 
                    'defaults' => array(
                        'controller' => 'Application\Controller\Membre',
                        'action' => 'consulter',
                    ),
                    'spec' => '/resultats-enquete_%id%',
                ),
            ), 
            

            /* effacerDonnees : vers la page des résultats d'une enquête
             * url : /effacer-donnees_##
             * param : id de l'enquête
             */
            'effacerDonnees' => array(
                'type' => 'Regex',
                'options' => array(
                    //analyse de mon url
                    'regex' => '/effacer-donnees_(?<id>[1-9][0-9]*)', 
                    'defaults' => array(
                        'controller' => 'Application\Controller\Membre',
                        'action' => 'effacer',
                    ),
                    'spec' => '/effacer-donnees_%id%',
                ),
            ), 
            
            /**
             * Route  zfcuser pour la connexion
             */
            'zfcuser' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/membre',
                    'defaults' => array(
                        'controller' => '\Application\Controller\Membre',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            
            
            
            
            
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'fr_FR',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language/form',
                'pattern' => '%s.mo',
            ),
            array(
                'type' => 'phparray',
                'base_dir' => __DIR__. '/../language/form_errors',
                'pattern' => '%s.php'
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Public' => 'Application\Controller\PublicController',
            'Application\Controller\Membre' => 'Application\Controller\MembreController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
