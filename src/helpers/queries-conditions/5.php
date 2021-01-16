<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query .= " SMARTPHONES.memoriaEspandibile >= :expandable_memory_boolean_value";
    