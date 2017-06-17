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

    // Setup a base URI so that all generated URIs include the "/" directory
    $di['url'] = function() {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    };

    // Setup the tag helpers
    $di['tag'] = function() {
        return new Tag();
    };

    // Handle the request
    $application = new Application($di);

    $fullCalendarCSSCollection = $application->assets->collection('fullcalendarCSS');
    $fullCalendarCSSCollection->addCss('node_modules/fullcalendar/dist/fullcalendar.min.css');

    $fullCalendarJSCollection = $application->assets->collection('fullcalendarJS');
    $fullCalendarJSCollection->addJs('node_modules/jquery/dist/jquery.min.js');
    $fullCalendarJSCollection->addJs('node_modules/moment/min/moment.min.js');
    $fullCalendarJSCollection->addJs('node_modules/fullcalendar/dist/fullcalendar.min.js');

    echo $application->handle()->getContent();

} catch (Exception $e) {
     echo "Exception: ", $e->getMessage();
}
