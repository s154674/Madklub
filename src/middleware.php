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
        $authenticated = true;
        $response = $next($request, $response);
        $request = 

    } catch(Exception $e){
        $authenticated = false;
        $response
            ->withStatus(401)
            ->write("could not authorize user");
    }

    return $response;

};