<?php

use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;



// Application middleware


// e.g: $app->add(new \Slim\Csrf\Guard);
$authonly = function ($request, $response, $next) {
    $jwtkey = "123456";
    $authheader = $request->getHeaderLine('Authorization');
    $token = substr($authheader, 7);

    // valider token
    try {
        $decoded = JWT::decode($token, $jwtkey, array('HS256'));
        // TODO check at den ikke er udløbet (kast exception hvis den er)

        // Find bruger i DB
        $user_id = $decoded->bruger->id;
        $sql = 'SELECT user_id, name, login, active, admin FROM users WHERE user_id='.$user_id.';';
        $result = mysqli_query($this->link, $sql);
        $user = mysqli_fetch_assoc($result);

        // Lav en ny token med nye opdaterede informationer om brugeren:
        $jwt = make_token($user['user_id'], $user['name'], $user['login'], $user['active'], $user['admin']);
        $response = $response->withHeader('Authorization', 'Bearer '.$jwt);

        $request = $request->withAttribute('bruger', $user);
        $response = $next($request, $response);

    } catch(ExpiredException $e){
        $authenticated = false;
        $response
            ->withStatus(401)
            ->write("session expired");

    } catch(Exception $e){
        $authenticated = false;
        $response
            ->withStatus(401)
            ->write("could not authorize user");
    }

    return $response;

};

$adminonly = function ($request, $response, $next) {
    $bruger = $request->getAttribute('bruger');

    if($bruger['admin']){
        $response = $next($request, $response);
    } else {
        $response
            ->withStatus(401)
            ->write("could not authorize user, admin status is required");
    }

    return $response;

};