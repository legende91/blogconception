<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog;

use Blog\Model\Member;
use Blog\Model\MemberTable;
use Blog\Model\Tutorial;
use Blog\Model\TutorialTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class Module {

    public function getServiceConfig() {
       
        return array(
            'factories' => array(
                'Blog\Model\MemberTable' => function($sm) {
            $tableGateway = $sm->get('MemberTableGateway');
            $table = new MemberTable($tableGateway);
            return $table;
        },
                'MemberTableGateway' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Member());
            return new TableGateway('member', $dbAdapter, null, $resultSetPrototype);
        },
                'Blog\Model\TutorialTable' => function($sm) {
            $tableGateway = $sm->get('Tutorial');
            $table = new TutorialTable($tableGateway);
            return $table;
        },
                'Tutorial' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Tutorial());
            return new TableGateway('tutorial', $dbAdapter, null, $resultSetPrototype);
        },
   
        'Blog\Model\MyAuthStorage' => function($sm){
            return new \Blog\Model\MyAuthStorage('blog_member_connect');  
        },
         
        'AuthService' => function($sm) {
                    //My assumption, you've alredy set dbAdapter
                    //and has member table with columns : name and password
                    
            $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 
                                              'member','name','password');
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Blog\Model\MyAuthStorage'));
            return $authService;
        },
     )  
   );    
}

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                   __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

}
