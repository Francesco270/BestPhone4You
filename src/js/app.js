"use strict"

const QUESTIONS_CONTROLLER_PATH = "controllers/jarvis.php";
const VIEW_WAIT_TIME = 1200; // Milliseconds

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

document.addEventListener("click", function(e)
{
    if(e.target)
    {
        if(e.target.matches(".bp4y-start-procedure-button"))
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
    }
});
