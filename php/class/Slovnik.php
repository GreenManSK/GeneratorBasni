<?php

/**
 * Slovník so všetkými slovami použiteľnými ku generovaniu básne
 *
 * @author Lukáš "GreenMan" Kurčík <lukas.kurcik@gmail.com>
 * @version 1.0
 * @todo: Mazanie slova
 */
class Slovnik {

    /**
     * Rody podsatných mies zo slovníku - mužský, ženský, stredný, množnočíselný
     * @var array 
     */
    private $rody = array("m", "z", "s", "p");

    /**
     * Slovník so všetkými použiteľnými slovami na tvorbu básne
     * @var array 
     */
    private $vocabulary = array();

    /**
     * Slovník so všetkými použiteľnými slovami rozdelený podľa písem na konci
     * @var array
     */
    private $vocabularyByLetters = array();

    /**
     * Načíta slovník z JSON súboru
     * @param string $filename
     * @return \Slovnik
     */
    public function addVocabulary($filename) {
        if (!file_exists($filename))
            throw new Exception("Nebol nájdený slovník '" . $filename . "'.");
        $json = file_get_contents($filename);
        $vocabulary = json_decode($json, TRUE);
        $this->addToNormalVocabulary($vocabulary)
                ->addToLetterVocabulary($vocabulary);

        return $this;
    }

    /**
     * Pridá do slovníku slová z poľa
     * @param array $vocabulary
     * @return \Slovnik
     */
    private function addToNormalVocabulary($vocabulary) {
        foreach ($vocabulary as $name => $type) {
            if ($name != "podstatne") {
                if (!isset($this->vocabulary[$name]))
                    $this->vocabulary[$name] = $type;
                else
                    $this->vocabulary[$name] = array_merge($this->vocabulary[$name], $type);
            } else {
                foreach ($vocabulary[$name] as $rod => $value) {
                    if (!isset($this->vocabulary[$name][$rod]))
                        $this->vocabulary[$name][$rod] = $value;
                    else
                        $this->vocabulary[$name][$rod] = array_merge($this->vocabulary[$name][$rod], $value);
                }
            }
        }
        return $this;
    }

    /**
     * Pridá do slovníku slová z poľa, podľa toho na aké písmeno sa končia
     * @param array $vocabulary
     * @return \Slovnik
     */
    private function addToLetterVocabulary($vocabulary) {
        foreach ($vocabulary as $name => $type) {
            if ($name != "podstatne") {
                foreach ($vocabulary[$name] as $word) {
                    if ($name == "sloveso") {
                        if (!is_array($word))
                            $word = array($word, "...");
                        $end = mb_substr($word[0], -1, mb_strlen($word[0]));
                    } else {
                        $end = mb_substr($word, -1, mb_strlen($word));
                    }


                    if (!isset($this->vocabularyByLetters[$name][$end]))
                        $this->vocabularyByLetters[$name][$end] = array();
                    $this->vocabularyByLetters[$name][$end][] = $word;
                }
            } else {
                foreach ($vocabulary[$name] as $rod => $value) {
                    foreach ($vocabulary[$name][$rod] as $word) {
                        $end = mb_substr($word, -1, mb_strlen($word));
                        if (!isset($this->vocabularyByLetters[$name][$rod][$end]))
                            $this->vocabularyByLetters[$name][$rod][$end] = array();
                        $this->vocabularyByLetters[$name][$rod][$end][] = $word;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Pridá do slovníku jedno nové slovo
     * @param string $type Slovný druh
     * @param mixed $word Slovo
     * @param string $rod V prípade podstatného mena jeho rod
     * @return \Slovnik
     * @throws Exception
     */
    public function addWord($type, $word, $rod = NULL) {
        if ($type == "podstatne") {
            if (!in_array($rod, $this->rody))
                throw new Exception("Slovník nepozná rod '" . $rod . "'.");
            if (!isset($this->vocabulary[$type][$rod])) {
                $this->vocabulary[$type][$rod] = array();
                $this->vocabularyByLetters[$type][$rod] = array();
            }
            $this->vocabulary[$type][$rod][] = $word;
            $end = mb_substr($word, -1, mb_strlen($word));
            if (!isset($this->vocabularyByLetters[$type][$rod][$end]))
                $this->vocabularyByLetters[$type][$rod][$end] = array();
            $this->vocabularyByLetters[$type][$rod][$end][] = $word;
        } else {
            if (!isset($this->vocabulary[$type])) {
                $this->vocabulary[$type] = array();
                $this->vocabularyByLetters[$type] = array();
            }
            if ($type == "sloveso" && !is_array($word)) {
                $word = array($word, "...");
                $end = mb_substr($word[0], -1, mb_strlen($word[0]));
            }
            else
                $end = mb_substr($word, -1, mb_strlen($word));
            $this->vocabulary[$type][] = $word;
            if (!isset($this->vocabularyByLetters[$type][$end]))
                $this->vocabularyByLetters[$type][$end] = array();
            $this->vocabularyByLetters[$type][$end][] = $word;
        }

        return $this;
    }

    /**
     * Zmaže dané slovo zo slovníku, ale len raz
     * @param string $type Slovný druh
     * @param string $word Slovo
     * @param string $rod V prípade podstatného mena jeho rod
     * @return \Slovnik
     */
    public function deleteWord($word, $type = NULL, $rod = NULL) {
        $end = mb_substr($word, -1, mb_strlen($word));
        if (isset($this->vocabulary[$type][$rod])) {
            $key = array_search($word, $this->vocabulary[$type][$rod]);
            unset($this->vocabulary[$type][$rod][$key]);
            unset($this->vocabularyByLetters[$type][$rod][$end][$key]);
        } elseif (isset($this->vocabulary[$type])) {
            $key = array_search($word, $this->vocabulary[$type]);
            unset($this->vocabulary[$type][$key]);
            unset($this->vocabularyByLetters[$type][$end][$key]);
        } else {
            foreach ($this->vocabulary as $type => $words) {
                if ($type != "podstatne") {
                    foreach ($words as $key => $value) {
                        if ($word == $value) {
                            unset($this->vocabulary[$type][$key]);
                            unset($this->vocabularyByLetters[$type][$end][$key]);
                            break 2;
                        }
                    }
                } else {
                    foreach ($words as $rod => $_words) {
                        foreach ($_words as $key => $value) {
                            if ($word == $value || ($word == "sloveso" && ($word == $value[0] || $word == $value[1]))) {
                                unset($this->vocabulary[$type][$rod][$key]);
                                unset($this->vocabularyByLetters[$type][$rod][$end][$key]);
                                break 2;
                            }
                        }
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Vráti slovo daného typu
     * @param string $type Typ slova
     * @param char $end Požadované písmeno na konci slova
     * @param string $rod Ak ide o podstatné meno tak jeho rod
     * @param bool $deleteAfter Zmazať slovo zo slovníka
     * @return string
     */
    public function getRandomWord($type, $end = NULL, $rod = NULL, $deleteAfter = FALSE) {
        if (!isset($this->vocabulary[$type]) || ($rod != NULL && $type == "podstatne" && !isset($this->vocabulary[$type][$rod])) || count($this->vocabulary[$type]) == 0)
            return "...";

        if ($type == "podstatne") {
            if ($rod == NULL)
                $rod = $this->rody[array_rand($this->rody)];
            elseif (!in_array($rod, $this->rody))
                throw new Exception("Neznámi rod '" . $rod . "'.");
            if ($end != NULL) {
                $rody = $this->rody;
                for ($i = 0; $i < 5; $i++) {
                    if ($i == 4)
                        return "..." . $end;
                    $r = array_rand($rody);
                    $rod = $rody[$r];
                    if (isset($this->vocabularyByLetters[$type][$rod][$end]) && count($this->vocabularyByLetters[$type][$rod][$end]) != 0) {
                        $word = $this->vocabularyByLetters[$type][$rod][$end][array_rand($this->vocabularyByLetters[$type][$rod][$end])];
                        break;
                    }
                    unset($rody[$r]);
                }
            }
            else
                $word = $this->vocabulary[$type][$rod][array_rand($this->vocabulary[$type][$rod])];
        } else {
            if ($end != NULL && (!isset($this->vocabularyByLetters[$type][$end]) || count($this->vocabularyByLetters[$type][$end]) == 0))
                return "..." . $end;
            if ($end != NULL) {
                if ($type != "sloveso")
                    $word = $this->vocabularyByLetters[$type][$end][array_rand($this->vocabularyByLetters[$type][$end])];
                else
                    $word = $this->vocabularyByLetters[$type][$end][array_rand($this->vocabularyByLetters[$type][$end])][$rod == "p" ? 1 : 0];
            } else {
                if ($type != "sloveso")
                    $word = $this->vocabulary[$type][array_rand($this->vocabulary[$type])];
                else
                    $word = $this->vocabulary[$type][array_rand($this->vocabulary[$type])][$rod == "p" ? 1 : 0];
            }
        }

        if ($deleteAfter)
            $this->deleteWord($word, $type, $rod);

        return $word;
    }

    /**
     * Vráti slovník
     * @return array
     */
    public function getVocabulary() {
        return $this->vocabulary;
    }

    /**
     * Vráti slovník so slovami roztriedenými podľa posledného písmena
     * @return array
     */
    public function getVocabularyByLetters() {
        return $this->vocabularyByLetters;
    }

    /**
     * Vráti rody podstatných mien
     * @return array
     */
    public function getRody() {
        return $this->rody;
    }

}