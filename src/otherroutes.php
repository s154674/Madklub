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
            ->write("could not authorize user");
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
