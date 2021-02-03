<?php
    if( !defined("APPNAME") )
    {
        exit;
    }
?>

<h3 id="homepage-heading" class="text-align-center">Troppi modelli di Smartphone<br>e troppi brand fra cui scegliere?</h3>
<div id="homepage-smartphones-container">
    <img src="./assets/images/smartphone_1.png">
    <img src="./assets/images/smartphone_2.png">
    <img class="hide-on-mobile" src="./assets/images/smartphone_3.png">
    <img class="hide-on-mobile" src="./assets/images/smartphone_5.png">
    <img src="./assets/images/smartphone_4.png">
    <img class="hide-on-mobile" src="./assets/images/smartphone_2.png">
    <img class="hide-on-mobile" src="./assets/images/smartphone_5.png">
    <img class="hide-on-mobile" src="./assets/images/smartphone_2.png">
    <img class="hide-on-mobile" src="./assets/images/smartphone_4.png">
    <img src="./assets/images/smartphone_5.png">
    <img src="./assets/images/smartphone_3.png">
    <img class="hide-on-mobile" src="./assets/images/smartphone_2.png">
    <img class="hide-on-mobile" src="./assets/images/smartphone_1.png">
</div>
<h2 id="homepage-cta-heading">Ti aiutiamo noi!</h2>
<p id="homepage-cta-text" class="text-align-center">
    Dicci quali sono le tue esigenze rispondendo a poche e semplici domande.
    Alla fine ti proporremo solo gli Smartphone che fanno al caso tuo,
    con i relativi prezzi e i link d'acquisto ai principali shop online.
</p>
<button class="bp4y-start-procedure-button bp4y-button" type="button">COMINCIA A SCEGLIERE</button>
<input type="hidden" id="bp4y-token" value="<?php echo $_SESSION['csrf_token']; ?>">
<?php
    if( isset($_SESSION["questions_and_answers"]) )
    {
        ?>
        <div id="screen-fade-container">
            <div class="modal-dialog-box">
                <span class="heading">Ripristino della Sessione</span>
                <div class="body">
                    La pagina è stata ricaricata oppure si è verificato&nbsp;un&nbsp;problema&nbsp;di&nbsp;rete. Vuoi&nbsp;riprendere&nbsp;dall'ultima&nbsp;domanda che stavi leggendo?
                </div>
                <div class="buttons-container">
                    <button id="bp4y-resume-procedure-button" class="bp4y-button" type="button">SÌ, RIPRENDI</button>
                    <button class="bp4y-start-procedure-button bp4y-button secondary" type="button">NO, RICOMINCIA DALL'INIZIO</button>
                </div>
            </div>
        </div>
        <?php
    }
