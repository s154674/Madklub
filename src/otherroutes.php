<?php

use Slim\Http\Request;
use Slim\Http\Response;


/*  $this->logger->addInfo('Something interesting happened'); */



// Routes
//logic for GET endpoints (e.g. frontpage)

$app->get('/futuredates', function (Request $request, Response $response) {
    $currentuser = $request->getAttribute('bruger');
    $currentuserid = $currentuser['user_id'];

    $sql = "SELECT date_id, date, cook, cookjoin.name AS cookname, help, helpjoin.name AS helpname, dish, settled, attendance.dateid IS NOT NULL AS attending , attendance.late, attendance.guest FROM dates JOIN users AS cookjoin ON dates.cook=cookjoin.user_id LEFT JOIN users AS helpjoin ON dates.help=helpjoin.user_id LEFT JOIN attendance ON dates.date_id = attendance.dateid AND ".$currentuserid." = attendance.userid WHERE settled = 0 ORDER BY dates.date ASC;";

    $result = mysqli_query($this->link, $sql);
    echo '[';
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }
    echo']';
})->add($authonly);

$app->get('/washers', function (Request $request, Response $response) {
    echo '[';
    $sql = 'SELECT * FROM washers;';
    $result = mysqli_query($this->link, $sql);
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));       
    }
    echo']';
});

$app->get('/frontpage', function (Request $request, Response $response) {
    $sql = 'SELECT * FROM frontpage;';
    $result = mysqli_query($this->link, $sql);
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));       
    }
});

$app->get('/users', function (Request $request, Response $response) {
    echo '[';
    $sql = 'SELECT user_id, name FROM users;';
    $result = mysqli_query($this->link, $sql);
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));       
    }
    echo ']';
});

$app->get('/{path}', function (Request $request, Response $response) {
    $path = $request->getAttribute('path');
    if ($path == ( 'status' || 'pastdates' || 'nextday' || 'activeusers' )) {
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
            ->write("could not authorize user");
    }
});


// Log ind
$app->post('/login', function (Request $request, Response $response) {
    $body = $request->getParsedBody();
    $brugernavn = $body['login'];
    $password = $body['password'];

    $authenticated = false;

    $wsdl = 'http://javabog.dk:9901/brugeradmin?wsdl';

    $soapclient = new SoapClient($wsdl);

    try {
        $params = array('arg0' => $brugernavn, 'arg1' => $password);
        $response = $soapclient->hentBruger($params);
        $authenticated = true;
    } catch (SoapFault $exception){
        $authenticated = false;
        return $response
            ->withStatus(401)
            ->write("Could not authorize user on javabog.dk");
    }

    $sql = "SELECT * FROM users WHERE login=\"".$brugernavn."\" LIMIT 1;";
    $result = mysqli_query($this->link, $sql);

    if(mysqli_num_rows($result)===0){
        $sql2 = "INSERT INTO users (login) VALUES (\"".$brugernavn."\");";
        mysqli_query($this->link, $sql2);
    }

    $result = mysqli_query($this->link, $sql);
    $row = mysqli_fetch_assoc($result);


    $jwt = make_token($row['user_id'], $row['name'], $brugernavn, $row['active'], $row['admin']);
    echo($jwt);
});



// Skift password
$app->put('/users/{id}/changepassword', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $body = $request->getParsedBody();

    $old = $body['old'];
    $new = $body['new'];
    $newrepeat = $body['new-repeat'];

    if ($new != $newrepeat) {
            return $response
                ->withStatus(403)
                ->write("Not identical passwords");
     }

    $currentuser = $request->getAttribute('bruger');
    $jwtuserid = $currentuser['user_id'];

    if (!(intval($jwtuserid)==$id || $currentuser['admin'])) {
        return $response
            ->withStatus(401)
            ->write("Could not authorize user");
    }


    $sql = "SELECT * FROM users WHERE user_id=".$id.";";
    $result = mysqli_query($this->link, $sql);
    $user = mysqli_fetch_assoc($result);


    $authenticated = false;
    $wsdl = 'http://javabog.dk:9901/brugeradmin?wsdl';
    $soapclient = new SoapClient($wsdl);

    try {
        $params = array('arg0' => $user['login'], 'arg1' => $old, 'arg2' => $new);
        $response = $soapclient->ændrAdgangskode($params);
        $authenticated = true;
    } catch (SoapFault $exception){
        $authenticated = false;
        return $response
            ->withStatus(401)
            ->write("Could not authorize user on javabog.dk");
    }
})->add($authonly);
