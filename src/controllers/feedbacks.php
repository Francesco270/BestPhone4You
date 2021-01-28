<?php

session_start([
    "cookie_lifetime" => 3600
]);

if(isset($_SESSION["csrf_token"]) && isset($_POST["token"]) && $_POST["token"] == $_SESSION["csrf_token"])
{
    try
    {
        $to      = 'root@localhost';
        $subject = 'Nuovo Feedback ricevuto';
        $message = $_POST["feedback_message"];
    
        mail($to, $subject, $message);

        echo true;
    }
    catch(Exception $e)
    {
        echo false;
    }
}
else
{
    // The request is forged. Probably someone messed up with DevTools
    echo false;
}
