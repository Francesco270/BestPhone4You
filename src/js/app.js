"use strict"

const QUESTIONS_CONTROLLER_PATH = "controllers/jarvis.php";
const FEEDBACKS_CONTROLLER_PATH = "controllers/feedbacks.php";
const VIEW_WAIT_TIME = 1200; // Milliseconds
const SORT_MODE_ASCENT = 0;
const SORT_MODE_DESCENT = 1;

function updatePriceSliderValueLabel()
{
    let priceSliderValue = document.getElementById("price-range-slider").value;
    document.getElementById("price-range-slider-value-label").innerText = priceSliderValue + "\u20AC";
    document.querySelector(".bp4y-response-confirm-button").setAttribute("data-response-value", priceSliderValue);
}

function refreshEventListeners()
{
    if(document.getElementById("price-range-slider"))
    {
        document.getElementById("price-range-slider").addEventListener("input", updatePriceSliderValueLabel);
    }
}

function disableViewButtons()
{
    document.querySelectorAll("#bp4y-start-procedure-button, .bp4y-response-confirm-button, #bp4y-back-button").forEach(function(element)
    {
        element.disabled = true;
    });
}

function queryQuestionController(action, responseValue = null)
{
    disableViewButtons();
            
    document.getElementById("application-view-container").classList.add("perform-view-animation");
    
    setTimeout(
        function()
        { 
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() 
            {
                if(this.readyState == 4 && this.status == 200) 
                {
                    if(this.response)
                    {
                        document.getElementById("application-view-container").innerHTML = this.response;
                        refreshEventListeners();

                        // Make the application view visible again
                        document.getElementById("application-view-container").classList.remove("perform-view-animation");

                        if(document.getElementById("results-page-intro-container"))
                        {
                            window.scrollTo({top: document.getElementById("results-page-intro-container").offsetTop, behavior: "smooth"});
                        }
                    }
                }
            };

            let bp4yToken = String(document.getElementById("bp4y-token").value);

            document.getElementById("application-view-container").textContent = "";

            xhttp.open("POST", QUESTIONS_CONTROLLER_PATH, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            let requestParametersString = "action=" + action + "&" +
                                            "token=" + bp4yToken;

            if(action == "next_question")
            {
                requestParametersString = requestParametersString + "&" + 
                                            "response_value=" + responseValue;
            }

            xhttp.send(requestParametersString);
        },
    VIEW_WAIT_TIME);
}

/**
 * Sort the Smartphone cards in the results page view.
 * 
 * @param {int} mode: The sorting mode.
 *                      0 [SORT_MODE_ASCENT]: Ascent mode (from the Lower Price to the Higher Price)
 *                      1 [SORT_MODE_DESCENT]: Descent mode (from the Higher Price to the Lower Price)
 */
function sortSmartphonesResultsView(mode)
{
    // Number each card element before starting to sort
    let smartphonesCardsIds = [];
    let smartphoneCardIdIndex = 0;
    document.querySelectorAll("#results-page-view-smartphones > div").forEach(function(smartphoneCardElement)
    {
        smartphonesCardsIds.push(smartphoneCardIdIndex);
        smartphoneCardElement.setAttribute("data-smartphone-card-id", smartphoneCardIdIndex);
        smartphoneCardIdIndex++;
    });

    for(let i = 0; i < smartphonesCardsIds.length - 1; i++)
    {
        let s = i;

        for(let j = i + 1; j < smartphonesCardsIds.length; j++)
        {
            let a = parseFloat(document.querySelector("#results-page-view-smartphones > div[data-smartphone-card-id='" + smartphonesCardsIds[s] + "'] .price-value").innerText);
            let b = parseFloat(document.querySelector("#results-page-view-smartphones > div[data-smartphone-card-id='" + smartphonesCardsIds[j] + "'] .price-value").innerText);

            if(mode == 0)
            {
                if(a > b)
                {
                    s = j;
                }
            }
            else if(mode == 1)
            {
                if(a < b)
                {
                    s = j;
                } 
            }
        }

        if(s != i)
        {
            let temp = smartphonesCardsIds[s];
            smartphonesCardsIds[s] = smartphonesCardsIds[i];
            smartphonesCardsIds[i] = temp;
        }
    }

    smartphonesCardsIds.forEach(function(smartphoneCardId)
    {
        document.getElementById("results-page-view-smartphones").appendChild(document.querySelector("#results-page-view-smartphones > div[data-smartphone-card-id='" + smartphoneCardId + "']"));
    });
}

document.addEventListener("click", function(e)
{
    if(e.target)
    {
        if(e.target.matches("#bp4y-start-procedure-button"))
        {
            queryQuestionController("start_procedure");
        }
        else if(e.target.matches(".bp4y-response-confirm-button"))
        {
            let responseValue = String(e.target.getAttribute("data-response-value"));
            queryQuestionController("next_question", responseValue);
        }
        else if(e.target.id == "bp4y-back-button")
        {
            queryQuestionController("previous_question");
        }
        else if(e.target.id == "bp4y-resume-procedure-button")
        {
            queryQuestionController("resume_procedure");
        }
        else if(e.target.matches(".view-mode-compact-button"))
        {
            document.querySelector("#results-page-view-options-rows .view-mode-complete-button").classList.remove("selected");
            e.target.classList.add("selected");

            document.querySelector("#results-page-view-smartphones").classList.remove("view-mode-complete");
            document.querySelector("#results-page-view-smartphones").classList.add("view-mode-compact");
        }
        else if(e.target.matches(".view-mode-complete-button"))
        {
            document.querySelector("#results-page-view-options-rows .view-mode-compact-button").classList.remove("selected");
            e.target.classList.add("selected");

            document.querySelector("#results-page-view-smartphones").classList.remove("view-mode-compact");
            document.querySelector("#results-page-view-smartphones").classList.add("view-mode-complete");
        }
        else if(e.target.matches(".price-sorting-mode-ascent"))
        {
            document.querySelector("#results-page-view-options-rows .price-sorting-mode-descent").classList.remove("selected");
            sortSmartphonesResultsView(SORT_MODE_ASCENT);
            e.target.classList.add("selected");
        }
        else if(e.target.matches(".price-sorting-mode-descent"))
        {
            document.querySelector("#results-page-view-options-rows .price-sorting-mode-ascent").classList.remove("selected");
            sortSmartphonesResultsView(SORT_MODE_DESCENT);
            e.target.classList.add("selected");
        }
        else if(e.target.id == "results-page-copy-link-for-sharing-button")
        {
            var textArea = document.createElement("textarea");
            textArea.value = e.target.dataset.urlForSharing;

            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            document.execCommand('copy');
            textArea.remove();

            // alert("Il link per condividere questa pagina è stato copiato negli appunti.");
            e.target.querySelector("span").innerText = "LINK COPIATO NEGLI APPUNTI!";
        }
        else if(e.target.id == "results-page-review-questions-button")
        {
            document.getElementById("screen-fade-container").classList.remove("hidden");
            document.querySelector("#screen-fade-container .modal-dialog-box").className = "modal-dialog-box show-questions-recap";
        }
        else if(e.target.id == "results-page-show-feedback-panel-cta")
        {
            document.getElementById("screen-fade-container").classList.remove("hidden");
            document.querySelector("#screen-fade-container .modal-dialog-box").className = "modal-dialog-box show-feedback-panel";
        }
        else if(e.target.id == "feedback-panel-send-message-button")
        {
            if(document.getElementById("feedback-panel-textarea").value != "")
            {
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() 
                {
                    if(this.readyState == 4 && this.status == 200) 
                    {
                        if(this.response)
                        {
                            alert("Il tuo feedback è stato inviato. Grazie!");
                            
                        }
                        else
                        {
                            alert("Oops, qualcosa è andato storto durante l'invio del feedback.\nRiprova più tardi.");
                        }
                        
                        document.getElementById("screen-fade-container").classList.add("hidden");
                        e.target.disabled = false;
                        document.getElementById("feedback-panel-textarea").value = "";
                    }
                };

                let feedbackMessage = document.getElementById("feedback-panel-textarea").value;
                let bp4yToken = String(document.getElementById("bp4y-token").value);

                xhttp.open("POST", FEEDBACKS_CONTROLLER_PATH, true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                let requestParametersString = "feedback_message=" + feedbackMessage + "&" +
                                                "token=" + bp4yToken;

                xhttp.send(requestParametersString);
                e.target.disabled = true;
            }
            else
            {
                alert("ERRORE: Non puoi inviare un feedback vuoto...");
            }
        }
        else if(e.target.id == "feedback-panel-cancel-button")
        {
            document.getElementById("feedback-panel-textarea").value = "";
        }
        else if(e.target.matches(".view-all-shops-prices-button"))
        {
            document.querySelectorAll("#all-smartphone-shops-prices-container .smartphone-purchase-option-container").forEach(function(purchaseOption)
            {
                purchaseOption.classList.add("hidden");
                purchaseOption.querySelector(".shop-product-link").href = "#";
            });

            document.getElementById("screen-fade-container").classList.remove("hidden");
            document.querySelector("#screen-fade-container .modal-dialog-box").className = "modal-dialog-box show-all-smartphone-shops-prices";

            let brandName = e.target.closest(".smartphone-card").querySelector(".brand-name").innerText;
            let modelName = e.target.closest(".smartphone-card").querySelector(".model-name").innerText;

            document.querySelector("#all-smartphone-shops-prices-container .heading").innerText = brandName + " " + modelName;

            let shopsPricesAndLinks = JSON.parse(e.target.dataset.shopsPricesAndLinks);

            shopsPricesAndLinks.forEach(function(shopPriceAndLink)
            {
                let purchaseOptionContainer = document.querySelector(".smartphone-purchase-option-container[data-shop-id='" + shopPriceAndLink.idNegozio + "']");

                purchaseOptionContainer.querySelector(".smartphone-price-value").innerText = shopPriceAndLink.prezzo.toFixed(2).replace(".", ",");
                purchaseOptionContainer.querySelector(".shop-product-link").href = shopPriceAndLink.urlPaginaProdotto;
                
                purchaseOptionContainer.classList.remove("hidden");
            });
        }

        if(e.target.matches(".bp4y-close-box-button"))
        {
            document.getElementById("screen-fade-container").classList.add("hidden");
        }
    }
});
