<?php

/**
 * Formulár na vytvorenie básne
 *
 * @author Lukáš "GreenMan" Kurčík <lukas.kurcik@gmail.com>
 * @version 1.0
 */
class Form {

    /**
     * Vypíše formulár
     * @return \Form
     */
    public function render() {
        echo '
                    <form method="get" action="">';
        $errors = $this->validate();
        if (is_array($errors)) {
            echo '<ul class="error">';
            foreach ($errors as $error) {
                echo '<li>';
                switch ($error) {
                    case "rhymes":
                        echo 'Nepovolený počet sloh.';
                        break;

                    case "type":
                        echo 'Neznámy typ básne.';
                        break;

                    case "rhyme":
                        echo 'Neznámy typ rýmu.';
                        break;
                    
                    default:
                        echo "Neznáma chyba. Ako si toto dokázal?";
                        break;
                }
                echo '</li>';
            }
            echo '</ul>';
        }
        echo '           <label for="rhymes"><strong>Počet sloh:</strong></label> <input id="rhymes" name="rhymes" type="number" min="1" value="'
        . (isset($_GET['rhymes']) ? (int) $_GET['rhymes'] : '1') . '" /><br />
                        <strong>Druh rýmu:</strong><br />
                        <input type="radio" id="aabb" name="rhyme" value="a"' . (isset($_GET['rhyme']) && $_GET['rhyme'] == "a" ? ' checked="checked"' : '') . '><label for="aabb">AABB</label><br />
                        <input type="radio" id="abba" name="rhyme" value="b"' . (isset($_GET['rhyme']) && $_GET['rhyme'] == "b" ? ' checked="checked"' : '') . '><label for="abba">ABBA</label><br />
                        <input type="radio" id="abab" name="rhyme" value="c"' . (isset($_GET['rhyme']) && $_GET['rhyme'] == "c" ? ' checked="checked"' : '') . '><label for="abab">ABAB</label><br />
                        <input type="radio" id="nnnn" name="rhyme" value="n"' . (isset($_GET['rhyme']) && $_GET['rhyme'] == "n" ? ' checked="checked"' : '') . '><label for="nnnn">Náhodný</label><br />
                        <strong>Typ básne:</strong><br />
                        <input type="radio" id="tr" name="_type" value="r"' . (isset($_GET['_type']) && $_GET['_type'] == "r" ? ' checked="checked"' : '') . '><label for="tr">Romantická - Pomôže vám pri vyjadrení citov k žene (3D/2D/Počítač)</label><br />
                        <input type="radio" id="tl" name="_type" value="l"' . (isset($_GET['_type']) && $_GET['_type'] == "l" ? ' checked="checked"' : '') . '><label for="tl">Lyrická - Nezmyselné veci s hlbokým významom</label><br />
                        <input type="radio" id="te" name="_type" value="e"' . (isset($_GET['_type']) && $_GET['_type'] == "e" ? ' checked="checked"' : '') . '><label for="te">Epická - Nejaký ten príbeh s ešte hlbším významom</label><br />
                        <input type="radio" id="tn" name="_type" value="n"' . (isset($_GET['_type']) && $_GET['_type'] == "n" ? ' checked="checked"' : '') . '><label for="tn">Náhodný - Pretože báseň, čo má zmysel je nuda.</label><br />
                        <input type="submit" class="button" name="generate" value="Vygeneruj umelecké dielo" />
                    </form>
            ';

        return $this;
    }

    /**
     * Zistí, či sú hodnoty vo formuláry správne
     * @return mixed Boolean ak sú správne, ak nie tak array s tým, čo nie je
     */
    public function validate() {
        $errors = array();

        if (!isset($_GET['rhymes']) || (isset($_GET['rhymes']) && (int) $_GET['rhymes'] <= 0)) {
            $errors[] = "rhymes";
        }

        if (!isset($_GET['_type']) || (isset($_GET['_type']) && !in_array((int) $_GET['_type'], array("e", "n", "l", "r")))) {
            $errors[] = "type";
        }

        if (!isset($_GET['rhyme']) || (isset($_GET['rhyme']) && !in_array((int) $_GET['rhyme'], array("aabb", "abba", "abab", "nnnn")))) {
            $errors[] = "rhyme";
        }

        if (count($errors) == 0)
            return TRUE;
        else
            return $errors;
    }
    
    /**
     * Vráti počet rýmov
     * @return int
     */
    public function getRhymes() {
        if (isset($_GET['rhymes']))
            return (int)$_GET['rhymes'];
        return 1;
    }
    
    public function getType() {
        if (isset($_GET['_type']))
            return $_GET['_type'];
        return "r";
    }

}
