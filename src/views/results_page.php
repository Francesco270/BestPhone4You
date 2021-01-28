<?php
    if( !defined("APPNAME") )
    {
        exit;
    }

    if(!defined("BP4Y_APP_ROOT_URI"))
    {
        define("BP4Y_APP_ROOT_URI", (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/');
    }

    // Save the session data only if the current request is not for a shared results page
    if(! $is_a_shared_results_page_request)
    {
        /**
         * Save the User Session data (all the questions asked with their associated answers)
         * in the Users Sessions table of the DB.
         */
    
        // Check if the randomly generated user session ID already exists in the Users Sessions table (lol, this is a really rare circumstance).
        // But if we are very very very very unlucky, generate another session ID, hoping that this time the generated ID does not already exist.
    
        $check_if_generated_user_session_id_already_exists_sql_query = "SELECT idSessione";
        $check_if_generated_user_session_id_already_exists_sql_query .= " FROM SESSIONI_UTENTI";
        $check_if_generated_user_session_id_already_exists_sql_query .= " WHERE idSessione = :user_session_id";
        $check_if_generated_user_session_id_already_exists_sql_query .= ";";
    
        do
        {
            $user_session_id = bin2hex(random_bytes(8));
            
            $check_if_generated_user_session_id_already_exists_sql_statement = $connection->prepare($check_if_generated_user_session_id_already_exists_sql_query);
            $check_if_generated_user_session_id_already_exists_sql_statement->bindValue(":user_session_id", $user_session_id, PDO::PARAM_STR);
            $check_if_generated_user_session_id_already_exists_sql_statement->execute();
            $check_if_generated_user_session_id_already_exists_sql_query_results = $check_if_generated_user_session_id_already_exists_sql_statement->fetchAll(PDO::FETCH_ASSOC);
    
        } while(!empty($check_if_generated_user_session_id_already_exists_sql_query_results));
    
        $user_session_data = json_encode($_SESSION["questions_and_answers"], JSON_NUMERIC_CHECK);
    
        $save_user_session_sql_query = "INSERT INTO SESSIONI_UTENTI(idSessione, datiSessione)";
        $save_user_session_sql_query .= " VALUES(:user_session_id, :user_session_data)";
        $save_user_session_sql_query .= ";";
    
        $save_user_session_sql_statement = $connection->prepare($save_user_session_sql_query);
        $save_user_session_sql_statement->bindValue(":user_session_id", $user_session_id, PDO::PARAM_STR);
        $save_user_session_sql_statement->bindValue(":user_session_data", $user_session_data, PDO::PARAM_STR);
        $save_user_session_sql_statement->execute();
    
        // Now the session data has been saved in the DB.
    
        $url_for_results_page_sharing = BP4Y_APP_ROOT_URI . "?usid=" . $user_session_id;
    }
    else
    {
        $url_for_results_page_sharing = BP4Y_APP_ROOT_URI . "?usid=" . $_GET["usid"];
    }

    $shops_data_sql_query = "SELECT * FROM NEGOZI";
    $shops_data_sql_statement = $connection->query($shops_data_sql_query);
    $shops_data_sql_query_results = $shops_data_sql_statement->fetchAll(PDO::FETCH_OBJ);

    $shops_data = array();
    foreach($shops_data_sql_query_results as $shop)
    {
        $shops_data[$shop->idNegozio] = array(
            "nomeNegozio" => $shop->nomeNegozio,
            "percorsoImmagineLogoNegozio" => $shop->percorsoImmagineLogoNegozio
        );
    }

    $smartphones_data_sql_query = "SELECT *";
    $smartphones_data_sql_query .= " FROM SMARTPHONES";
    $smartphones_data_sql_query .= " JOIN PREZZI_SMARTPHONES_NEGOZI ON SMARTPHONES.idSmartphone = PREZZI_SMARTPHONES_NEGOZI.idSmartphone";
    $smartphones_data_sql_query .= " JOIN SISTEMI_OPERATIVI ON SISTEMI_OPERATIVI.idSistemaOperativo = SMARTPHONES.idSistemaOperativo";
    $smartphones_data_sql_query .= " JOIN PRODUTTORI ON SMARTPHONES.idProduttore = PRODUTTORI.idProduttore";
    $smartphones_data_sql_query .= " JOIN NEGOZI ON NEGOZI.idNegozio = PREZZI_SMARTPHONES_NEGOZI.idNegozio";

    build_and_execute_sql_query($smartphones_data_sql_query, "ORDER BY prezzo ASC");

    $smartphones_data_sql_query_results = $statement->fetchAll(PDO::FETCH_OBJ);

    // Group the Smartphones along with their associated shops and prices (one Smartphone can be associated to more than one shop)
    $smartphones_data = array();
    foreach($smartphones_data_sql_query_results as $smartphone)
    {
        $s_id = $smartphone->idSmartphone;

        if( !array_key_exists($s_id, $smartphones_data) )
        {
            $smartphones_data[$s_id]["nomeModello"] = $smartphone->nomeModello;
            $smartphones_data[$s_id]["lunghezza"] = $smartphone->lunghezza;
            $smartphones_data[$s_id]["altezza"] = $smartphone->altezza;
            $smartphones_data[$s_id]["spessore"] = $smartphone->spessore;
            $smartphones_data[$s_id]["peso"] = $smartphone->peso;
            $smartphones_data[$s_id]["resistenzaAcqua"] = $smartphone->resistenzaAcqua;
            $smartphones_data[$s_id]["polliciSchermo"] = $smartphone->polliciSchermo;
            $smartphones_data[$s_id]["risoluzioneSchermo"] = $smartphone->risoluzioneSchermo;
            $smartphones_data[$s_id]["densitaPixelSchermo"] = $smartphone->densitaPixelSchermo;
            $smartphones_data[$s_id]["tipoSchermo"] = $smartphone->tipoSchermo;
            $smartphones_data[$s_id]["frequenzaAggiornamentoSchermo"] = $smartphone->frequenzaAggiornamentoSchermo;
            $smartphones_data[$s_id]["protezioneSchermo"] = $smartphone->protezioneSchermo;
            $smartphones_data[$s_id]["processori"] = $smartphone->processori;
            $smartphones_data[$s_id]["chipset"] = $smartphone->chipset;
            $smartphones_data[$s_id]["RAM"] = $smartphone->RAM;
            $smartphones_data[$s_id]["GPU"] = $smartphone->GPU;
            $smartphones_data[$s_id]["capacitaMemoriaInterna"] = $smartphone->capacitaMemoriaInterna;
            $smartphones_data[$s_id]["memoriaEspandibile"] = $smartphone->memoriaEspandibile;
            $smartphones_data[$s_id]["mpFotocamerePosteriori"] = $smartphone->mpFotocamerePosteriori;
            $smartphones_data[$s_id]["aperturaFocaleFotocamerePosteriori"] = $smartphone->aperturaFocaleFotocamerePosteriori;
            $smartphones_data[$s_id]["stabilizzazioneOttica"] = $smartphone->stabilizzazioneOttica;
            $smartphones_data[$s_id]["mpFotocamereFrontali"] = $smartphone->mpFotocamereFrontali;
            $smartphones_data[$s_id]["aperturaFocaleFotocamereFrontali"] = $smartphone->aperturaFocaleFotocamereFrontali;
            $smartphones_data[$s_id]["risoluzioneVideocameraPosteriore"] = $smartphone->risoluzioneVideocameraPosteriore;
            $smartphones_data[$s_id]["fpsVideocameraPosteriore"] = $smartphone->fpsVideocameraPosteriore;
            $smartphones_data[$s_id]["risoluzioneVideocameraFrontale"] = $smartphone->risoluzioneVideocameraFrontale;
            $smartphones_data[$s_id]["fpsVideocameraFrontale"] = $smartphone->fpsVideocameraFrontale;
            $smartphones_data[$s_id]["formFactorSIM"] = $smartphone->formFactorSIM;
            $smartphones_data[$s_id]["_4G_LTE"] = $smartphone->_4G_LTE;
            $smartphones_data[$s_id]["_5G"] = $smartphone->_5G;
            $smartphones_data[$s_id]["dualSIM"] = $smartphone->dualSIM;
            $smartphones_data[$s_id]["sensoreImpronteDigitali"] = $smartphone->sensoreImpronteDigitali;
            $smartphones_data[$s_id]["jackAudio"] = $smartphone->jackAudio;
            $smartphones_data[$s_id]["tipoBatteria"] = $smartphone->tipoBatteria;
            $smartphones_data[$s_id]["capacitaBatteria"] = $smartphone->capacitaBatteria;
            $smartphones_data[$s_id]["ricaricaWireless"] = $smartphone->ricaricaWireless;
            $smartphones_data[$s_id]["pennino"] = $smartphone->pennino;
            $smartphones_data[$s_id]["percorsoImmagineSmartphone"] = $smartphone->percorsoImmagineSmartphone;
            $smartphones_data[$s_id]["nomeSistemaOperativo"] = $smartphone->nomeSistemaOperativo;
            $smartphones_data[$s_id]["percorsoImmagineLogoSistemaOperativo"] = $smartphone->percorsoImmagineLogoSistemaOperativo;
            $smartphones_data[$s_id]["nomeProduttore"] = $smartphone->nomeProduttore;

            $smartphones_data[$s_id]["available_shops"] = array();
        }

        array_push($smartphones_data[$s_id]["available_shops"], array(
            "idNegozio" => $smartphone->idNegozio,
            "prezzo" => $smartphone->prezzo,
            "urlPaginaProdotto" => $smartphone->urlPaginaProdotto
        ));
    }

    $smartphones_count = count($smartphones_data);
?>

<div id="results-page-intro-container" class="column-container">
    <div class="row-container">
        <img src="./assets/icons/finish-flag.svg">
            <div>
                <h1>RICERCA COMPLETATA!</h1>
                <?php if($smartphones_count > 1): ?>
                    <p>Abbiamo trovato <strong><span id="results-page-smartphone-count-text"><?php echo $smartphones_count; ?></span> Smartphone</strong> per te</p>
                <?php elseif($smartphones_count == 1): ?>
                    <p>Abbiamo trovato <strong> LO Smartphone</strong> che fa per te!</p>
                <?php endif; ?>     
            </div>
        <img src="./assets/icons/finish-flag.svg" style="transform: rotateY(180deg);">
    </div>
    <p>Condividi il link a questa pagina oppure salvalo per tornare qui in un secondo momento.<br>Verrà eseguita automaticamente una nuova ricerca tenendo conto delle risposte che hai già dato.</p>
    <div id="results-page-intro-container-buttons-container" class="row-container" style="margin: 0.5rem 0 0 0;">
        <button id="results-page-copy-link-for-sharing-button" class="bp4y-button small primary" data-url-for-sharing="<?php echo $url_for_results_page_sharing; ?>"><img src="./assets/icons/copy.svg"><span>COPIA LINK PER LA CONDIVISIONE</span></button>
        <button id="results-page-review-questions-button" class="bp4y-button small secondary"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" fill="#2d3039"/></svg>RIVEDI LE DOMANDE</button>
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
    <?php if($smartphones_count > 1): ?>
        <div>
            <span>ORDINA PER PREZZO</span>
            <div class="row-container">
                <button class="price-sorting-mode-ascent row-container selected">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" style="transform: rotateZ(270deg);"><path d="M5.88 4.12L13.76 12l-7.88 7.88L8 22l10-10L8 2z"/></svg>
                    <span>Crescente</span>
                </button>
                <button class="price-sorting-mode-descent row-container">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" style="transform: rotateZ(90deg);"><path d="M5.88 4.12L13.76 12l-7.88 7.88L8 22l10-10L8 2z"/></svg>
                    <span>Decrescente</span>
                </button>
            </div>
        </div>
    <?php endif; ?>
    <hr>
</div>
<div id="results-page-view-smartphones" class="row-container view-mode-compact">
    <?php foreach($smartphones_data as $smartphone): ?>
        <div class="smartphone-card">
            <span class="brand-name"><?php echo $smartphone["nomeProduttore"]; ?></span>
            <span class="model-name"><?php echo $smartphone["nomeModello"]; ?></span>
            <div class="smartphone-image-and-network-technology column-container">
                <img src="<?php echo BP4Y_APP_ROOT_URI . $smartphone["percorsoImmagineSmartphone"]; ?>" class="smartphone-image">
                <?php
                    if($smartphone["_5G"] == 1)
                    {
                        $network_technology_image_path = BP4Y_APP_ROOT_URI . "assets/icons/5g.svg";
                    }
                    elseif($smartphone["_4G_LTE"] == 1)
                    {
                        $network_technology_image_path = BP4Y_APP_ROOT_URI . "assets/icons/4g_lte.svg";
                    }
                ?>
                <?php if(isset($network_technology_image_path)): ?>
                    <img src="<?php echo $network_technology_image_path; ?>" class="network-technology">
                <?php endif; ?>
            </div>
            <img src="<?php echo BP4Y_APP_ROOT_URI . $smartphone["percorsoImmagineLogoSistemaOperativo"]; ?>" alt="<?php echo $smartphone["nomeSistemaOperativo"]; ?>" class="os-logo">

            <p class="summarized-specs">
                Schermo da <?php echo $smartphone["polliciSchermo"]; ?>&quot; • Peso <?php echo (float) $smartphone["peso"]; ?> g<br>
                RAM <?php echo $smartphone["RAM"]; ?> GB • Mem. Interna da <?php echo $smartphone["capacitaMemoriaInterna"]; ?> GB <?php echo $smartphone["memoriaEspandibile"] == 1 ? "Espandibile" : ""; ?><br>
                <?php echo $smartphone["dualSIM"] == "Sì" ? "DualSIM" : ""; ?> • Batteria da <?php echo $smartphone["capacitaBatteria"]; ?> mAh
            </p>

            <div class="general-specs">
                <span><b>Dimensioni:</b> <?php echo $smartphone["lunghezza"]; ?>×<?php echo $smartphone["altezza"]; ?>×<?php echo $smartphone["spessore"]; ?> mm </span>
                <span><b>Peso:</b> <?php echo (float) $smartphone["peso"]; ?> g</span>
                <span><b>Resistenza all'acqua:</b> <?php echo $smartphone["resistenzaAcqua"]; ?></span>
                <span><b>DualSIM:</b> <?php echo $smartphone["dualSIM"]; ?></span>
                <span><b>Pennino:</b> <?php echo $smartphone["pennino"] == 1 ? "Sì" : "No"; ?></span>
            </div>

            <div class="categories-specs-wrapper">
                <div class="column-container align-flex-start">
                    <div class="category-specs">
                        <div class="header row-container">
                            <span>SCHERMO</span>
                            <hr>
                        </div>
                        <span><b>Pollici:</b> <?php echo $smartphone["polliciSchermo"]; ?></span>
                        <span><b>Tipo:</b> <?php echo $smartphone["tipoSchermo"]; ?></span>
                        <span><b>Risoluzione:</b> <?php echo $smartphone["risoluzioneSchermo"]; ?> px</span>
                        <span><b>Densità Pixel:</b> <?php echo $smartphone["densitaPixelSchermo"]; ?> ppi</span>
                        <span><b>Refresh Rate:</b> <?php echo $smartphone["frequenzaAggiornamentoSchermo"]; ?> Hz</span>
                        <span><b>Protezione:</b> <?php echo $smartphone["protezioneSchermo"]; ?></span>
                    </div>
                    <div class="category-specs">
                        <div class="header row-container">
                            <span>BATTERIA</span>
                            <hr>
                        </div>
                        <span><b>Capacità:</b> <?php echo $smartphone["capacitaBatteria"]; ?> mAh</span>
                        <span><b>Tipo:</b> <?php echo $smartphone["tipoBatteria"]; ?></span>
                        <span><b>Ricarica Wireless:</b> <?php echo $smartphone["ricaricaWireless"] == 1 ? "Sì" : "No"; ?></span>
                    </div>
                </div>
                <div class="category-specs">
                    <div class="header row-container">
                        <span>MAIN BOARD</span>
                        <hr>
                    </div>
                    <span><b>Chipset:</b> <?php echo $smartphone["chipset"]; ?></span>
                    <span>
                        <b>Processori:</b><br>
                        <?php
                            foreach(explode(" + ", $smartphone["processori"]) as $cpu_core_component)
                            {
                                echo "- " . $cpu_core_component . "<br>";
                            }
                        ?>
                    </span>
                    <span><b>RAM:</b> <?php echo $smartphone["RAM"]; ?> GB</span>
                    <span><b>GPU:</b> <?php echo $smartphone["GPU"]; ?></span>
                    <span><b>Memoria Interna:</b> <?php echo $smartphone["capacitaMemoriaInterna"]; ?> GB</span>
                    <span><b>Memoria Espandibile:</b> <?php echo $smartphone["memoriaEspandibile"] == 1 ? "Sì" : "No"; ?></span>
                    <span><b>Jack Audio:</b> <?php echo $smartphone["jackAudio == 1"] ? "Sì" : "No"; ?></span>
                    <span><b>Sensore di impronte digitali:</b> <?php echo $smartphone["sensoreImpronteDigitali == 1"] ? "Sì" : "No"; ?></span>
                </div>
                <div class="column-container align-flex-start" style="min-width: 200px;">
                    <div class="category-specs">
                        <div class="header row-container">
                            <span>FOTOCAMERE POSTERIORI</span>
                            <hr>
                        </div>
                        <span><b>Megapixels:</b> <?php echo $smartphone["mpFotocamerePosteriori"]; ?></span>
                        <span><b>Apertura Focale:</b> <?php echo $smartphone["aperturaFocaleFotocamerePosteriori"]; ?></span>
                        <span><b>Risoluzione Video:</b> <?php echo $smartphone["risoluzioneVideocameraPosteriore"]; ?></span>
                        <span><b>FPS Video:</b> <?php echo $smartphone["fpsVideocameraPosteriore"]; ?></span>
                        <span><b>Stabilizzazione ottica:</b> <?php echo $smartphone["stabilizzazioneOttica"] == 1 ? "Sì" : "No"; ?></span>
                    </div>
                    <div class="category-specs" style="min-width: 200px;">
                        <div class="header row-container">
                            <span>FOTOCAMERE FRONTALI</span>
                            <hr>
                        </div>
                        <span><b>Megapixels:</b> <?php echo $smartphone["mpFotocamereFrontali"]; ?></span>
                        <span><b>Apertura Focale:</b> <?php echo $smartphone["aperturaFocaleFotocamereFrontali"]; ?></span>
                        <span><b>Risoluzione Video:</b> <?php echo $smartphone["risoluzioneVideocameraFrontale"]; ?></span>
                        <span><b>FPS Video:</b> <?php echo $smartphone["fpsVideocameraFrontale"]; ?></span>
                    </div>
                </div>
            </div>

            <div class="column-container price-and-shop-container">
                <?php
                    $available_shops_count = count($smartphone["available_shops"]);

                    for($i = 0; $i < $available_shops_count; $i++)
                    {
                        if( ($i == 0) || (((float) $smartphone["available_shops"][$i]["prezzo"]) < $smartphone_lowest_price) )
                        {
                            $smartphone_lowest_price = (float) $smartphone["available_shops"][$i]["prezzo"];
                            $smartphone_shop_id_with_lowest_price = (float) $smartphone["available_shops"][$i]["idNegozio"];
                            $smartphone_shop_with_lowest_price_link = $smartphone["available_shops"][$i]["urlPaginaProdotto"];
                        }
                    }
                ?>
                <span class="price-text"><span class="price-value"><?php echo number_format($smartphone_lowest_price, 2, ",", ""); ?></span> &euro;</span>
                <div class="row-container">
                    <span style="margin: 0 0.5rem 0 0;">su</span>
                    <img src="<?php echo BP4Y_APP_ROOT_URI . $shops_data[$smartphone_shop_id_with_lowest_price]["percorsoImmagineLogoNegozio"]; ?>" alt="<?php echo $shops_data[$smartphone_shop_id_with_lowest_price]["nomeNegozio"]; ?>" class="shop-image">
                </div>
                <a href="<?php echo $smartphone_shop_with_lowest_price_link; ?>" target="_blank"><span class="goto-shop-button row-container"><img src="./assets/icons/shop-bag.svg">VAI AL NEGOZIO</span></a>
                <?php if($available_shops_count > 1): ?>
                    <span style="margin: 1rem 0 0 0;">Oppure</span>
                    <button data-shops-prices-and-links='<?php echo json_encode($smartphone["available_shops"], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK); ?>' class="view-all-shops-prices-button">Visualizza tutte le opzioni</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div id="results-page-footer" class="row-container">
    <div>
        <?php if($smartphones_count > 1): ?>
            <p>Gli Smartphone che ti abbiamo proposto ti hanno soddisfatto?<br>Hai riscontrato problemi?<br><button id="results-page-show-feedback-panel-cta">Inviaci un feedback</button>!</p>
        <?php else: ?>
            <p>Lo Smartphone che ti abbiamo proposto ti ha soddisfatto?<br>Hai riscontrato problemi?<br><button id="results-page-show-feedback-panel-cta">Inviaci un feedback</button>!</p>
        <?php endif; ?>
    </div>
    <div>
        <p>Se vuoi eseguire una nuova ricerca, puoi tornare alla <a href="<?php echo BP4Y_APP_ROOT_URI; ?>">Homepage</a>.</p>
    </div>
</div>

<div id="screen-fade-container" class="hidden">
    <div class="modal-dialog-box">
        <div id="questions-recap-container">
            <span class="heading">Riepilogo delle domande</span>
            <div class="body">
                <?php
                    $questions_counter = 1;
                    foreach($_SESSION["questions_and_answers"] as $question_id => $question_response_value)
                    {
                        $question_text = $questions_and_answers[$question_id]["question_text"];
                        if(count($questions_and_answers[$question_id]["question_responses"]) > 1)
                        {
                            $question_response_value_text = $questions_and_answers[$question_id]["question_responses"][$question_response_value];
                        }
                        else
                        {
                            $question_response_value_text = $questions_and_answers[$question_id]["question_responses"][0];
                        }
                        ?>
    
                        <?php if($questions_counter > 1): ?>
                            <hr>
                        <?php endif; ?>
                        <div class="question-and-answer-container">
                            <div class="question">
                                <div class="label"><strong>Domanda&nbsp;<?php echo $questions_counter; ?>:</strong></div>
                                <div class="text"><?php echo $question_text; ?></div>
                            </div>
                            <div class="answer">
                                <div class="label"><strong>Risposta:</strong></div>
                                <div class="text"><?php echo $question_response_value_text; ?></div>
                            </div>
                        </div>
    
                        <?php
                        $questions_counter++;
                    }
                ?>
            </div>
            <div class="buttons-container">
                <button class="bp4y-button bp4y-close-box-button" type="button">CHIUDI</button>
            </div>
        </div>
        <div id="all-smartphone-shops-prices-container">
            <span class="heading"></span>
            <div class="body">
                <span style="display: block;">Visualizza tutte le opzioni di acquisto per questo Smartphone</span>
                <div id="smartphone-purchase-options-wrapper">
                    <?php $shops_counter = 0; ?>
                    <?php foreach($shops_data as $shop_id => $shop_data): ?>
                        <?php if($shops_counter > 0): ?>
                            <hr>
                        <?php endif; ?>
                        <div data-shop-id="<?php echo $shop_id; ?>" class="smartphone-purchase-option-container hidden">
                            <span class="smartphone-price-text"><span class="smartphone-price-value"></span> &euro;</span>
                            <div class="column-container">
                                <img class="shop-image" src="<?php echo BP4Y_APP_ROOT_URI . $shop_data["percorsoImmagineLogoNegozio"]; ?>" alt="<?php echo $shop_data["nomeNegozio"]; ?>">
                                <a class="shop-product-link" href="#" target="_blank"><span class="row-container"><img src="./assets/icons/shop-bag.svg">VAI AL NEGOZIO</span></a>
                            </div>
                        </div>
                        <?php $shops_counter++; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="buttons-container">
                <button class="bp4y-button bp4y-close-box-button" type="button">CHIUDI</button>
            </div>
        </div>
        <div id="feedback-panel-container">
            <span class="heading">Invia un Feedback</span>
            <div class="body">
                <span style="display: block; margin: 0 0 1rem 0; text-align: left;">Questo modulo è completamente anonimo.<br>Non raccogliamo dati personali in alcun modo.</span>
                <textarea id="feedback-panel-textarea" cols="50" rows="5" placeholder="Scrivi qui il tuo feedback"></textarea>
            </div>
            <div class="buttons-container">
                <button id="feedback-panel-send-message-button" class="bp4y-button" type="button">CONFERMA</button>
                <button id="feedback-panel-cancel-button" class="bp4y-button secondary bp4y-close-box-button" type="button">ANNULLA</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="bp4y-token" value="<?php echo $_SESSION['csrf_token']; ?>">
<?php
    unset($_SESSION["questions_and_answers"]);
    unset($_SESSION["last_action"]);
    // The session will not be unset, because the csrf_token is still needed to perform validation on the feedbacks controller
    // (See the TODO in index.php for other details)
    // session_unset();