<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    /**
     * RV   | Memory Capacity (in GB)
     * =====|======================
     * 1    | 64
     * 2    | 128
     * 3    | 256 and above
     */
    $chosen_memory_capacity = (int) $_SESSION["questions_and_answers"][MEMORY_CAPACITY_QUESTION_ORDER_NUMBER];
    if($chosen_memory_capacity == 3)
    {
        $comparison_operator = ">=";
    }
    else
    {
        $comparison_operator = "=";
    }

    $sql_query .= " SMARTPHONES.capacitaMemoriaInterna " . $comparison_operator . " :memory_capacity";
    