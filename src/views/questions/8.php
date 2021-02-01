<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query = "SELECT jackAudio";
    $sql_query .= " FROM SMARTPHONES";
    $sql_query .= " JOIN PREZZI_SMARTPHONES_NEGOZI ON SMARTPHONES.idSmartphone = PREZZI_SMARTPHONES_NEGOZI.idSmartphone";

    build_and_execute_sql_query($sql_query);

    $jack_audio_rows = $statement->fetchAll(PDO::FETCH_OBJ);

    $jack_audio_smartphones_count = 0;
    foreach($jack_audio_rows as $row)
    {
        if($row->jackAudio == 1)
        {
            $jack_audio_smartphones_count++;
            break; // if at least one smartphone has the jack connector, this loop can be interrupted earlier
        }
    }

    // Display the question only if there is at least one smartphone which has the jack connector.
    if($jack_audio_smartphones_count > 0)
    {
        $question = new Question(__FILE__, $questions_and_answers[AUDIO_JACK_QUESTION_ORDER_NUMBER]["question_text"]);
    }

    if(isset($question)):
?>

<div class="question-container">
    <span class="question-number">DOMANDA <?php echo count($_SESSION["questions_and_answers"]); ?></span>
    <span class="question-text-small"><?php echo $question->get_question_text(); ?></span>
</div>
<div class="buttons-row-container stack-on-mobile">
    <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="1"><?php echo $questions_and_answers[AUDIO_JACK_QUESTION_ORDER_NUMBER]["question_responses"][1]; ?></button>
    <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="0"><?php echo $questions_and_answers[AUDIO_JACK_QUESTION_ORDER_NUMBER]["question_responses"][0]; ?></button>
</div>
<span class="question-help small">
    Uno Smartphone con il jack audio permette
    di usare cuffie cablate con connettore jack che in genere offrono
    <strong>una qualità maggiore nell'ascolto</strong>.
</span>
<button id="bp4y-back-button" title="Torna alla domanda precedente"><img src="/assets/icons/back.svg" alt="Torna alla domanda precedente"></button>
<input type="hidden" id="bp4y-token" value="<?php echo $_SESSION['csrf_token']; ?>">

<?php
    endif;
?>