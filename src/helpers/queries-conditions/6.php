<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query .= " SMARTPHONES.capacitaBatteria >= :battery_capacity_value";
    