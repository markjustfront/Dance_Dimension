<?php
function gameValidation($gameFile, $songDuration) {
    $file = $gameFile;
    
    try {
        
        $file = explode(PHP_EOL, $file);
        if (count($file) < 2) {
            throw new Exception("Fletxes no trobades");
        }
        $nArrows = intval($file[0]);
        

        if (!is_int($nArrows)) {
            throw new Exception("Fletxes no és un número");
        }
        unset($file[0]);
        $linies = [];
        $regex = "/(37|38|39|40)\s#\s[0-9]*(\.[0-9]+)?\s#\s[0-9]*(\.[0-9]+)?/";
        foreach ($file as $content) {
            if (preg_match($regex, $content)) {
                $linia = explode(" # ", $content);
                if ($linia[1] >= $linia[2]) {
                    throw new Exception("Aparicio més tard que desaparicio");
                }
                if ($linia[2] > $songDuration) {
                    throw new Exception("Desaparicio més tard que durada de la cançó");
                }
                if ($linia[1] < 0) {
                    throw new Exception("Aparicio més aviat que 0");
                }
                if (!empty($linies) && $linia[1] < end($linies)[1]) {
                    throw new Exception("Aparicio abans de l'anterior");
                }
                $linies[] = $linia;
            } else {
                throw new Exception("Linia no segueix el format");
            }
        }

        if (sizeof($linies) != $nArrows) {
            throw new Exception("Nombre de fletxes incorrecte");
        }
        $linies = json_encode($linies);
        return true;


    } catch (Exception $e) {
        return false;
    }
}
