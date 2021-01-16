<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query .= " SMARTPHONES.idSistemaOperativo BETWEEN :chosen_OS_lower AND :chosen_OS_upper";
