<?php

/**
 * Trieda ktorá sa stará o vygerovanie slohy podľa danej témy a druhu verša.
 *
 * @author Lukáš "GreenMan" Kurčík <lukas.kurcik@gmail.com>
 * @version 1.0
 */
class Sloha {
    /* Rýmy */

    const R_AABB = 1, R_ABAB = 2, R_ABBA = 3, R_RAND = 0;

    /* Typy */
    const T_LYRIC = 1, T_EPIC = 2, T_LOVE = 3, T_RAND = 0;

    /**
     * Typy viet z ktorých sa budú skladať verše
     * @var array
     */
    private $kostra = array(
        "normal" => array(
            "miesto" => "(Tam :2)kde ([pridavne:1]:2) [podstatne:1] [sloveso:1] [pridavne:o] [podstatne]",
            "popis" => "[podstatne] [slov_pri] {[prirovnanie]/ ([pridavne]:4) [podstatne]}",
            "cinnost" => "[pridavne:s] je [cinnost] [podstatne]",
            "konstatovanie" => "[podstatne] jak {[prirovnanie]/ ([pridavne]:4) [podstatne]} ([trpne]:5)",
            "popisMiesto" => "{Tu/Tam/Hentuto} [pridavne:s] [miesto] [slov_pri] [podstatne]",
            "vec" => "[pridavne:1] [podstatne:1]",),
        "love" => array(
            "siAko1" => "(Ty:3) si ako [pridavne:1] [podstatne:1]",
//            "siAko2" => "(Ty:3) si ako [prirovnanie],",
            "milujemAko1" => "Milujem ťa ako [pridavne:1] [podstatne:1]",
            "siTak1" => "Si tak [pridavne:z] ako [podstatne]",
            "siTak2" => "Si tak ako [prirovnanie] [pridavne:z]",
            "milujemKeď" => "Milujem keď [sloveso]š [podstatne]",
            "krasna" => "Si krásna ako [pridavne:1] [podstatne:1]"
        )
    );

    /**
     * Druh rýmu, jeden z druhov konštantne definovaných
     * @var int
     */
    private $rhyme;

    /**
     * Druh básne, jeden s typov konštantne definovaných
     * @var int
     */
    private $type;

    /**
     * Slovník so všetkými použiteľnými slovami na tvorbu básne
     * @var Slovnik
     */
    private $vocabulary;

    /**
     * Vytvorý novú štvorveršovú slohu
     * @param int $rhyme
     * @param int $type
     * @param Slovnik $vocabulary
     */
    public function __construct($rhyme, $type, $vocabulary) {
        $this->setRhyme($rhyme);
        $this->setType($type);
        $this->vocabulary = $vocabulary;
    }

    /**
     * Vygeneruje jednu úžasnú 4-veršovú slohu
     * @return array
     */
    public function generate() {
        if ($this->rhyme == self::R_RAND)
            $this->rhyme = rand(1, 3);

        $verse = $this->getVerse();

        $voc = clone $this->vocabulary;
        $v = new Vers($voc);

        $c = 10;
        if ($this->rhyme == self::R_AABB) {
            for ($i = 0; $i < $c; $i++) {
                $verse[0] = $v->podlaNavrhu($verse[0]);
                $l = mb_substr($verse[0], -1, mb_strlen($verse[0]));
                $l = $l == "," ? NULL : $l;
                $verse[1] = $v->podlaNavrhu($verse[1], $l);
                $last = mb_substr($verse[1], -2, mb_strlen($verse[1]) - 2);
                if ($last != ".")
                    break;
            }
            for ($i = 0; $i < $c; $i++) {
                $verse[2] = $v->podlaNavrhu($verse[2]);
                $l = mb_substr($verse[2], -1, mb_strlen($verse[2]));
                $l = $l == "," ? NULL : $l;
                $verse[3] = $v->podlaNavrhu($verse[3], $l);
                $last = mb_substr($verse[3], -2, mb_strlen($verse[3]) - 2);
                if ($last != ".")
                    break;
            }
        } elseif ($this->rhyme == self::R_ABAB) {
            for ($i = 0; $i < $c; $i++) {
                $verse[0] = $v->podlaNavrhu($verse[0]);
                $l = mb_substr($verse[0], -1, mb_strlen($verse[0]));
                $l = $l == "," ? NULL : $l;
                $verse[2] = $v->podlaNavrhu($verse[2], $l);
                $last = mb_substr($verse[2], -2, mb_strlen($verse[2]) - 2);
                if ($last != ".")
                    break;
            }
            for ($i = 0; $i < $c; $i++) {
                $verse[1] = $v->podlaNavrhu($verse[1]);
                $l = mb_substr($verse[1], -1, mb_strlen($verse[1]));
                $l = $l == "," ? NULL : $l;
                $verse[3] = $v->podlaNavrhu($verse[3], $l);
                $last = mb_substr($verse[3], -2, mb_strlen($verse[3]) - 2);
                if ($last != ".")
                    break;
            }
        } elseif ($this->rhyme == self::R_ABBA) {
            for ($i = 0; $i < $c; $i++) {
                $verse[0] = $v->podlaNavrhu($verse[0]);
                $l = mb_substr($verse[0], -1, mb_strlen($verse[0]));
                $l = $l == "," ? NULL : $l;
                $verse[3] = $v->podlaNavrhu($verse[3], $l);
                $last = mb_substr($verse[3], -2, mb_strlen($verse[3]) - 2);
                if ($last != ".")
                    break;
            }
            for ($i = 0; $i < $c; $i++) {
                $verse[1] = $v->podlaNavrhu($verse[1]);
                $l = mb_substr($verse[1], -1, mb_strlen($verse[1]));
                $l = $l == "," ? NULL : $l;
                $verse[2] = $v->podlaNavrhu($verse[2], $l);
                $last = mb_substr($verse[3], -2, mb_strlen($verse[3]) - 2);
                if ($last != ".")
                    break;
            }
        }

        return $verse;
    }

    /**
     * Vráti kostu pre verše tejto slohy
     * @return array
     */
    public function getVerse() {
        if ($this->type == self::T_RAND)
            $this->type = rand(1, 3);
        $verse = array('', '', '', '');

        if ($this->type == self::T_LOVE)
            $kostra = $this->kostra['love'];
        elseif ($this->type == self::T_EPIC) {
            $kostra = $this->kostra['normal'];
            unset($kostra["vec"]);
        } elseif ($this->type == self::T_LYRIC) {
            $kostra = $this->kostra['normal'];
            unset($kostra["cinnost"]);
            unset($kostra["popis"]);
        }

        for ($i = 0; $i < 4; $i++) {
            $key = array_rand($kostra);

            $verse[$i] = $kostra[array_rand($kostra)];
        }

        return $verse;
    }

    /**
     * Zmení typ rýmu
     * @param type $rhyme
     * @return \Sloha
     */
    public function setRhyme($rhyme) {
        $a = array(self::R_AABB, self::R_ABAB, self::R_ABBA);
        if (!in_array($rhyme, $a))
            $rhyme = self::R_RAND;
        $this->rhyme = $rhyme;
        return $this;
    }

    /**
     * Zmení typ básne
     * @param type $type
     * @return \Sloha
     */
    public function setType($type) {
        $a = array(self::T_EPIC, self::T_LOVE, self::T_LYRIC);
        if (!in_array($type, $a))
            $type = self::T_RAND;
        $this->type = $type;
        return $this;
    }

}