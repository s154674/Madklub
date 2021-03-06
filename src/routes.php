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

$app->get('/users/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $sql = 'SELECT * FROM users WHERE users.user_id='.$id.';';
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
});

$app->get('/dates/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $sql = 'SELECT * FROM dates WHERE dates.date_id='.$id.';';
    $result = mysqli_query($this->link, $sql);
    if(!$result){
        return $response
            ->withStatus(500)
            ->write("could not get dates");
    } else {
        for ($i=0;$i<mysqli_num_rows($result);$i++) {
            echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
        }
    }           
});

$app->get('/dates/{id}/attendees', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $sql = 'SELECT * FROM attendance WHERE dateid='.$id.';';
    $result = mysqli_query($this->link, $sql);
    if(!$result){
        return $response
            ->withStatus(500)
            ->write("could not get attendance");
    } else {
        echo '[';
        for ($i=0;$i<mysqli_num_rows($result);$i++) {
            echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
        } 
        echo ']';
    }           
});


//logic for PUT endpoints 
$app->put('/users/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $body = $request->getParsedBody();
    
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
    $sql2=" SELECT user_id, login, name, numerator, denominator, active, admin FROM users WHERE user_id=".$id.";";
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
});

//logic for PUT endpoints 
$app->put('/dates/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $body = $request->getParsedBody();
    
    $sets=array();

    foreach($body as $key => $value){
        if(is_string($value)){
            $s=$key."=\"".$value."\"";
        } else {
            $s=$key."=".$value;
        }
        array_push($sets,$s);
    }

    
    $sql = 'UPDATE dates SET '.join(", ",$sets)." WHERE date_id=".$id.";";
    $sql2=" SELECT date, cook, help, dish FROM dates WHERE date_id=".$id.";";
    $updateresult = mysqli_query($this->link, $sql);
    if(!$updateresult){
        return $response
            ->withStatus(500)
            ->write("could not update date");
    }

    $result = mysqli_query($this->link, $sql2);
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }   
    //$response->getBody()->write("Fuck, $name");
    

    //return $response;
});


//logic for PUT endpoints 
$app->put('/dates/{id}/attendees/{userid}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $userid = $request->getAttribute('userid');
    $body = $request->getParsedBody();
    
    $sets=array();

    foreach($body as $key => $value){
        if(is_string($value)){
            $s=$key."=\"".$value."\"";
        } else {
            $s=$key."=".$value;
        }
        array_push($sets, $s)
    }

    $sql = 'UPDATE attendance SET '.join(", ",$sets)." WHERE userid=".$userid." AND dateid=".$id.";";
    $sql2=" SELECT * FROM attendance WHERE userid=".$userid." AND dateid=".$id.";";
    $updateresult = mysqli_query($this->link, $sql);
    if(!$updateresult){
        return $response
            ->withStatus(500)
            ->write("could not update attendance");
    }

    $result = mysqli_query($this->link, $sql2);
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }   
    //$response->getBody()->write("Fuck, $name");
    

    //return $response;
});



//logic for POST endpoints
$app->post('/users', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Fuck, $name");

    return $response;
});


//logic for DELETE endpoints
$app->delete('/users/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Fuck, $name");

    return $response;
});