<?php

/***** Index *****/
$app->get('/', function () use ($app) {
    return file_get_contents(APP_DIR.'/templates/index.html');
})
->bind('index');

/***** Database Install *****/
$app->get('/database-install', function () use ($app) {
    $schema = $app['db']->getSchemaManager();

    if (! $schema->tablesExist('items')) {
        $itemsTable = new Doctrine\DBAL\Schema\Table('items');

        // ID
        $itemsTable->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
        $itemsTable->setPrimaryKey(array('id'));

        // Order
        $itemsTable->addColumn('order', 'integer');

        $schema->createTable($itemsTable);
    }

    return new Response(
        'Database Tables successfully installed!'
    );
});

/***** Database Clear *****/
$app->get('/database-clear', function () use ($app) {
    $app['db']->query('TRUNCATE TABLE items;');

    return new Response(
        'Database Tables successfully cleared!'
    );
});
