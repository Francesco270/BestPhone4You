<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $must_have_audio_jack = (int) $_SESSION["questions_and_answers"][AUDIO_JACK_QUESTION_ORDER_NUMBER];

    // Check if the audio jack response value is valid (must be 0 or 1)
    if( in_array($must_have_audio_jack, array(0, 1)) )
    {
        $statement->bindValue(':audio_jack_boolean_value', $must_have_audio_jack, PDO::PARAM_INT);
    }
    else
    {
        // ... otherwise, exit immediately. The request has been forged.
        exit;
    }
    