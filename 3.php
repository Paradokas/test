<?php
namespace Test3;

use Exception; // добавил класс

class newBase
{
    protected string $name; // перенес вверх изменил private на protected добавил string
    protected mixed $value; // пернес вверх и добавил mixed

    /**
     * @param string $name
     */
    function __construct(string $name = ' ') // int изменил на string и переделал функцию лишнее удалил
    {
        if (trim($name) == false)
        {
            $name = '0';
        }
        $this->name = $name;
    }



    /**
     * @return string
     */
    public function getName(): string
    {
        return '*' . $this->name  . '*';
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
     */ // изменил string на int
    public function getSize():int // добавил int
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
        $value = serialize($this->value); // вместо serialize($value)
        return $this->name . ':' . strlen($value) . ':' . $value; // sizeof($value) заменил на strlen($value)
    }



    /**
     * @param string $value
     * @return newBase
     */ // добавил @param string $value
    static public function load(string $value): newBase // переделал функцию
    {
        $arValue = explode(':', $value);
        $res = new newBase($arValue[0]);
        $res->setValue(unserialize(substr($value, strlen($arValue[0]) + 1 + strlen($arValue[1]) + 1, $arValue[1])));
        return $res;
    }
}

class newView extends newBase
{
    private ?string $type = null; // добавил ?string
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
        return $this->value;
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



    private function setSize()
    {
        if (is_subclass_of($this->value, 'Test3\newBase')) // вместо "Test3\newView"
        {
            $this->size = parent::getSize() + 1 + strlen($this->property);
        } elseif ($this->type === 'test')
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
            throw new Exception("The object doesn't have name"); // вместо 'The object doesn\'t have name'
        }
        return '"' . $this->name  . '": ';
    }



    /**
     * @return string
     */
    public function getType(): string
    {
        return ' type ' . $this->type  . ';';
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
        if ($this->type === 'test' && is_object($this->value)) // вместо ($this->type == 'test')
        {
            $this->value = $this->value->getSave();
        }
        return parent::getSave() .':'. serialize($this->property);
    }



    /**
     * @param string $value
     * @return newView
     */ // добавил @param string $value
    static public function load(string $value): newView // вместо newBase и переделал функцию
    {
        $arValue = explode(':', $value);
        $res = new newView($arValue[0]);

        $res->setValue(new newBase(substr($arValue[4],1)))->setValue(unserialize(substr($value, strlen($arValue[0]) + 1 + strlen($arValue[1]) + 1 + strlen($arValue[2]) + 1 + strlen($arValue[3]) + 1 + strlen($arValue[4]) + 1 + strlen($arValue[5]) + 1, $arValue[5])));
        $res->setProperty(unserialize(substr($value, -(strlen($arValue[11]) + 1 + strlen($arValue[10]) + 2))));

        return $res;
    }

}

function gettype($value): string
{
    if (is_object($value)) {
        $type = get_class($value);
        do {
            if (str_contains($type, 'Test3\newBase')) //вместо (strpos($type, "Test3\newBase") !== false)
            {
                return 'test';
            }
        } while ($type = get_parent_class($type));
    }
    return gettype($value);
}



$obj = new newBase('12345');
$obj->setValue('text');

$obj2 = new newView('09876'); // вместо new \Test3\newView('O9876');
$obj2->setValue($obj);
$obj2->setProperty('field');
$obj2->getInfo();

$save = $obj2->getSave();

$obj3 = newView::load($save);

var_dump($obj2->getSave() === $obj3->getSave());