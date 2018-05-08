<?php

use Slim\Http\Request;
use Slim\Http\Response;
/*  $this->logger->addInfo('Something interesting happened'); */

// Routes

$app->get('/users/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $currentuser = $request->getAttribute('bruger');
    $jwtuserid = $currentuser['user_id'];

    if (!(intval($jwtuserid)==$id || $currentuser['admin'])) {
        return $response
            ->withStatus(401)
            ->write("Could not authorize user");
    }

    $sql = " SELECT user_id, login, name, numerator, denominator, active, admin FROM users WHERE user_id=".$id.";";
    $result = mysqli_query($this->link, $sql);
    if(!$result){
        return $response
            ->withStatus(500)
            ->write("could not get user");
    } else {
        for ($i=0;$i<mysqli_num_rows($result);$i++) {
            echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
        } 
    }
})->add($authonly);

//logic for PUT endpoints 
$app->put('/users/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $body = $request->getParsedBody();

    $currentuser = $request->getAttribute('bruger');
    $jwtuserid = $currentuser['user_id'];

    if (!(intval($jwtuserid)==$id || $currentuser['admin'])) {
        return $response
            ->withStatus(401)
            ->write("Could not authorize user");
    }
    
    $sets=array();

    foreach($body as $key => $value){
        if(is_string($value)){
            $s=$key."=\"".$value."\"";
        } else {
            $s=$key."=".$value;
        }
        array_push($sets,$s);
    }

    
    $sql = 'UPDATE users SET '.join(", ",$sets)." WHERE user_id=".$id.";";
    $sql2= "SELECT user_id, login, name, numerator, denominator, active, admin FROM users WHERE user_id=".$id.";";
    $updateresult = mysqli_query($this->link, $sql);
    if(!$updateresult){
        return $response
            ->withStatus(500)
            ->write("could not update user");
    }

    $result = mysqli_query($this->link, $sql2);
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }   
    //$response->getBody()->write("Fuck, $name");
    

    //return $response;
})->add($authonly);


//logic for POST endpoints
$app->post('/users', function (Request $request, Response $response) {
    $body = $request->getParsedBody();

    $keys=array();
    $values=array();

    foreach($body as $key => $value){
        array_push($keys,$key);
        array_push($values,"\"".$value."\"");
    }

    $sql = "INSERT INTO users (".join(", ",$keys).") VALUES (".join(", ",$values).");";
    $updateresult = mysqli_query($this->link, $sql);
    $sql2 = "SELECT user_id, login, name, numerator, denominator, active, admin FROM users ORDER BY user_id DESC LIMIT 0, 1;";

    if(!$updateresult){
        return $response
            ->withStatus(500)
            ->write("could not post user");
    }
    $result = mysqli_query($this->link, $sql2);
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }  

    //return $response;
})->add($authonly)->add($adminonly);


//logic for DELETE endpoints
$app->delete('/users/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM users WHERE user_id = ".$id.";";
    $result = mysqli_query($this->link, $sql);

    if($result){
        return $response
            ->withStatus(204)
            ->write("User with id: ".$id." deleted.");
    } else {
        return $response
            ->withStatus(404)
            ->write("User with id: ".$id." was not found.");
    };
    

    //return $response;
})->add($authonly)->add($adminonly);