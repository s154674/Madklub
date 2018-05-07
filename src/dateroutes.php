<?php

use Slim\Http\Request;
use Slim\Http\Response;
/*  $this->logger->addInfo('Something interesting happened'); */


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
        array_push($sets, $s);
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
$app->post('/dates', function (Request $request, Response $response) {
    $body = $request->getParsedBody();

    $keys=array();
    $values=array();

    foreach($body as $key => $value){
        array_push($keys,$key);
        array_push($values,"\"".$value."\"");
    }

    $sql = "INSERT INTO dates (".join(", ",$keys).") VALUES (".join(", ",$values).");";
    $updateresult = mysqli_query($this->link, $sql);
    $sql2 = "SELECT * FROM dates ORDER BY date_id DESC LIMIT 0, 1;";

    if(!$updateresult){
        return $response
            ->withStatus(500)
            ->write("could not post date");
    }
    $result = mysqli_query($this->link, $sql2);
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }  

    //return $response;
});


//logic for DELETE endpoints
$app->delete('/dates/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM dates WHERE date_id = ".$id.";";
    $result = mysqli_query($this->link, $sql);

    if($result){
        return $response
            ->withStatus(204)
            ->write("Date with id: ".$id." deleted.");
    } else {
        return $response
            ->withStatus(404)
            ->write("Date with id: ".$id." was not found.");
    };
    

    //return $response;
});
