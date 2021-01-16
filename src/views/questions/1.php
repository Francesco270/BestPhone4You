<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $question = new Question(__FILE__, "Quanto vorresti spendere, al massimo?");

    $statement = $connection->prepare("SELECT MIN(prezzo) FROM PREZZI_SMARTPHONES_NEGOZI;");
    $statement->execute();
    $minimumSmartphonePrice = $statement->fetch(PDO::FETCH_NUM)[0];
    $lowerPriceRangeSliderValue = ($minimumSmartphonePrice + 100) - ($minimumSmartphonePrice % 100);                

    $statement = $connection->prepare("SELECT MAX(prezzo) FROM PREZZI_SMARTPHONES_NEGOZI;");
    $statement->execute();
    $maximumSmartphonePrice = $statement->fetch(PDO::FETCH_NUM)[0];
    $upperPriceRangeSliderValue = ($maximumSmartphonePrice) - ($maximumSmartphonePrice % 100);
?>

<div class="question-container">
    <span class="question-number">DOMANDA <?php echo count($_SESSION["questions_and_answers"]); ?></span>
    <span class="question-text"><?php echo $question->get_question_text(); ?></span>
</div>
<div id="price-range-slider-container">
    <span id="price-range-slider-value-label">300&euro;</span>
    <span class="price-range-slider-side-label">Meno di <?php echo $lowerPriceRangeSliderValue; ?>&euro;</span>
    <input type="range" id="price-range-slider" min="<?php echo $lowerPriceRangeSliderValue; ?>" max="<?php echo $upperPriceRangeSliderValue; ?>" step="50" value="300">
    <span class="price-range-slider-side-label">Più di <?php echo $upperPriceRangeSliderValue; ?>&euro;</span>
</div>
<span class="question-help">Regola questo slider per aumentare o diminuire il tuo budget.</span>
<button class="bp4y-response-confirm-button bp4y-button" type="button" data-response-value="300">CONFERMA BUDGET</button>
<input type="hidden" id="bp4y-token" value="<?php echo $_SESSION['csrf_token']; ?>">