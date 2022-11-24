<?php

function convertString(string $a, string $b): string
{
    if (substr_count($a, $b) >= 2)
    {
        $length_b = strlen($b);
        $reverse_b = strrev($b);
        $position = strpos($a, $b, strpos($a, $b) + $length_b);

        return substr_replace($a, $reverse_b, $position, $length_b);
    }else
    {
        return $a;
    }
}



//echo convertString('string string string', 'str');