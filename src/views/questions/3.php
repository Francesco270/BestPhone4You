<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query = "SELECT DISTINCT polliciSchermo";
    $sql_query .= " FROM SMARTPHONES";
    $sql_query .= " JOIN PREZZI_SMARTPHONES_NEGOZI ON SMARTPHONES.idSmartphone = PREZZI_SMARTPHONES_NEGOZI.idSmartphone";

    build_and_execute_sql_query($sql_query);

    $sql_query_rows = $statement->fetchAll(PDO::FETCH_OBJ);

    $smartphones_with_small_display_are_present = false;
    $smartphones_with_medium_display_are_present = false;
    $smartphones_with_large_display_are_present = false;
    foreach($sql_query_rows as $row)
    {
        if($row->polliciSchermo >= 4.7 && $row->polliciSchermo <= 5.5)
        {
            $smartphones_with_small_display_are_present = true;
        }
        elseif($row->polliciSchermo >= 5.51 && $row->polliciSchermo <= 6.2)
        {
            $smartphones_with_medium_display_are_present = true;
        }
        elseif($row->polliciSchermo >= 6.21 && $row->polliciSchermo <= 7.6)
        {
            $smartphones_with_large_display_are_present = true;
        }

        // Stop checking if all displays dimensions are present
        if(
            $smartphones_with_small_display_are_present &&
            $smartphones_with_medium_display_are_present &&
            $smartphones_with_large_display_are_present
        )
        {
            break;
        }
    }

    // Display the question only if there are two distinct combinations of display dimensions
    if(
        ($smartphones_with_small_display_are_present && $smartphones_with_medium_display_are_present) ||
        ($smartphones_with_medium_display_are_present && $smartphones_with_large_display_are_present) ||
        ($smartphones_with_small_display_are_present && $smartphones_with_large_display_are_present)
    )
    {
        $question = new Question(__FILE__, "Quale deve essere la dimensione dello schermo?");
    }

    if(isset($question)):
?>

<div class="question-container">
    <span class="question-number">DOMANDA <?php echo count($_SESSION["questions_and_answers"]); ?></span>
    <span class="question-text"><?php echo $question->get_question_text(); ?></span>
</div>
<div class="images-and-buttons-row-container stack-on-mobile">
    <?php if($smartphones_with_small_display_are_present): ?>
        <div class="image-and-response-container">
            <span>Dai 4.7" ai 5.5"</span>
            <img id="smartphone-display-small-image" src="/assets/images/smartphone-display-diagonal.png" alt="Schermo piccolo">
            <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="1">PICCOLA</button>
        </div>
    <?php endif; ?>
    <?php if($smartphones_with_medium_display_are_present): ?>
        <div class="image-and-response-container">
            <span>Dai 5.6" ai 6.2"</span>
            <img id="smartphone-display-medium-image" src="/assets/images/smartphone-display-diagonal.png" alt="Schermo medio">
            <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="2">MEDIA</button>
        </div>
    <?php endif; ?>
    <?php if($smartphones_with_large_display_are_present): ?>
        <div class="image-and-response-container">
            <span>Dai 6.3" ai 7.6"</span>
            <img id="smartphone-display-large-image" src="/assets/images/smartphone-display-diagonal.png" alt="Schermo grande">
            <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="3">GRANDE</button>
        </div>
    <?php endif; ?>
</div>
<button id="bp4y-back-button" title="Torna alla domanda precedente"><img src="/assets/icons/back.svg" alt="Torna alla domanda precedente"></button>
<input type="hidden" id="bp4y-token" value="<?php echo $_SESSION['csrf_token']; ?>">

<?php
    endif;
?>