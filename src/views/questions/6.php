<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query = "SELECT capacitaBatteria";
    $sql_query .= " FROM SMARTPHONES";
    $sql_query .= " JOIN PREZZI_SMARTPHONES_NEGOZI ON SMARTPHONES.idSmartphone = PREZZI_SMARTPHONES_NEGOZI.idSmartphone";

    build_and_execute_sql_query($sql_query);

    $large_battery_rows = $statement->fetchAll(PDO::FETCH_OBJ);

    $large_battery_smartphones_count = 0;
    foreach($large_battery_rows as $row)
    {
        if($row->capacitaBatteria >= 4000)
        {
            $large_battery_smartphones_count++;
            break; // if at least one smartphone has the battery capacity greater than 4000 mAh, this loop can be interrupted earlier
        }
    }

    // Display the question only if there is at least one smartphone which has the battery capacity greater than 4000 mAh
    if($large_battery_smartphones_count > 0)
    {
        $question = new Question(__FILE__, $questions_and_answers[LARGE_BATTERY_QUESTION_ORDER_NUMBER]["question_text"]);
    }

    if(isset($question)):
?>

<div class="question-container">
    <span class="question-number">DOMANDA <?php echo count($_SESSION["questions_and_answers"]); ?></span>
    <span class="question-text-small"><?php echo $question->get_question_text(); ?></span>
</div>
<div class="buttons-row-container stack-on-mobile">
    <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="1"><?php echo $questions_and_answers[LARGE_BATTERY_QUESTION_ORDER_NUMBER]["question_responses"][1]; ?></button>
    <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="0"><?php echo $questions_and_answers[LARGE_BATTERY_QUESTION_ORDER_NUMBER]["question_responses"][0]; ?></button>
</div>
<span class="question-help small">
    Una batteria <strong>molto capiente</strong>
    permette di utilizzare più a lungo lo Smartphone, a discapito
    del peso e delle dimensioni maggiori.
</span>
<button id="bp4y-back-button" title="Torna alla domanda precedente"><img src="/assets/icons/back.svg" alt="Torna alla domanda precedente"></button>
<input type="hidden" id="bp4y-token" value="<?php echo $_SESSION['csrf_token']; ?>">

<?php
    endif;
?> 