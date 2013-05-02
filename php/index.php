<?php
/* Automatické načítavanie tried lebo sme lenivý */

function __autoload($class_name) {
    $name = GENERATOR_DIR . 'class/' . $class_name . '.php';
    if (file_exists($name))
        require_once $name;
}

$slovnik = new Slovnik;
$slovnik->addVocabulary(GENERATOR_DIR . "vocabulary.json")
        ->addVocabulary(GENERATOR_DIR . "real.json");

$basen = new Basen($slovnik);
echo '<h1>' . $basen->createName() . '</h1>';

$types = array("l" => Sloha::T_LYRIC, "e" => Sloha::T_EPIC, "r" => Sloha::T_LOVE, "n" => Sloha::T_RAND);

if (!isset($type))
    $type = $types["r"];

if (!isset($rhymes))
    $rhymes = 1;

$slohy = $basen->createBasen($rhymes, Sloha::R_AABB, $type);

foreach ($slohy as $sloha) {
    echo '<p>';
    foreach ($sloha as $vers) {
        echo $vers . '<br/>';
    }
    echo '</p>';
}