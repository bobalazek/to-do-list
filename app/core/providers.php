<?php

/***** Config *****/
$app->register(
    new Igorw\Silex\ConfigServiceProvider(
        APP_DIR.'/config.php'
    )
);

/***** Database *****/
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $app['databaseOptions'],
));
