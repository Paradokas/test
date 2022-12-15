<?php

namespace Test3;

use Exception; // добавил класс

class newBase
{
    static private int $count = 0; // добавил int
    static private array $arSetName = []; // добавил array
    protected string $name; // перенес вверх изменил private на protected добавил string
    protected mixed $value; // пернес вверх и добавил mixed



    /**
     * @param string $name
     */
    function __construct(string $name) // добавил string
    {
        if (empty($name))
        {
            while (array_search(self::$count, self::$arSetName) != false)
            {
                ++self::$count;
            }
            $name = self::$count;
        }
        $this->name = $name;
        self::$arSetName[] = $this->name;
    }



    /**
     * @return string
     */
    public function getName(): string
    {
        return '*' . $this->name . '*';
    }



    /**
     * @param mixed $value
     * @return mixed
     */ // добавил @return mixed
    public function setValue(mixed $value): mixed // добавил mixed x2
    {
        return $this->value = $value; // добавил return
    }



    /**
     * @return int
     */ // int
    public function getSize(): int // int
    {
        return strlen(serialize($this->value));  // было $size = strlen(serialize($this->value)); и return strlen($size) + $size;
    }



    public function __sleep() // не особо понял что делает метод __sleep()
    {
        return ['value'];
    }



    /**
     * @return string
     */
    public function getSave(): string
    {
        $value = serialize($this->value); // $value заменил на $this->value
        return $this->name . ':' . strlen($value) . ':' . $value; // sizeof($value) заменил на strlen($value)
    }



    /**
     * @param string $value
     * @return newBase
     */ // добавил @param string $value
    static public function load(string $value) // тут все под вопросом))
    {
        $arValue = explode(':', $value);
//        return (new newBase($arValue[0]))
//            ->setValue(unserialize(substr($value, strlen($arValue[0]) + 1
//                + strlen($arValue[1]) + 1), $arValue[1]))
//            ->setProperty(unserialize(substr($value, strlen($arValue[0]) + 1
//                + strlen($arValue[1]) + 1 + $arValue[1])))
//            ; //было
        return unserialize(substr($value, strlen($arValue[0]) + 1 + strlen($arValue[1]) + 1), [$arValue[4]]);//стало
    }
}

class newView extends newBase
{
    private ?string $type = null; // добавил ?string
    private int $size = 0; // добавил int
    private ?string $property = null; // добавил ?string



    /**
     * @param mixed $value
     * @return mixed
     */ // добавил mixed
    public function setValue(mixed $value): mixed // добавил mixed x2
    {
        parent::setValue($value);
        $this->setType();
        $this->setSize();
        return $this->value; // добавил return $this->value;
    }



    /**
     * @param mixed $value
     * @return newView
     */ // добавил  @param mixed $value и @return newView
    public function setProperty(mixed $value): newView // добавил mixed и :newView
    {
        $this->property = $value;
        return $this;
    }


    private function setType(): void // добавил :void
    {
        $this->type = gettype($this->value);
    }



    private function setSize(): void // добавил :void
    {
        if (is_subclass_of($this->value, 'Test3\newView'))
        { // изменил " " на ' '
            $this->size = parent::getSize() + 1 + strlen($this->property);
        } elseif ($this->type == 'test')
        {
            $this->size = parent::getSize();
        } else
        {
            $this->size = strlen($this->value);
        }
    }



    /**
     * @return string[]
     */ // вместо string
    public function __sleep(): array // Добавил array
    {
        return ['property'];
    }



    /**
     * @return string
     * @throws Exception
     */ // добавил @throws Exception
    public function getName(): string
    {
        if (empty($this->name))
        {
            throw new Exception("The object doesn\'t have name"); // изменил ' ' на " "
        }
        return '"' . $this->name . '": ';
    }



    /**
     * @return string
     */
    public function getType(): string
    {
        return ' type ' . $this->type . ';';
    }



    /**
     * @return int
     */ //изменил на int
    public function getSize(): int
    {
        return strlen(serialize($this->value)); // вместо return ' size ' . $this->size . ';';
    }



    public function getInfo()
    {
        try
        {
            echo $this->getName()
                . $this->getType()
                . $this->getSize()
                . "\r\n";
        } catch (Exception $exc)
        {
            echo 'Error: ' . $exc->getMessage();
        }
    }



    /**
     * @return string
     */
    public function getSave(): string
    {
        if ($this->type == 'test')
        {
            $this->value = $this->value->getSave();
        }
        return parent::getSave() . serialize($this->property);
    }



    /**
     * @param string $value
     * @return newView
     */ // добавил @param string $value
    static public function load(string $value): newView // вместо newBase и тут тоже все под вопросом))
    {
        $arValue = explode(':', $value);
        return (new newView($arValue[0])) // вместо new newBase
        ->setValue(unserialize(substr($value, strlen($arValue[0]) + 1 + strlen($arValue[1]) + 1), $arValue[1]))
            ->setProperty(unserialize(substr($value, strlen($arValue[0]) + 1 + strlen($arValue[1]) + 1), $arValue[1])); // вместо  + 1 + $arValue[1])));
    }
}

function gettype($value): string
{
    if (is_object($value)) {
        $type = get_class($value);
        do {
            if (str_contains($type, 'Test3\newBase')) //изменил " " на ' ' и str_pos на str_contains
            {
                return 'test';
            }
        } while ($type = get_parent_class($type));
    }
    return gettype($value);
}

$obj = new newBase('12345');
$obj->setValue('text');

$obj2 = new newView('09876'); // вместо $obj2 = new \Test3\newView('O9876');
$obj2->setValue($obj);
$obj2->setProperty('field');
$obj2->getInfo();

$save = $obj2->getSave();

//$obj3 = newView::load($save);

//var_dump($obj2->getSave() == $obj3->getSave());