<?php

/**
 * Trieda ktorá sa stará o získanie slovníku a poskladanie jednotlivých sloh dokopy.
 *
 * @author Lukáš "GreenMan" Kurčík <lukas.kurcik@gmail.com>
 * @version 1.0
 */
class Basen {

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
     * Vytvorý pre báseň úžasné meno
     * @return string
     */
    public function createName() {
        $voc = clone $this->vocabulary;
        $v = new Vers($voc);

        $titles = array(
            "[podstatne:1] [pridavne:1]",
            "[pridavne:1] [podstatne:1]",
            "[podstatne] [slov_pri] [podstatne]",
            "[koniec]"
        );

        return $v->podlaNavrhu($titles[array_rand($titles)]);
    }

    /**
     * Vytvorí krásnu vysoko zmyselnú báseň
     * @param int $length Počet sloh básne
     * @param int $rhyme Druh rýmu
     * @param int $type Typ basne
     * @return array Báseň
     */
    public function createBasen($length = 1, $rhyme = Sloha::R_AABB, $type = Sloha::T_LYRIC) {
        if ($length < 1)
            $length = 1;
        $basen = array();

        for ($i=0;$i<$length;$i++) {
            $sloha = new Sloha($rhyme, $type, $this->vocabulary);
            $basen[] = $sloha->generate();
        }
        
        return $basen;
    }

}
