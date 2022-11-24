<?php


///**
// * @throws Exception
// */
//function findSimple(int $a, int $b): array
//{
//    if ($a <= 0 || $b <= 0 || $a >= $b)
//    {
//        throw new Exception('Неверные входные данные');
//    }
//    if ($a === 1)
//    {
//        $a++;
//    }
//
//    $array = array();
//    for ($i = $a; $i <= $b; $i++)
//    {
//        $isSimple = true;
//        for ($c = 2; $c < $i; $c++)
//        {
//            if ($i % $c === 0)
//            {
//                $isSimple = false;
//                break;
//            }
//        }
//        if ($isSimple)
//        {
//            $array[] = $i;
//        }
//    }
//    return $array;
//}
//
//try
//{
//    findSimple(1,15);
//}
//catch (Exception $exception)
//{
//    echo $exception->getMessage();
//}


function findSimple(int $a, int $b): array|string
{
    try {
        if ($a <= 0 || $b <= 0 || $a >= $b) {
            throw new Exception('Неверные входные данные');
        }
        if ($a === 1) {
            $a++;
        }

        $array = array();
        for ($i = $a; $i <= $b; $i++) {
            $isSimple = true;
            for ($c = 2; $c < $i; $c++) {
                if ($i % $c === 0) {
                    $isSimple = false;
                    break;
                }
            }
            if ($isSimple) {
                $array[] = $i;
            }
        }

        return $array;
    } catch (Exception $exception) {
        return $exception->getMessage();
    }
}

function createTrapeze(array $a): array
{
    $chunk = array_chunk($a, 3);
    $array = [];

    for ($i = 0; $i < count($chunk); $i++) {
        $array[$i] = array_combine(['a', 'b', 'c'], $chunk[$i]);
    }

    return $array;
}

function squareTrapeze(array &$a): void
{
    foreach ($a as $key => $value) {
        $a[$key]['s'] = ($value['a'] + $value['b']) / 2 * $value['c'];
    }
}

function getSizeForLimit(array $a, float $b): array
{
    $index = 0;
    for ($i = 0; $i < count($a); $i++) {
        if ($a[$i]['s'] <= $b) {
            $index = $i;
        }
    }
    return $a[$index];
}

function getMin(array $a): int
{
    $array = array_values($a);
    sort($array);
    return $array[0];
}

function printTrapeze(array $a): void
{
    $table = <<<HTML
    <table style="border: 1px solid black">
        <thead>
        <tr>
            <th>a</th>
            <th>b</th>
            <th>c</th>
        </tr>
        </thead>
        <tbody>
HTML;
    for ($i = 0; $i < count($a); $i++) {
        $table .= '<tr';
        if ($i % 2 == 1) {
            $table .= ' style="background-color: lightgreen"';
        }
        $table .= <<<HTML
			>
                <td>{$a[$i]['a']}</td>
                <td>{$a[$i]['b']}</td>
                <td>{$a[$i]['c']}</td>
            </tr>
HTML;
    }
    $table .= <<<HTML
		</tbody>
    </table>
HTML;
    echo $table;
}

abstract class BaseMath
{
    public function exp1(float $a, float $b, float $c): float
    {
        return $a * ($b ** $c);
    }

    public function exp2(float $a, float $b, float $c): float
    {
        return ($a / $b) ** $c;
    }

    abstract public function getValue();
}

class F1 extends BaseMath
{
    private float $a;
    private float $b;
    private float $c;

    public function __construct(float $a, float $b, float $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

    public function getValue(): float
    {
        return ($this->a * ($this->b ** $this->c) + ((($this->a / $this->c) ** $this->b) % 3) ** min($this->a, $this->b, $this->c));
    }
}

$object = new F1(1, 2, 3);

$exp1 = $object->exp1(4, 5, 6);
$exp2 = $object->exp2(7, 8, 9);
$value = $object->getValue();

//Локальные переменные видны только в рамках той функции, где их определили.
//Свойства класса это специальные перменные, которые видны внутри каждого метода класса, в чем их преимущество и есть.