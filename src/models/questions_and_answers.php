<?php
    /**
     * Questions and Answers Data.
     * 
     * Provides informations about questions texts, along with their answers.
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
                0 => "NON MI INTERESSA",
                1 => "Android",
                2 => "iOS"
            )
        ),
        DISPLAY_DIMENSION_QUESTION_ORDER_NUMBER => array(
            "question_text" => "Quale deve essere la dimensione dello schermo?",
            "question_responses" => array(
                1 => 'PICCOLA',
                2 => 'MEDIA',
                3 => 'GRANDE'
            )
        ),
        MEMORY_CAPACITY_QUESTION_ORDER_NUMBER => array(
            "question_text" => "Quale vuoi che sia la dimensione della memoria interna?",
            "question_responses" => array(
                1 => '64 GB',
                2 => '128 GB',
                3 => '256 GB E OLTRE'
            )
        ),
        EXPANDABLE_MEMORY_QUESTION_ORDER_NUMBER => array(
            "question_text" => "Lo Smartphone deve avere la memoria espandibile?",
            "question_responses" => array(
                0 => 'NON MI INTERESSA',
                1 => 'SÌ'
            )
        ),
        LARGE_BATTERY_QUESTION_ORDER_NUMBER => array(
            "question_text" => "Lo Smartphone deve avere una batteria molto capiente?",
            "question_responses" => array(
                0 => 'NON MI INTERESSA',
                1 => 'SÌ'
            )
        ),
        DUALSIM_QUESTION_ORDER_NUMBER => array(
            "question_text" => "Lo Smartphone deve essere DualSIM?",
            "question_responses" => array(
                0 => 'NON MI INTERESSA',
                1 => 'SÌ'
            )
        )
    );
