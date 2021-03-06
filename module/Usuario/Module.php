<?php

namespace Usuario;

use Usuario\Model\Usuario;
use Usuario\Model\UsuarioTable;
use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'boforeDispatch'
                ), 100);
    }

    function boforeDispatch(MvcEvent $event) {

        $request = $event->getRequest();
        $response = $event->getResponse();
        $target = $event->getTarget();

        $whiteList = array(
            'Application\Controller\Login-index',
            'Application\Controller\Login-logout',
                //    'Application\Controller\Index-index'
        );

        $requestUri = $request->getRequestUri();
        $controller = $event->getRouteMatch()->getParam('controller');
        $action = $event->getRouteMatch()->getParam('action');

        $requestedResourse = $controller . "-" . $action;

        $auth = $event->getApplication()->getServiceManager()->get('AuthService');

        if ($auth->hasIdentity()) {
            if ($requestedResourse == 'Application\Controller\Login-index' || in_array($requestedResourse, $whiteList)) {
                $url = '/';
                $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
            }
        } else {

            if ($requestedResourse != 'Application\Controller\Login-index' && !in_array($requestedResourse, $whiteList)) {
                $url = '/login';
                $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
            }
            $response->sendHeaders();
        }
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'AuthService' => function ($serviceManager) {
                    $adapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $dbAuthAdapter = new DbAuthAdapter($adapter, 'users', 'username', 'password', 'MD5(?) AND status = 1');

                    $auth = new AuthenticationService();
                    $auth->setAdapter($dbAuthAdapter);
                    return $auth;
                },
                'Usuario\Model\UsuarioTable' => function($sm) {
                    $tableGateway = $sm->get('UsuarioTableGateway');
                    $table = new UsuarioTable($tableGateway);
                    return $table;
                },
                'UsuarioTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Usuario());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

}
