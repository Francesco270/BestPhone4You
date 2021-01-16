<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    $sql_query = "SELECT *";
    $sql_query .= " FROM SMARTPHONES";
    $sql_query .= " JOIN PREZZI_SMARTPHONES_NEGOZI ON SMARTPHONES.idSmartphone = PREZZI_SMARTPHONES_NEGOZI.idSmartphone";
    $sql_query .= " JOIN SISTEMI_OPERATIVI ON SISTEMI_OPERATIVI.idSistemaOperativo = SMARTPHONES.idSistemaOperativo";
    $sql_query .= " JOIN PRODUTTORI ON SMARTPHONES.idProduttore = PRODUTTORI.idProduttore";
    $sql_query .= " JOIN NEGOZI ON NEGOZI.idNegozio = PREZZI_SMARTPHONES_NEGOZI.idNegozio";

    build_and_execute_sql_query($sql_query, "ORDER BY prezzo ASC");

    // save_session_data();

    $smartphones_results = $statement->fetchAll(PDO::FETCH_OBJ);

    // var_dump(session_encode());

    // Show all the questions asked, along with the associated answers 
    // require("./../models/questions_and_answers.php");

    // foreach($_SESSION["questions_and_answers"] as $question_id => $question_response_value)
    // {
    //     $question_text = $questions_and_answers[$question_id]["question_text"];
    //     if(count($questions_and_answers[$question_id]["question_responses"]) > 1)
    //     {
    //         $question_response_value_text = $questions_and_answers[$question_id]["question_responses"][$question_response_value];
    //     }
    //     else
    //     {
    //         $question_response_value_text = $questions_and_answers[$question_id]["question_responses"][0];
    //     }

    //     echo "<h2>" . $question_text . ": " . $question_response_value_text . "</h2>";
    // }

    // TODO: Make a CTA that takes the user to the main page, so that he can repeat the procedure (namely, execute a new search)

    session_unset();

    define("BP4Y_APP_ROOT_PATH", (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/');
?>

<body>
    <div id="application-view-container">
        <div id="results-page-intro-container" class="column-container">
            <div class="row-container">
                <img src="./assets/icons/finish-flag.svg">
                <div>
                    <h1>RICERCA COMPLETATA!</h1>
                    <p>Abbiamo trovato <strong><span id="results-page-smartphone-count-text"><?php echo count($smartphones_results); ?></span> Smartphone</strong> per te</p>
                </div>
                <img src="./assets/icons/finish-flag.svg" style="transform: rotateY(180deg);">
            </div>
            <p>Condividi questa pagina con chi vuoi, o rivedi le domande a cui hai risposto</p>
            <div id="results-page-intro-container-buttons-container" class="row-container" style="margin: 0.5rem 0 0 0;">
                <button class="bp4y-button small primary"><img src="./assets/icons/copy.svg">COPIA LINK PER LA CONDIVISIONE</button>
                <button class="bp4y-button small secondary"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" fill="#2d3039"/></svg>RIVEDI LE DOMANDE</button>
            </div>
        </div>
        <div id="results-page-view-options-rows" class="row-container">
            <div>
                <span>VISUALIZZAZIONE</span>
                <div class="row-container">
                    <button class="view-mode-compact-button row-container selected">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M10 18h5V5h-5v13zm-6 0h5V5H4v13zM16 5v13h5V5h-5z"/></svg>
                        <span>Compatta</span>
                    </button>
                    <button class="view-mode-complete-button row-container">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M4 14h4v-4H4v4zm0 5h4v-4H4v4zM4 9h4V5H4v4zm5 5h12v-4H9v4zm0 5h12v-4H9v4zM9 5v4h12V5H9z"/></svg>
                        <span>Completa</span>
                    </button>
                </div>
            </div>
            <div>
                <span>ORDINA PER PREZZO</span>
                <div class="row-container">
                    <button class="row-container selected">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" style="transform: rotateZ(270deg);"><path d="M5.88 4.12L13.76 12l-7.88 7.88L8 22l10-10L8 2z"/></svg>
                        <span>Crescente</span>
                    </button>
                    <button class="row-container">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" style="transform: rotateZ(90deg);"><path d="M5.88 4.12L13.76 12l-7.88 7.88L8 22l10-10L8 2z"/></svg>
                        <span>Decrescente</span>
                    </button>
                </div>
            </div>
            <hr>
        </div>
        <div id="results-page-view-smartphones" class="row-container view-mode-compact">
            <?php foreach($smartphones_results as $smartphone): ?>
                <div>
                    <span class="brand-name"><?php echo $smartphone->nomeProduttore; ?></span>
                    <span class="model-name"><?php echo $smartphone->nomeModello; ?></span>
                    <div class="smartphone-image-and-network-technology column-container">
                        <img src="<?php echo BP4Y_APP_ROOT_PATH . $smartphone->percorsoImmagineSmartphone; ?>" class="smartphone-image">
                        <?php
                            if($smartphone->_5G == 1)
                            {
                                $network_technology_image_path = BP4Y_APP_ROOT_PATH . "assets/icons/5g.svg";
                            }
                            elseif($smartphone->_4G_LTE == 1)
                            {
                                $network_technology_image_path = BP4Y_APP_ROOT_PATH . "assets/icons/4g_lte.svg";
                            }
                        ?>
                        <?php if(isset($network_technology_image)): ?>
                            <img src="<?php echo $network_technology_image_path; ?>" class="network-technology">
                        <?php endif; ?>
                    </div>
                    <img src="./assets/os-logos/android-logo.png" alt="<?php echo $smartphone->nomeSistemaOperativo; ?>" class="os-logo">

                    <p class="summarized-specs">
                        Schermo da <?php echo $smartphone->polliciSchermo; ?>&quot; • Peso <?php echo (float) $smartphone->peso; ?> g<br>
                        RAM <?php echo $smartphone->RAM; ?> GB • Mem. Interna da <?php echo $smartphone->capacitaMemoriaInterna; ?> GB <?php echo $smartphone->memoriaEspandibile == 1 ? "Espandibile" : ""; ?><br>
                        <?php echo $smartphone->dualSIM == "Sì" ? "DualSIM" : ""; ?> • Batteria da <?php echo $smartphone->capacitaBatteria; ?> mAh
                    </p>

                    <div class="general-specs">
                        <span><b>Dimensioni:</b> <?php echo $smartphone->lunghezza; ?>×<?php echo $smartphone->altezza; ?>×<?php echo $smartphone->spessore; ?> mm </span>
                        <span><b>Peso:</b> <?php echo (float) $smartphone->peso; ?> g</span>
                        <span><b>Resistenza all'acqua:</b> <?php echo $smartphone->resistenzaAcqua; ?></span>
                        <span><b>DualSIM:</b> <?php echo $smartphone->dualSIM; ?></span>
                        <span><b>Pennino:</b> <?php echo $smartphone->pennino == 1 ? "Sì" : "No"; ?></span>
                    </div>

                    <div class="categories-specs-wrapper">
                        <div class="column-container align-flex-start">
                            <div class="category-specs">
                                <div class="header row-container">
                                    <span>SCHERMO</span>
                                    <hr>
                                </div>
                                <span><b>Pollici:</b> <?php echo $smartphone->polliciSchermo; ?></span>
                                <span><b>Tipo:</b> <?php echo $smartphone->tipoSchermo; ?></span>
                                <span><b>Risoluzione:</b> <?php echo $smartphone->risoluzioneSchermo; ?> px</span>
                                <span><b>Densità Pixel:</b> <?php echo $smartphone->densitaPixelSchermo; ?> ppi</span>
                                <span><b>Refresh Rate:</b> <?php echo $smartphone->frequenzaAggiornamentoSchermo; ?> Hz</span>
                                <span><b>Protezione:</b> <?php echo $smartphone->protezioneSchermo; ?></span>
                            </div>
                            <div class="category-specs">
                                <div class="header row-container">
                                    <span>BATTERIA</span>
                                    <hr>
                                </div>
                                <span><b>Capacità:</b> <?php echo $smartphone->capacitaBatteria; ?> mAh</span>
                                <span><b>Tipo:</b> <?php echo $smartphone->tipoBatteria; ?></span>
                                <span><b>Ricarica Wireless:</b> <?php echo $smartphone->ricaricaWireless == 1 ? "Sì" : "No"; ?></span>
                            </div>
                        </div>
                        <div class="category-specs">
                            <div class="header row-container">
                                <span>MAIN BOARD</span>
                                <hr>
                            </div>
                            <span><b>Chipset:</b> <?php echo $smartphone->chipset; ?></span>
                            <span>
                                <b>Processori:</b><br>
                                <?php
                                    foreach(explode(" + ", $smartphone->processori) as $cpu_core_component)
                                    {
                                        echo "- " . $cpu_core_component . "<br>";
                                    }
                                ?>
                            </span>
                            <span><b>RAM:</b> 8 <?php echo $smartphone->RAM; ?></span>
                            <span><b>GPU:</b> <?php echo $smartphone->GPU; ?></span>
                            <span><b>Memoria Interna:</b> <?php echo $smartphone->capacitaMemoriaInterna; ?> GB</span>
                            <span><b>Memoria Espandibile:</b> <?php echo $smartphone->memoriaEspandibile == 1 ? "Sì" : "No"; ?></span>
                            <span><b>Jack Audio:</b> <?php echo $smartphone->jackAudio == 1 ? "Sì" : "No"; ?></span>
                            <span><b>Sensore di impronte digitali:</b> <?php echo $smartphone->sensoreImpronteDigitali == 1 ? "Sì" : "No"; ?></span>
                        </div>
                        <div class="column-container align-flex-start">
                            <div class="category-specs">
                                <div class="header row-container">
                                    <span>FOTOCAMERE POSTERIORI</span>
                                    <hr>
                                </div>
                                <span><b>Megapixels:</b> <?php echo $smartphone->mpFotocamerePosteriori; ?></span>
                                <span><b>Apertura Focale:</b> <?php echo $smartphone->aperturaFocaleFotocamerePosteriori; ?></span>
                                <span><b>Risoluzione Video:</b> <?php echo $smartphone->risoluzioneVideocameraPosteriore; ?></span>
                                <span><b>FPS Video:</b> <?php echo $smartphone->fpsVideocameraPosteriore; ?></span>
                                <span><b>Stabilizzazione ottica:</b> <?php echo $smartphone->stabilizzazioneOttica == 1 ? "Sì" : "No"; ?></span>
                            </div>
                            <div class="category-specs">
                                <div class="header row-container">
                                    <span>FOTOCAMERE FRONTALI</span>
                                    <hr>
                                </div>
                                <span><b>Megapixels:</b> <?php echo $smartphone->mpFotocamereFrontali; ?></span>
                                <span><b>Apertura Focale:</b> <?php echo $smartphone->aperturaFocaleFotocamereFrontali; ?></span>
                                <span><b>Risoluzione Video:</b> <?php echo $smartphone->risoluzioneVideocameraFrontale; ?></span>
                                <span><b>FPS Video:</b> <?php echo $smartphone->fpsVideocameraFrontale; ?></span>
                            </div>
                        </div>
                    </div>


                    <div class="column-container price-and-shop-container" style="grid-area: smartphone-price-and-shop-container;">
                        <span class="price"><?php echo $smartphone->prezzo; ?> &euro;</span>
                        <div style="display: flex; flex-direction: row; align-items: center; justify-content: center;">
                            <span style="margin: 0 1rem 0 0;">su</span>
                            <img src="<?php echo BP4Y_APP_ROOT_PATH . $smartphone->percorsoImmagineLogoNegozio; ?>" alt="<?php echo $smartphone->nomeNegozio; ?>" class="shop-image">
                        </div>
                        <a href="#"><span class="goto-shop-button row-container"><img src="./assets/icons/shop-bag.svg">VAI AL NEGOZIO</span></a>
                        <span style="margin: 1rem 0 0 0;">Oppure</span>
                        <button style="background-color: #FFF; border: none; padding: 0; color: #079CFF; font-family: 'Poppins'; font-size: 1em; font-weight: 600; cursor: pointer;">Visualizza altre X opzioni</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
<script>window.scrollTo({top: document.getElementById("results-page-intro-container").offsetTop, behavior: "smooth"});</script>