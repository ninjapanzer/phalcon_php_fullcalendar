<?php

use Phalcon\Loader;
use Phalcon\Tag;
use Phalcon\Security;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View\Engine\Volt as PhVolt;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

try {

    // Register an autoloader
    $loader = new Loader();
    $loader->registerNamespaces(
        [
           'App\Assets'    => '../app/assets/',
           'App\Auth'      => '../app/library/Auth',
           'App\Forms'     => '../app/forms',
        ]
    );
    $loader->registerDirs(
        array(
            '../app/controllers/',
            '../app/models/'
        )
    );
    $loader->register();

    // Create a DI
    $di = new FactoryDefault();

    $di['security'] = function () {
        $security = new Security();

        // Set the password hashing factor to 12 rounds
        $security->setWorkFactor(12);

        return $security;
    };

    $di['session'] = function () {
        $session = new SessionAdapter();
        if (PHP_SESSION_ACTIVE !== session_status()) {
            $session->start();
        }
        return $session;
    };

    $di->setShared(
        'flashSession',
        function () {
            return new FlashSession();
        }
    );

    $di['dispatcher'] = function () {
        $dispatcher = new Dispatcher();
        return $dispatcher;
    };

    // Set the database service
    $di['db'] = function () {
        return new DbAdapter(array(
            "host"     => "127.0.0.1",
            "username" => "root",
            "password" => "",
            "dbname"   => "fullcalendar_dev_db"
        ));
    };

    // Setting up the view component
    $di['view'] = function() use ($di) {
        $options  = [
            'compiledPath'      => '../storage/cache/volt/',   //path of where your templates will be compiled
            'compiledSeparator' => '_',
            'compiledExtension' => '.php',
            'compileAlways'     => true,
            'stat'              => true,
        ];
        $view = new View();
        $view->setViewsDir('../app/views/');
        $view->registerEngines(
        [
            '.volt' => function ($view) use ($options, $di) {
                $volt  = new PhVolt($view, $di);
                $volt->setOptions($options);
                return $volt;
            },
        ]
    );
        return $view;
    };

    $di['assetPipeline'] = function() use ($di) {
        $options = [
            'jsPath'      => '../app/assets/js/',
            'cssPath'     => '../app/assets/css/',
            'manifestExt' => '.manifest'
        ];
        return new \App\Assets\ApplicationManifest($di['assets'], $options);
    };

    $di['assetPipeline']->loadManifests();

    $di['url']->setBaseUri('/');

    // Handle the request
    $application = new Application($di);
    echo $application->handle()->getContent();

} catch (Exception $e) {
     echo "Exception: ", $e->getMessage();
}
