<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $chosen_budget = (int) $_SESSION["questions_and_answers"][BUDGET_QUESTION_ORDER_NUMBER];
    
    $statement->bindValue(':chosen_budget_1', $chosen_budget, PDO::PARAM_INT);
    $statement->bindValue(':chosen_budget_2', $chosen_budget, PDO::PARAM_INT);
