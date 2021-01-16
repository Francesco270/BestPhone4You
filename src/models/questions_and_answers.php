<?php
    /**
     * Questions and Answers Data.
     * 
     * Provides informations about questions texts, along with their answers,
     * in a manner that it's possible to display the questions answered on
     * the Results Page, with their repective answers.
     */

    $questions_and_answers = array(
        BUDGET_QUESTION_ORDER_NUMBER => array(
            "question_text" => "Quanto vorresti spendere, al massimo?",
            "question_responses" => array(
                0 => (string) $_SESSION["questions_and_answers"][BUDGET_QUESTION_ORDER_NUMBER] . "€"
            )
        ),
        OS_QUESTION_ORDER_NUMBER => array(
            "question_text" => "Quale Sistema Operativo vorresti usare?",
            "question_responses" => array(
                0 => "Non mi interessa",
                1 => "Android",
                2 => "iOS"
            )
        ),
        DISPLAY_DIMENSION_QUESTION_ORDER_NUMBER => array(
            "question_text" => "Quale deve essere la dimensione dello schermo?",
            "question_responses" => array(
                1 => 'Piccola',
                2 => 'Media',
                3 => 'Grande'
            )
        ),
        MEMORY_CAPACITY_QUESTION_ORDER_NUMBER => array(
            "question_text" => "Quale vuoi che sia la dimensione della memoria interna?",
            "question_responses" => array(
                1 => '64 GB',
                2 => '128 GB',
                3 => '256 GB e oltre'
            )
        ),
        EXPANDABLE_MEMORY_QUESTION_ORDER_NUMBER => array(
            "question_text" => "Lo Smartphone deve avere la memoria espandibile?",
            "question_responses" => array(
                0 => 'Non mi interessa',
                1 => 'Sì'
            )
        )
    );
