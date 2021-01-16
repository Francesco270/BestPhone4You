<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query .= " SMARTPHONES.polliciSchermo BETWEEN :display_dimension_lower AND :display_dimension_upper";
