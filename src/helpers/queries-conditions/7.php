<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query .= " SMARTPHONES.ricaricaWireless >= :wireless_charging_boolean_value";