<?php

if (file_exists("Form.php"))
    require_once 'Form.php';

$form = new Form;

if (is_array($form->validate())) {
    $form->render();
} else {
    $rhymes = $form->getRhymes();
    $type = $form->getType();
    if (file_exists(GENERATOR_DIR . 'index.php'))
        require GENERATOR_DIR . 'index.php';
}