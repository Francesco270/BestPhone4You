<?php

/**
 * Questions Controller - Codename "JARVIS"
 */

session_start([
    "cookie_lifetime" => 3600
]);

if(
    isset($_SESSION["csrf_token"]) &&
    isset($_POST["token"]) &&
    isset($_POST["action"]) &&
    $_POST["token"] == $_SESSION["csrf_token"] &&
    in_array($_POST["action"], array("start_procedure", "resume_procedure", "next_question", "previous_question"))
)
{
    // The AJAX request is valid

    define("APPNAME", "BESTPHONE4YOU");
    define("QUESTION_TEMPLATE_FILENAME_MODEL", "./../views/questions/%d.php");
    require("./../helpers/question_order_number_definitions.php");

    class Question
    {
        protected int $order_number;
        protected string $text;

        public function __construct(string $question_template_file_path, string $text)
        {
            // Get the question order number from the question template file name
            $this->order_number = sscanf(basename($question_template_file_path, ".php"), basename(QUESTION_TEMPLATE_FILENAME_MODEL, ".php"))[0];
            $this->text = $text;

            if( in_array($_POST["action"], array("start_procedure", "next_question")) )
            {
                $_SESSION["questions_and_answers"][$this->order_number] = 0; // Initialize the question in the session variable
            }
        }

        public function get_question_text()
        {
            return $this->text;
        }
    }

    function build_and_execute_sql_query(string $sql_query, string $after_conditions = NULL)
    {
        global $connection;
        global $statement;

        $answered_questions_ids = array_keys($_SESSION["questions_and_answers"]);

        $sql_query .= " WHERE";
        for($i = 0; $i < count($answered_questions_ids); $i++)
        {
            // If the user turns back to the previous question or resumes the session, then don't add the query condition of that question
            if( in_array($_SESSION["last_action"], array("previous_question", "resume_procedure")) && $i == (count($answered_questions_ids) - 1) )
            {
                break;
            }

            if($i != 0)
            {
                $sql_query .= " AND";
            }

            require("./../helpers/queries-conditions/" . $answered_questions_ids[$i] . ".php");
        }

        if(isset($after_conditions))
        {
            $sql_query .= " " . $after_conditions;
        }

        $sql_query .= ";";

        $statement = $connection->prepare($sql_query);

        for($i = 0; $i < count($answered_questions_ids); $i++)
        {
            // If the user turns back to the previous question or resumes the session, then don't add the query bindings with the answer of that question
            if( in_array($_SESSION["last_action"], array("previous_question", "resume_procedure")) && $i == (count($answered_questions_ids) - 1) )
            {
                break;
            }

            require("./../helpers/queries-binders/" . $answered_questions_ids[$i] . ".php");
        }

        $statement->execute();
    }

    require("./../../credentials/database.php");
    try
    {
        $connection = new PDO("mysql:host=" . BP4Y_DB_HOST . ";dbname=" . BP4Y_DB_NAME . ";charset=utf8",
                                BP4Y_DB_USERNAME,
                                BP4Y_DB_PASSWORD,
                                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                            );
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    catch(PDOException $e) 
    {
        // DB Connection has failed
        exit;
    }

    if($_POST["action"] == "start_procedure")
    {
        $_SESSION["questions_and_answers"] = array();
        $_SESSION["last_action"] = "start_procedure";

        $template_filename = sprintf(QUESTION_TEMPLATE_FILENAME_MODEL, 1);

        require($template_filename);
    }
    elseif($_POST["action"] == "resume_procedure")
    {
        $_SESSION["last_action"] = "resume_procedure";

        $current_question_order_number = array_keys($_SESSION["questions_and_answers"])[count($_SESSION["questions_and_answers"]) - 1];
        $template_filename = sprintf(QUESTION_TEMPLATE_FILENAME_MODEL, $current_question_order_number);

        require($template_filename);
    }
    elseif($_POST["action"] == "next_question")
    {
        $_SESSION["last_action"] = "next_question";

        $current_question_order_number = array_keys($_SESSION["questions_and_answers"])[count($_SESSION["questions_and_answers"]) - 1];

        $_SESSION["questions_and_answers"][$current_question_order_number] = $_POST["response_value"];

        $next_question_order_number = $current_question_order_number + 1;

        if( file_exists(sprintf(QUESTION_TEMPLATE_FILENAME_MODEL, $next_question_order_number)) )
        {
            while( !isset($question) && file_exists(sprintf(QUESTION_TEMPLATE_FILENAME_MODEL, $next_question_order_number)) )
            {
                require(sprintf(QUESTION_TEMPLATE_FILENAME_MODEL, $next_question_order_number));

                $next_question_order_number++;
            }
        }

        if( !isset($question) && !file_exists(sprintf(QUESTION_TEMPLATE_FILENAME_MODEL, $next_question_order_number)) )
        {
            // There are no more questions to ask.
            // Hence, display the results page
            require("./../views/results_page.php");
        }
    }
    elseif($_POST["action"] == "previous_question")
    {
        if(count($_SESSION["questions_and_answers"]) >= 2)
        {
            $_SESSION["last_action"] = "previous_question";

            // Remove the last question from the sequence
            array_pop($_SESSION["questions_and_answers"]);

            $previous_question_order_number = array_keys($_SESSION["questions_and_answers"])[count($_SESSION["questions_and_answers"]) - 1];
            $template_filename = sprintf(QUESTION_TEMPLATE_FILENAME_MODEL, $previous_question_order_number);
        }
        else
        {
            // To go back to the previous question, there must be at least 2 questions.
            // So the request is forged. Probably someone messed up with DevTools
            echo false;
            exit;
        }

        require($template_filename);
    }
}
else
{
    // The request is forged. Probably someone messed up with DevTools
    echo false;
}
