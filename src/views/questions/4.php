<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query = "SELECT DISTINCT capacitaMemoriaInterna";
    $sql_query .= " FROM SMARTPHONES";
    $sql_query .= " JOIN PREZZI_SMARTPHONES_NEGOZI ON SMARTPHONES.idSmartphone = PREZZI_SMARTPHONES_NEGOZI.idSmartphone";

    build_and_execute_sql_query($sql_query);

    $sql_query_rows = $statement->fetchAll(PDO::FETCH_OBJ);

    $smartphones_with_64_GB_memory_are_present = false;
    $smartphones_with_128_GB_memory_are_present = false;
    $smartphones_with_256_GB_and_above_memory_are_present = false;
    foreach($sql_query_rows as $row)
    {
        if($row->capacitaMemoriaInterna == 64)
        {
            $smartphones_with_64_GB_memory_are_present = true;
        }
        elseif($row->capacitaMemoriaInterna == 128)
        {
            $smartphones_with_128_GB_memory_are_present = true;
        }
        elseif($row->capacitaMemoriaInterna >= 256)
        {
            $smartphones_with_256_GB_and_above_memory_are_present = true;
        }

        // Stop checking if all memory capacities are present
        if(
            $smartphones_with_64_GB_memory_are_present &&
            $smartphones_with_128_GB_memory_are_present &&
            $smartphones_with_256_GB_and_above_memory_are_present
        )
        {
            break;
        }
    }

    // Display the question only if there are two distinct combinations of memory capacities
    if(
        ($smartphones_with_64_GB_memory_are_present && $smartphones_with_128_GB_memory_are_present) ||
        ($smartphones_with_128_GB_memory_are_present && $smartphones_with_256_GB_and_above_memory_are_present) ||
        ($smartphones_with_64_GB_memory_are_present && $smartphones_with_256_GB_and_above_memory_are_present)
    )
    {
        $question = new Question(__FILE__, $questions_and_answers[MEMORY_CAPACITY_QUESTION_ORDER_NUMBER]["question_text"]);
    
    }
    
    if(isset($question)):
?>

<div class="question-container">
    <span class="question-number">DOMANDA <?php echo count($_SESSION["questions_and_answers"]); ?></span>
    <span class="question-text-small"><?php echo $question->get_question_text(); ?></span>
</div>
<div class="buttons-row-container stack-on-mobile">
    <?php if($smartphones_with_64_GB_memory_are_present): ?>
        <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="1"><?php echo $questions_and_answers[MEMORY_CAPACITY_QUESTION_ORDER_NUMBER]["question_responses"][1]; ?></button>
    <?php endif; ?>
    <?php if($smartphones_with_128_GB_memory_are_present): ?>
        <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="2"><?php echo $questions_and_answers[MEMORY_CAPACITY_QUESTION_ORDER_NUMBER]["question_responses"][2]; ?></button>
    <?php endif; ?>
    <?php if($smartphones_with_256_GB_and_above_memory_are_present): ?>
        <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="3"><?php echo $questions_and_answers[MEMORY_CAPACITY_QUESTION_ORDER_NUMBER]["question_responses"][3]; ?></button>
    <?php endif; ?>
</div>
<span class="question-help small">
    <strong>All'aumentare della capienza</strong> della memoria interna, aumenta
    il numero di foto, video e altri elementi multimediali
    che possono essere memorizzati, così come
    il numero di applicazioni che possono essere installate.<br><br>

    Ad ogni modo, <strong>anche il prezzo dello Smartphone aumenta</strong>.
</span>
<button id="bp4y-back-button" title="Torna alla domanda precedente"><img src="/assets/icons/back.svg" alt="Torna alla domanda precedente"></button>
<input type="hidden" id="bp4y-token" value="<?php echo $_SESSION['csrf_token']; ?>">

<?php
    endif;
?>