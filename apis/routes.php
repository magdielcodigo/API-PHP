<?php
// Ruta de la aplicacion
define('rp', '/apps/apis/');

// Invoación de archivo principal
require_once __DIR__.'/router.php';

// URLs de la api
get('/', 'views/index.php');

post('/create', 'components/createController.php');

post('/read', 'components/readController.php');

post('/update', 'components/updateController.php');

post('/delete', 'components/deleteController.php');

any('/404','views/404.php');
