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



function importXml(string $a)
{
    require 'connectDB.php';

    $xml = simplexml_load_file($a);

    foreach ($xml as $value)
    {
        $code = $value['Код'];
        $check = $connect->query("SELECT * FROM a_product WHERE code='$code'");
        if($check->num_rows <= 0) // Проверка наличия товаров в базе по полю "Код"
        {

// a_product ---------------------------------------------------------------------------
            $name = $value['Название'];
            $connect->query("INSERT INTO a_product (code, name) VALUES ('$code', '$name')"); // Записываем код и название в a_product
            $id = $connect->insert_id;

// a_category a_category_product ---------------------------------------------------------------------------
            $id_cat = '-';
            foreach ($value->Разделы->Раздел as $categories) // Записываем код, категорию, род. категорию в a_category. id продукта и id категории в a_category_product
            {
                $category = $categories;
                $code = $categories['Код'];

                $query = $connect->query("SELECT * FROM a_category WHERE name='$category'");
                if($query->num_rows <= 0)
                {
                    $connect->query("INSERT INTO a_category (code, name, parent_id) VALUES ('$code', '$category', '$id_cat')");
                    $id_cat = $connect->insert_id;
                } else
                {
                    $id_cat = mysqli_fetch_assoc($query)['id'];
                }
                $queryCatPr = $connect->query("SELECT * FROM a_category_product WHERE id_product='$id' AND id_category='$id_cat'");
                if($queryCatPr->num_rows <= 0)
                {
                    $connect->query("INSERT INTO a_category_product (id_category, id_product) VALUES ('$id_cat', '$id')");
                }
            }

// a_price ---------------------------------------------------------------------------
            foreach ($value->Цена as $price) // Записываем цену, тип и id продукта в a_price
            {
                $type = $price['Тип'];
                $val = $price;
                $connect->query("INSERT INTO a_price (id_product, price_type, price) VALUES ('$id', '$type', '$val')");
            }

// a_property ---------------------------------------------------------------------------
            foreach($value->Свойства->children() as $tags) // Записываем id продукта его свойста и значения в a_property
            {
                $property = $tags->getName();
                $pr_value = $tags;
                $at_property = $tags->attributes()->getName();
                $at_value = $tags['ЕдИзм'];
                $connect->query("INSERT INTO a_property (id_product, property, value, att_property, att_value) VALUES ('$id', '$property', '$pr_value', '$at_value', '$at_property')");
            }
        } else
        {
            echo "Товар с кодом $code уже существует<br>";
        }
    }
}



/**
 * @throws DOMException
 * @throws Exception
 */

function exportXml(string $a, string $b)
{
    require 'connectDB.php';

    $checkCategory = $connect->query("SELECT * FROM a_category WHERE name='$b'");
    if ($checkCategory->num_rows <= 0) // Проверка наличия товаров в заданной категории
    {
        throw new Exception("Товары в категории '$b' отсутствуют");
    }

    $dom = new domDocument("1.0", "windows-1251");
    $root = $dom->createElement("Товары"); // Создаём корневой элемент Товары
    $dom->appendChild($root); // Добавляем корневой каталог "Товары" в файл

    $queryIdCategory = $checkCategory->fetch_assoc();
    $idCategory = $queryIdCategory['id'];

    $allProducts = $connect->query("SELECT * FROM a_category_product WHERE id_category='$idCategory'"); // Запрос на выборку товаров
    while($row = $allProducts->fetch_assoc())// Получаем все строки в цикле по одной
    {
        $idProduct = $row['id_product'];
// Товар ---------------------------------------------------------------------------
        $tagProduct = $dom->createElement("Товар"); // Создаём узел "Товар"
        $infoProduct = $connect->query("SELECT * FROM a_product WHERE id='$idProduct'");
        while ($row = $infoProduct->fetch_assoc())
        {
            $tagProduct->setAttribute("Код", $row['code']); // Устанавливаем атрибут "Код" у узла "Товары"
            $tagProduct->setAttribute("Название", $row['name']); // Устанавливаем атрибут "Название" у узла
        }
// Цена ---------------------------------------------------------------------------
        $infoPrice = $connect->query("SELECT * FROM a_price WHERE id_product='$idProduct'");
        while ($row = $infoPrice->fetch_assoc())
        {
            $tagPrice = $dom->createElement("Цена", $row['price']); // Создаём узел "Цена" с текстом внутри
            $tagPrice->setAttribute("Тип", $row['price_type']); // Устанавливаем атрибут "Тип" у узла "Цена"
            $tagProduct->appendChild($tagPrice); // Добавляем в узел "Товар" узел "Цены"
        }
// Свойства ---------------------------------------------------------------------------
        $tagProperty = $dom->createElement("Свойства"); // Создаём узел "Свойства"
        $infoProperty = $connect->query("SELECT * FROM a_property WHERE id_product='$idProduct'");
        while ($row = $infoProperty->fetch_assoc())
        {
            $nameProperty = $row['property'];
            $tagInfoProperty = $dom->createElement("$nameProperty", $row['value']);
            $tagProperty->appendChild($tagInfoProperty); // Добавляем в узел "Свойства" узлы с "Имя свойства"
        }
        $tagProduct->appendChild($tagProperty); // Добавляем в узел "Товар" узел "Свойства"
// Разделы ---------------------------------------------------------------------------
        $tagCategories = $dom->createElement("Разделы"); // Создаём узел "Разделы"
        $queryIdCategories = $connect->query("SELECT * FROM a_category_product WHERE id_product='$idProduct'");
        while ($row = $queryIdCategories->fetch_assoc())
        {
            $idProduct = $row['id_category'];
            $queryNameCategory = $connect->query("SELECT * FROM a_category WHERE id='$idProduct'");
            while ($row = $queryNameCategory->fetch_assoc())
            {
                $tagCategory = $dom->createElement("Раздел", $row['name']); // Создаём узел "Раздел"
                $tagCategories->appendChild($tagCategory); // Добавляем в узел "Разделы" узел "Раздел"
            }
        }
        $tagProduct->appendChild($tagCategories);// Добавляем в узел "Товар" узел "Разделы"

        $root->appendChild($tagProduct); // Добавляем в корневой узел "Товары" узел "Товар"
    }

    $dom->save("$a"); // Сохраняем полученный XML-документ в файл
}



//echo convertString('string string string', 'str');

//try
//{
//    mySortForKey($a, $b);
//} catch (Exception $e)
//{
//    echo $e->getMessage();
//}

//importXml('test.xml');

//try
//{
//    exportXml('test.xml', 'принтеры');
//} catch (DOMException|Exception $e) {
//    echo $e->getMessage();
//}