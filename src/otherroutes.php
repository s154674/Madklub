<?php

use Slim\Http\Request;
use Slim\Http\Response;
/*  $this->logger->addInfo('Something interesting happened'); */

// Routes
//logic for GET endpoints (e.g. frontpage)
$app->get('/{path}', function (Request $request, Response $response) {
    $path = $request->getAttribute('path');
    if ($path == ( 'frontpage' || 'status' || 'futuredates' || 'pastdates' || 'nextday' || 'users' )) {
        echo '[';
        $sql = 'SELECT * FROM '.$path.';';
        $result = mysqli_query($this->link, $sql);
        for ($i=0;$i<mysqli_num_rows($result);$i++) {
            echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
        }
        echo']';
    } else {
        return $response
            ->withStatus(500)
            ->write("could not update user");
    }
});

