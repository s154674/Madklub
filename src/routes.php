<?php

use Slim\Http\Request;
use Slim\Http\Response;
/*  $this->logger->addInfo('Something interesting happened'); */

// Routes
//logic for GET endpoints (e.g. frontpage)
$app->get('/{path}', function (Request $request, Response $response) {
    $path = $request->getAttribute('path');
    if ($path == ('frontpage' || 'status' || 'futuredates' || 'pastdates' )) {
        echo '[';
        $sql = 'SELECT * FROM '.$path.';';
        $result = mysqli_query($this->link, $sql);
        for ($i=0;$i<mysqli_num_rows($result);$i++) {
            echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
        }
        echo']';
    } else {
        // Maybe do something
    }
});

//logic for POST endpoints
$app->post('/fuck/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Fuck, $name");

    return $response;
});


//logic for PUT endpoints
$app->put('/fuck/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Fuck, $name");

    return $response;
});


//logic for DELETE endpoints
$app->delete('/fuck/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Fuck, $name");

    return $response;
});