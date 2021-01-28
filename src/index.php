<?php
    define("APPNAME", "BESTPHONE4YOU");
    define("BP4Y_APP_ROOT_PATH", $_SERVER["DOCUMENT_ROOT"] . "/");
    define("BP4Y_APP_ROOT_URI", (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/');

    // Start a session and set the cookie expiration time to 1 hour
    session_start([
        "cookie_lifetime" => 3600 // seconds
    ]);

    // Create a CSRF token only if it has not been created yet
    // TODO: Maybe re-create the token if the user turns back to the homepage after having been in the results page
    //       The session is not unset, but only the "questions_and_answers" and "last_action" keys are removed,
    //       and the "csrf_token" is preserved. This is done to allow the token checking on the feedbacks controller
    if( !isset($_SESSION["csrf_token"]) && empty($_SESSION["csrf_token"]) )
    {
        // Generate a token that will be used to prevent CSRFs
        // and store it in the $_SESSION PHP superglobal.
        $_SESSION["csrf_token"] = bin2hex(random_bytes(16));
    }

    require_once("./views/header.php");    
?>
<body>
    <img id="site-logo" src="./assets/images/bp4y-logo.png" alt="BestPhone4You">
    <div id="application-view-container">
        <?php
            // Check if the User Session ID is set in the URL
            if(isset($_GET["usid"]) && !empty($_GET["usid"]))
            {
                require_once(BP4Y_APP_ROOT_PATH . "helpers/db_connection.php");

                $user_session_id = $_GET["usid"];

                // Check if the User Session ID exists in the DB, otherwise display an error page
                $check_if_generated_user_session_id_already_exists_sql_query = "SELECT idSessione";
                $check_if_generated_user_session_id_already_exists_sql_query .= " FROM SESSIONI_UTENTI";
                $check_if_generated_user_session_id_already_exists_sql_query .= " WHERE idSessione = :user_session_id";
                $check_if_generated_user_session_id_already_exists_sql_query .= ";";
                
                $check_if_generated_user_session_id_already_exists_sql_statement = $connection->prepare($check_if_generated_user_session_id_already_exists_sql_query);
                $check_if_generated_user_session_id_already_exists_sql_statement->bindValue(":user_session_id", $user_session_id, PDO::PARAM_STR);
                $check_if_generated_user_session_id_already_exists_sql_statement->execute();
                $check_if_generated_user_session_id_already_exists_sql_query_results = $check_if_generated_user_session_id_already_exists_sql_statement->fetchAll(PDO::FETCH_ASSOC);

                if(!empty($check_if_generated_user_session_id_already_exists_sql_query_results))
                {
                    // The User Session ID specified in the GET request exists in the User Session Table

                    // Load session data
                    $get_user_session_data_sql_query = "SELECT datiSessione";
                    $get_user_session_data_sql_query .= " FROM SESSIONI_UTENTI";
                    $get_user_session_data_sql_query .= " WHERE idSessione = :user_session_id";
                    $get_user_session_data_sql_query .= ";";

                    $get_user_session_data_sql_query_statement = $connection->prepare($get_user_session_data_sql_query);
                    $get_user_session_data_sql_query_statement->bindValue(":user_session_id", $user_session_id, PDO::PARAM_STR);
                    $get_user_session_data_sql_query_statement->execute();
                    $user_session_data = json_decode($get_user_session_data_sql_query_statement->fetchAll(PDO::FETCH_ASSOC)[0]["datiSessione"], true);

                    $_SESSION["questions_and_answers"] = $user_session_data;

                    $is_a_shared_results_page_request = true;
                    require_once(BP4Y_APP_ROOT_PATH . "controllers/jarvis.php");
                    // exit;
                }
                else
                {
                    // The URL does not refer to any results page (the specified User Session ID does not exist)
                    require_once(BP4Y_APP_ROOT_PATH . "views/404.php");
                    exit;
                }
            }
            else
            {
                require_once(BP4Y_APP_ROOT_PATH . "views/homepage.php");
            }
        ?>
    </div>
</body>
</html>