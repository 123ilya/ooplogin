<?php
class Hash
{
    //Метод принимает в качестве аргумента строку и salt(дополнительная строка).
    //Строки объединяються и хэшируються при помощи алгоритма sha256.
    public static function make(string $string, string $salt = ''): string
    {
        return hash('sha256', $string . $salt);
    }
    //Метод возвращает закодированную строку. Длина строки определяется значением аргумента $length
    public static function salt(int $length): string
    {
        return mb_convert_encoding(random_bytes($length), 'UTF-8');
    }
    //Метод возвращает хэшированную строку, полученную из 'уникального id', полученного на основании
    //текущего времени в микросекундах. 
    public static function unique(): string
    {
        return self::make(uniqid());
    }
}
