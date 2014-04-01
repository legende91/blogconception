<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog_member' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/member',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'edit',
                    ),
                ),
            ),
            'blog_addMember' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/addMember',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'addMember',
                    ),
                ),
            ),
            'blog_editMember' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/editMember[/:id]',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'editMember',
                    ),
                ),
            ),
            'blog_profilMember' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/profilMember',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'profilMember',
                    ),
                ),
            ),
            'blog_deleteMember' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/deleteMember[/:id]',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'deleteMember',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'authenticate',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'process' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:action]',
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
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'logout',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'process' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:action]',
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
            'success' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/success',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller' => 'Success',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:action]',
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
            'tutorial' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/tutorial',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller' => 'Tutorial',
                        'action' => 'index',
                    ),
                ),
            ),
            'tutorial_add' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/tutorial_add',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller' => 'Tutorial',
                        'action' => 'add',
                    ),
                ),
            ),
            'tutorial_edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/tutorial_edit[/:id]',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Tutorial',
                        'action' => 'edit',
                    ),
                ),
            ),
            'tutorial_delete' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/tutorial_delete[/:id]',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Tutorial',
                        'action' => 'delete',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'blog' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/blog',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
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
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'Zend\Authentication\AuthenticationService' => 'AuthService',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Index' => 'Blog\Controller\IndexController',
            'Blog\Controller\Success' => 'Blog\Controller\SuccessController',
            'Blog\Controller\Tutorial' => 'Blog\Controller\TutorialController',
            'AuthService' => 'Zend\Authentication\AuthenticationService',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
