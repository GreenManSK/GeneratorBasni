<?php

/* Nette lebo ho máme radi */
require 'nette.min.php';

/* Automatické načítavanie tried lebo sme lenivý */

function __autoload($class_name) {
    $name = 'class/' . $class_name . '.php';
    if (file_exists($name))
        include_once $name;
}

$slovnik = new Slovnik;
$slovnik->addVocabulary("vocabulary.json");

$basen = new Basen($slovnik);
echo '<h1>' . $basen->createName() . '</h1>';
$slohy = $basen->createBasen(10, Sloha::R_RAND, Sloha::T_RAND);

foreach ($slohy as $sloha) {
    echo '<p>';
    foreach ($sloha as $vers) {
        echo $vers . '<br/>';
    }
    echo '</p>';
} 

//$sloha = new Sloha(Sloha::R_AABB, Sloha::T_LOVE, $slovnik);
//dump($sloha->generate());
//$sloha = new Sloha(Sloha::R_ABAB, Sloha::T_LYRIC, $slovnik);
//dump($sloha->generate());
//$sloha = new Sloha(Sloha::R_ABBA, Sloha::T_EPIC, $slovnik);
//dump($sloha->generate());
//$vers = new Vers($slovnik);
//dump($vers->podlaNavrhu("[pridavne:3] [podstatne:p] [podstatne:3] Vypíš text a [pridavne] -(pjů:1)-, -([slov_pod]:1)- [slov_pod] [podstatne] a nakoniec <br />[prirovnanie]", "n"));