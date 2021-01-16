<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query = "SELECT COUNT(DISTINCT idSistemaOperativo)";
    $sql_query .= " FROM SMARTPHONES";
    $sql_query .= " JOIN PREZZI_SMARTPHONES_NEGOZI ON SMARTPHONES.idSmartphone = PREZZI_SMARTPHONES_NEGOZI.idSmartphone";

    build_and_execute_sql_query($sql_query);

    $OS_count = $statement->fetch(PDO::FETCH_NUM)[0];

    // Display the question only if there is more than one distinct OS 
    if($OS_count > 1)
    {
        $question = new Question(__FILE__, "Quale Sistema Operativo vorresti usare?");

        $OS_infos_sql_query = "SELECT idSistemaOperativo, nomeSistemaOperativo, percorsoImmagineLogoSistemaOperativo";
        $OS_infos_sql_query .= " FROM SISTEMI_OPERATIVI;";

        $OS_infos_rows = $connection->query($OS_infos_sql_query, PDO::FETCH_OBJ);
    }

    if(isset($question)):
?>

<div class="question-container">
    <span class="question-number">DOMANDA <?php echo count($_SESSION["questions_and_answers"]); ?></span>
    <span class="question-text"><?php echo $question->get_question_text(); ?></span>
</div>
<div class="images-and-buttons-row-container stack-on-mobile">
    <?php foreach($OS_infos_rows as $row): ?>
        <div class="image-and-response-container">
            <img src="<?php echo $row->percorsoImmagineLogoSistemaOperativo; ?>" alt="Logo <?php echo $row->nomeSistemaOperativo; ?>">
            <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="<?php echo $row->idSistemaOperativo; ?>"><?php echo $row->nomeSistemaOperativo; ?></button>
        </div>
    <?php endforeach; ?>
</div>
<span style="margin: 1rem 0">oppure</span>
<div class="image-and-response-container">
    <img src="/assets/images/idk.png" alt="Non mi interessa">
    <button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="0">NON MI INTERESSA</button>
</div>
<button id="bp4y-back-button" title="Torna alla domanda precedente"><img src="/assets/icons/back.svg" alt="Torna alla domanda precedente"></button>
<input type="hidden" id="bp4y-token" value="<?php echo $_SESSION['csrf_token']; ?>">

<?php
    endif;
?>