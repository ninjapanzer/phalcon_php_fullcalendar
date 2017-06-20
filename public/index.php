<?php

use Phalcon\Loader;
use Phalcon\Tag;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View\Engine\Volt as PhVolt;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

try {

    // Register an autoloader
    $loader = new Loader();
    $loader->registerDirs(
        array(
            '../app/controllers/',
            '../app/models/'
        )
    )->register();

    // Create a DI
    $di = new FactoryDefault();

    // Set the database service
    $di['db'] = function() {
        return new DbAdapter(array(
            "host"     => "localhost",
            "username" => "root",
            "password" => "secret",
            "dbname"   => "tutorial"
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

    $assets = $di['assets'];
    $assets->collection('fullcalendarCSS')
           ->addCss('node_modules/fullcalendar/dist/fullcalendar.min.css');
    $assets->collection('fullcalendarJS')
           ->addJs('node_modules/jquery/dist/jquery.min.js')
           ->addJs('node_modules/moment/min/moment.min.js')
           ->addJs('node_modules/fullcalendar/dist/fullcalendar.min.js')
           ->addJs('js/fullcalendarImpl.js');

    $di['url']->setBaseUri('/');

    // Handle the request
    $application = new Application($di);
    echo $application->handle()->getContent();

} catch (Exception $e) {
     echo "Exception: ", $e->getMessage();
}
