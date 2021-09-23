 <?php
//These must be at the top of your script, not inside a function
require("../vendor/phpmailer/phpmailer/src/PHPMailer.php"); 
require("../vendor/phpmailer/phpmailer/src/SMTP.php");
$timestamp = date("Y-m-d"); //We generate a DATETIME FORMAT DATE FOR EASY MYSQL INSERTION - USER DATE WITH 6 NUMBER LIMIT

function generate_token($tokenlenght) { //This Generates a token with a custom Lenght as $tokenlenght so wee need to pass the lenght RECONDEMEND AT LEAST 5.

    $token = bin2hex(random_bytes($tokenlenght));
    return $token; //Returns the token for further uses
}

function upload_token($token,$email) { //Upload token to Database

    require('database.php'); //Requiring connection
    $timestamp = date("Y-m-d"); //TimeStamping today
    
                //we start the query
                $query1 = "
                INSERT INTO tokens
                VALUES (0, :email, :token, :timestamp)
            ";
            $check1 = $pdo->prepare($query1);
            $check1->bindParam(':email', $email, PDO::PARAM_STR);
            $check1->bindParam(':token', $token, PDO::PARAM_STR);
            $check1->bindParam(':timestamp', $timestamp, PDO::PARAM_STR); //when time to compare will be use this https://www.php.net/manual/en/datetime.diff.php
            $check1->execute();

}

function mail_token($token,$email) { //Sends an Email with token link, still a W.I.P. Maybe can use PhpMailer

    $weburl = 'http://mywebsite/tokenvalidate.php'; //your tokenvalidate.php file folder into website
    $url = $weburl.'?token='.$token.'&email='.$email; //Concatenate and create the get string 

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "yourhost";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "yourusername";
    $mail->Password = "yourpassword";
    $mail->SetFrom("youremail");
    $mail->Subject = "your subject";
    $mail->Body    = 'Hi, Please click on the link in order to activate your account'.' '.$url.' '.'Thanks!';
    $mail->AddAddress($email);

     if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
     } else {
        echo "Message has been sent";
     }



    }

    function validate_token($token,$email) { //This Validate the token with given $token and $email 

        require('database.php'); //Connecting to database

        //Search for the token and gets time of token and email
        $query = "
            SELECT email,timestamp
            FROM tokens
            WHERE token = :token
        ";
        $check = $pdo->prepare($query);
        $check->bindParam(':token', $token, PDO::PARAM_STR);
        $check->execute();
        
        $tokens = $check->fetchAll(PDO::FETCH_ASSOC); //Creates an array of results
        
        //He found Token and Email so we can check time difference and validate it
        if (count($tokens) > 0) { //If the array gives more than 0 then we will procede

            foreach ($tokens as $tokens1) { //For each array value we execute it 
            $emails = $tokens1['email']; // Catch the email from the array
            $tokentime = new DateTime($tokens1['timestamp']); //The time of the originaltoken
            $timestamp = new DateTime('now'); // Time of now
            $diff = date_diff($tokentime, $timestamp); //Difference of Token time from Now
            $diffconv = $diff->format('%R%a'); //Formatting in +X Days
            
            if ($diffconv != '+0' ){ //If the result it's more than 24 hours or 1day then token it's expired!

                echo 'Token Expired!';
            }
            elseif ($emails == $email) { //Else if token not expired and email given is the same as the token email update user and delete token ((security feature))

                //Update User table
                
                $regtoken = 'yes'; //yes as token valid
                $query = "UPDATE users SET reg_token=? WHERE email=?";
                $stmt= $pdo->prepare($query);
                $stmt->execute([$regtoken, $emails]);

                //Delete Used Token

                $query1 = "DELETE FROM tokens WHERE email=?";
                $stmt1= $pdo->prepare($query1);
                $stmt1->execute([$email]);

                echo 'User activated!';
;       
            }

            }
        } 
        //Didn' found the token or it's late
        else {
            echo 'No valid token found';
        }

    }



?>