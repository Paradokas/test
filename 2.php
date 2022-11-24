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



/**
 * @throws Exception
 */

function mySortForKey(array &$a, string $b): bool
{
    for ($i = 0; $i < count($a); $i++)
    {
        if (!array_key_exists($b,$a[$i]))
        {
            throw new Exception("Во вложенном массиве с индексом $i отсутсвует ключ $b.");
        }
    }

    function sorter($key): Closure
    {
        return function ($a, $b) use ($key)
        {
            return $a[$key] <=> $b[$key];
        };
    }

    return usort($a, sorter($b));
}


//echo convertString('string string string', 'str');

//try
//{
//    mySortForKey($a, $b);
//} catch (Exception $e)
//{
//    echo $e->getMessage();
//}