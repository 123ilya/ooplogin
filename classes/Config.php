<?php
// Создаём класс Config. В нём создаём статический метод get.
class Config
{
    //В качестве аргумента принимается путь к нужному элементу массива $GLOBALS['config'] указанный
    //не как путь к элементу сложного массива, а как путь к файлу сложной дирректории.
    public static function get(string $path = null): string
    {
        if ($path) {
            $config = $GLOBALS['config'];//В переменную $config записываем содержимое массива $GLOBALS['config']
            $path = explode('/', $path); //$path - массив, созданный из осколков аргумента(разбитой по разделителю '/' строки)
            //Пробегаем по массиву и на каждой итерации, если в массиве $config на верхнем уровне
            //есть ключ, совпадающий со значением $bit, то массиву присваивается значение этого ключа.
            //То есть мы на каждой итерации обрезаем параллеьные элементы , соседствующие с $bit
            //в $config. В итог переменная $config принимает значение свойства, указанного в нашем
            //псевдопути и метод возвращает это значение.
            foreach ($path as $bit) {
                if (isset($config[$bit])) {//Есть ли в массиве $config элемент с именем $bit и задано ли у этого элемента какое либо значение. 
                    $config = $config[$bit];//В итоге переменной $config присваивается значение(строка)
                }
            }
            return $config; //Если аргумент передан и он отражает действительность, то 
            //возвращается строка. Если аргумент передан, но он не имеет связи с реальностью(содержанием 
            //масива 'config' ) то возвращается содержимое самого массива 'config'
        }
        return false; //Если аргумент не передан вообще, то возвращается false.
    }
}
