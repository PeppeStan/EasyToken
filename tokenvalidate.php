<?php

include 'token.php';

//Get the Variables from URL with GET Method
$token = $_GET['token'];
$emailpre = $_GET['email'];
$email = filter_var($emailpre, FILTER_SANITIZE_EMAIL); //Sanitize Email for unwanted stuff

 if(strlen($token) > 5 && strlen($email) > 5 ) { //Check if token has lenght > 5 for safety reason and also check if email is inserted

    validate_token($token,$email); //Validate token process

}
else {
    echo 'Sorry! No Valid Token or email';
}

?>