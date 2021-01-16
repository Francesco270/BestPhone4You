<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query .= " PREZZI_SMARTPHONES_NEGOZI.prezzo BETWEEN :chosen_budget_1 - 100 AND :chosen_budget_2";
    