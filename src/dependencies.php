<?php
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;

// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};


function make_token($user_id, $name, $login, $active, $admin){
    $jwtkey = "123456";

    $bruger = array("id"=>  $user_id, "navn" => $name, "login" => $login, "active" => $active, "admin" => $admin);

    $token = array(
        "iss" => "http://lmlige.dk",
        "made" => time(),
        "exp" => time()+3600,
        "bruger" => $bruger
    );

    $jwt = JWT::encode($token, $jwtkey);

    return $jwt;
}

// $date = YYYY-mm-dd   eks: 2018-05-07
// $time = tt           eks: 17
function die_if_after($date, $time){
    $datetime = date_create_from_format('Y-m-d:G', $date.":".$time);
    $now = new DateTime();

    if ($now<$datetime){
        $error = 'Time is exceeded';
        throw new Exception($error);
    }
}