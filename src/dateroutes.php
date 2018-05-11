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
})->add($authonly);

$app->get('/dates/{id}/attendees', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $sql = 'SELECT dateid, userid, late, guest, users.name AS name FROM attendance JOIN users ON attendance.userid = users.user_id WHERE dateid='.$id.';';
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

$app->get('/dates/{id}/washers', function (Request $request, Response $response) {
    $dateid = $request->getAttribute('id');
    echo '[';
    $sql = 'SELECT user_id, name, numerator, denominator FROM users WHERE user_id IN (SELECT userid FROM attendance WHERE dateid = '.$dateid.' AND late = 0) ORDER BY users.numerator / users.denominator;';
    $result = mysqli_query($this->link, $sql);
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));       
    }
    echo']';
});

//logic for PUT endpoints 
$app->put('/dates/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $body = $request->getParsedBody();
    

    $sql = 'SELECT cook FROM dates WHERE date_id='.$id.';';
    $result = mysqli_query($this->link, $sql);
    $cookstuff = mysqli_fetch_assoc($result);
    $cook = $cookstuff["cook"];
 

    $currentuser = $request->getAttribute('bruger');
    $jwtuserid = $currentuser['user_id'];

    if (!(intval($jwtuserid)==$cook || $currentuser['admin'])) {
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
})->add($authonly);

//logic for PUT endpoints 
$app->put('/dates/{id}/attendees/{userid}', function (Request $request, Response $response) {
    $dateid = $request->getAttribute('id');
    $userid = $request->getAttribute('userid');
    $body = $request->getParsedBody();

    
    $currentuser = $request->getAttribute('bruger');
    $jwtuserid = $currentuser['user_id'];

    if (!(intval($jwtuserid)==$userid || $currentuser['admin'])) {
        return $response
            ->withStatus(401)
            ->write("Could not authorize user");
    }

    /*
    try{
        die_if_after('2018-02-03', '12'); //Indsæt date fra databasen og klokkeslæt den skal dø, integet imellem 0-24
    } catch(Exception $e) {
        return $response->withStatus(410)->write("its too late to change");
    }
    */

    $keys=array();
    $vals=array();

    foreach($body as $key => $value){
        if(is_string($value)){
            $value = "'".$value."'";
        }
        array_push($keys, $key);
        array_push($vals, $value);
    }

    $sql = "REPLACE INTO attendance ( userid, dateid, ".join(", ",$keys).") VALUES ( ".$userid.", ".$dateid.", ".join(", ",$vals).");";
    $sql2= "SELECT * FROM attendance WHERE userid=".$userid." AND dateid=".$dateid.";";
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
})->add($authonly);

$app->put('/dates/{id}/settle', function (Request $request, Response $response) {
    $dateid = $request->getAttribute('id');
    $body = $request->getParsedBody();

    $sql = 'SELECT settled FROM dates WHERE date_id='.$dateid.';';
    $result = mysqli_query($this->link, $sql);
    $datevalues = mysqli_fetch_assoc($result);
    $settled = $datevalues["settled"];

    if($settled){
        return $response
            ->withStatus(412)
            ->write("date already settled.");
    }

    $currentuser = $request->getAttribute('bruger');
    $jwtuserid = $currentuser['user_id'];

    if (!(intval($jwtuserid)==$body['cookid'] || $currentuser['admin'])) {
        return $response
            ->withStatus(401)
            ->write("Could not authorize user");
    }

    $keys=array();
    $values=array();

    foreach($body as $key => $value){
        array_push($keys,$key);
        array_push($values,$value);
    }

    $sql = "CALL settle ( ".$dateid.", ".$body['cookid'].", ".$body['helpid'].", ".$body['washerone'].", ".$body['washertwo'].", ".$body['washerthree'].", ".$body['price'].");";
    $updateresult = mysqli_query($this->link, $sql);
    $sql2 = "SELECT * FROM dates WHERE date_id = ".$dateid.";";

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
})->add($authonly);

$app->put('/dates/{id}/unsettle', function (Request $request, Response $response) {
    $dateid = $request->getAttribute('id');
    $body = $request->getParsedBody();

    $sql = 'SELECT cook, price, settled FROM dates WHERE date_id='.$dateid.';';
    $result = mysqli_query($this->link, $sql);
    $datevalues = mysqli_fetch_assoc($result);
    $cook = $datevalues["cook"];
    $price = $datevalues["price"];
    $settled = $datevalues["settled"];

    if(!$settled){
        return $response
            ->withStatus(412)
            ->write("date already not settled.");
    }

    $sql = "CALL unsettle ( ".$dateid.", ".$cook.", ".$price.");";
    $updateresult = mysqli_query($this->link, $sql);
    $sql2 = "SELECT * FROM dates WHERE date_id = ".$dateid.";";

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
})->add($adminonly)->add($authonly);

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
})->add($adminonly)->add($authonly);


$app->post('/dates/{id}/attendees', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $body = $request->getParsedBody();
    $currentuser = $request->getAttribute('bruger');
    $userid = $currentuser['user_id'];

    if (!(intval($userid)==$body['userid'] || $currentuser['admin'])) {
        return $response
            ->withStatus(401)
            ->write("Could not authorize user");
    }
    
    /*
    try{
        die_if_after('2018-02-03', '12'); //Indsæt date fra databasen og klokkeslæt den skal dø, integet imellem 0-24
    } catch(Exception $e) {
        return $response->withStatus(410)->write("its too late to change");
    }
    */

    $keys=array();
    $values=array();

    foreach($body as $key => $value){
        array_push($keys,$key);
        array_push($values,"\"".$value."\"");
    }

    $sql = "INSERT INTO attendance ( dateid, ".join(", ",$keys).") VALUES ( ".$id.", ".join(", ",$values).");";

    $updateresult = mysqli_query($this->link, $sql);
    $sql2 = "SELECT * FROM attendance WHERE userid = ".$body['userid']." AND dateid = ".$id.";";

    if(!$updateresult){
        return $response
            ->withStatus(500)
            ->write("Could not post attendance");
    }
    $result = mysqli_query($this->link, $sql2);
    for ($i=0;$i<mysqli_num_rows($result);$i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }  

    //return $response;
})->add($authonly);


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
})->add($adminonly)->add($authonly);


$app->delete('/dates/{id}/attendees/{userid}', function (Request $request, Response $response) {
    $dateid = $request->getAttribute('id');
    $userid = $request->getAttribute('userid');

    $currentuser = $request->getAttribute('bruger');
    $jwtuserid = $currentuser['user_id'];

    if (!(intval($jwtuserid)==$userid || $currentuser['admin'])) {
        return $response
            ->withStatus(401)
            ->write("Could not authorize user");
    }

    $sql = 'SELECT cook FROM dates WHERE date_id='.$dateid.';';
    $result = mysqli_query($this->link, $sql);
    $datevalues = mysqli_fetch_assoc($result);
    $cook = $datevalues["cook"];

    if($cook == $userid){
        return $response
            ->withStatus(401)
            ->write("The cook must attend his/her own date.");
    }

    $sql = "DELETE FROM attendance WHERE dateid = ".$dateid." AND userid = ".$userid.";";
    $result = mysqli_query($this->link, $sql);

    if($result){
        return $response
            ->withStatus(204)
            ->write("Attendence from date with id: ".$dateid." and userid ".$userid." deleted.");
    } else {
        return $response
            ->withStatus(404)
            ->write("Attendence from date with id: ".$dateid." and userid ".$userid." was not found.");
    };
    

    //return $response;
})->add($authonly);


