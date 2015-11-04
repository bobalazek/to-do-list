<?php

/***** Index *****/
$app->get('/', function () use ($app) {
    return file_get_contents(APP_DIR.'/templates/index.html');
})
->bind('index');

/********** Items **********/
/***** Get (all) *****/
$app->get('/items', function () use ($app) {
    return $app->json(array(
        'success' => true,
        'items' => $app['db']->fetchAll('SELECT * FROM items'),
    ));
});

/***** Create (new) *****/
$app->post('/items', function () use ($app) {
    $data = $app['request']->request->all();

    try {
        $app['db']->insert('items', array(
            'text' => $data['text'],
            '`order`' => 999,
        ));
    } catch(\Exception $e) {
        return $app->json(array(
            'error' => array(
                'message' => $e->getMessage(),
            ),
        ), 500);
    }

    return $app->json(array(
        'success' => true,
    ));
});

/***** Update (existing) *****/
$app->put('/items/{id}', function ($id) use ($app) {
    $data = $app['request']->request->all();

    try {
        $app['db']->update('items', $data, array(
            'id' => $id,
        ));
    } catch(\Exception $e) {
        return $app->json(array(
            'error' => array(
                'message' => $e->getMessage(),
            ),
        ), 500);
    }

    return $app->json(array(
        'success' => true,
    ));
});

/***** Remove (delete) *****/
$app->delete('/items/{id}', function ($id) use ($app) {
    try {
        $app['db']->delete('items', array(
            'id' => $id,
        ));
    } catch(\Exception $e) {
        return $app->json(array(
            'error' => array(
                'message' => $e->getMessage(),
            ),
        ), 500);
    }

    return $app->json(array(
        'success' => true,
    ));
});

/********** Database **********/
/***** Install *****/
$app->get('/database/install', function () use ($app) {
    $schema = $app['db']->getSchemaManager();

    if (! $schema->tablesExist('items')) {
        $itemsTable = new Doctrine\DBAL\Schema\Table('items');

        // ID
        $itemsTable->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
        $itemsTable->setPrimaryKey(array('id'));

        // Text
        $itemsTable->addColumn('text', 'text');

        // Order
        $itemsTable->addColumn('order', 'integer');

        $schema->createTable($itemsTable);
    }

    return $app->json(array(
        'success' => true,
        'message' => 'Database tables installed!',
    ));
});

/***** Clear *****/
$app->get('/database/clear', function () use ($app) {
    $app['db']->query('TRUNCATE TABLE items');

    return $app->json(array(
        'success' => true,
        'message' => 'Database tables cleared!',
    ));
});
