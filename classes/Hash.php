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
        return random_bytes($length);
    }
    //Метод возвращает хэшированную строку, полученную из 'уникального id', полученного на основании
    //текущего времени в микросекундах. 
    public static function unique(): string
    {
        return self::make(uniqid());
    }
}
