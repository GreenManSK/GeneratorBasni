<?php

/**
 * Najmenšia časť básne, ktorá sa postará o to, ako vlastne bude vypadať a kto čo s kým.
 *
 * @author Lukáš "GreenMan" Kurčík <lukas.kurcik@gmail.com>
 * @version 1.0
 */
class Vers {

    /**
     * Slovník pre tvorbu veršov
     * @var Slovnik
     */
    private $vocabulary;

    /**
     * @param Slovnik $vocabulary
     */
    public function __construct($vocabulary) {
        $this->vocabulary = $vocabulary;
    }

    /**
     * Ako písať návrh:
     * [typ slova] - nahradí za vybraný typ slova zo slovníku
     * [podstatne/pridavne:rod] - buď jeden z rodov m,z,s alebo p (množnočíselný!) alebo číslo(0-9), ktoré určí náhodný rod, ale pre každý výskyt čísla vždy rovnaký
     * (Slovo:10) - vypíše slovo so šancou 1:10
     * {[typ slova]/slovo/dalšie slovo} - vypíše náhodne jednu z troch variant
     * 
     * Vytvorí verš, alebo kľudne aj vetu pomocou jednoduchého návrhu v jednoduchom jazyku.
     * @param string $navrh Návrh vera
     * @param char $end Posledné písmeno veršu
     * @return string Verš
     */
    public function podlaNavrhu($navrh, $end = NULL) {
        $vers = trim($navrh);
        $voc = $this->vocabulary;
        $rody = $this->vocabulary->getRody();
        $zhody = array();


        /* Nahradenie [typ slova] na konci veršu aby vznikol rým */
        if ($end != NULL) {
            $vers = preg_replace_callback("~^(.*)\[([^\]]+?):(.?)\]$~", function ($match) use (&$voc, &$zhody, $rody, $end) {
                        $v = $voc->getVocabulary();
                        if (!isset($v[$match[2]]))
                            throw new Exception("Syntax error '" . $match[0] . "'.");
                        if (!in_array($match[2], $rody)) {
                            if (!isset($zhody[$match[3]]))
                                $zhody[$match[3]] = $rody[array_rand($rody)];
                            $match[3] = $zhody[$match[3]];
                        }
                        return $match[1] . $voc->getRandomWord($match[2], $end, $match[3]);
                    }, $vers);

            $vers = preg_replace_callback("~^(.*)\[(.+?)\]$~", function ($match) use (&$voc, $end) {
                        $v = $voc->getVocabulary();
                        if (!isset($v[$match[2]]))
                            throw new Exception("Syntax error '" . $match[0] . "'.");

                        return $match[1] . $voc->getRandomWord($match[2], $end);
                    }, $vers);
        }


        /* Zhodorodovače */
        $vers = preg_replace_callback("~\[([^\]]+?):(.+?)\]~", function ($match) use (&$voc, &$zhody, $rody) {
                    if (!in_array($match[2], $rody)) {
                        if (!isset($zhody[$match[2]]))
                            $zhody[$match[2]] = $rody[array_rand($rody)];
                        $match[2] = $zhody[$match[2]];
                    }
                    if ($match[1] == "podstatne") {
                        $w = $voc->getRandomWord("podstatne", NULL, $match[2]);
                    } elseif ($match[1] == "sloveso") {
                        $w = $voc->getRandomWord("sloveso", NULL, $match[2]);
                    } else {
                        /* Zmena rodu */
                        switch ($match[2]) {
                            case "m":
                                $c = "ý";
                                break;
                            case "z":
                                $c = "á";
                                break;
                            default:
                                $c = "é";
                                break;
                        }
                        $w = $voc->getRandomWord($match[1]);
                        $w = mb_substr($w, 0, -1) . $c;
                    }

                    return $w;
                }, $vers);

        /* Nahradenie [typ slova] */
        $vers = preg_replace_callback("~\[(.+?)\]~", function ($match) use (&$voc) {
                    $v = $voc->getVocabulary();
                    if (!isset($v[$match[1]]))
                        throw new Exception("Syntax error '" . $match[0] . "'.");
                    return $voc->getRandomWord($match[1]);
                }, $vers);

        /* Slová so šancou */
        $vers = preg_replace_callback("~\((.+?):(\d+)\)~", function ($match) {
                    $r = rand(0, $match[2]);
                    if ($r == 0)
                        return $match[1];
                    return NULL;
                }, $vers);

        /* Jedno slovo z viacerých */
        $vers = preg_replace_callback("~\{(.+?)\}~", function ($match) {
                    $parts = explode("/", $match[1]);
                    return $parts[array_rand($parts)];
                }, $vers);

        $vers = trim($vers);
        $vers = mb_strtoupper(mb_substr($vers, 0, 1)) . mb_substr($vers, 1, mb_strlen($vers));

        return $vers;
    }

}
