<?php
//Данный класс предназначен для валидации пользовательского ввода 
class Validate
{
    private $_passed = false;
    private $_errors = array();
    private $_db = null;//экземпляр класса DB, овечающего за взаимодействия с базой данных

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }
    //Метод проверяет пользовательский ввод,согласно шаблона, переданного в качестве аргумента.
    //$source это либо $_POST либо $_GET, $items - многомерный массив. Ключи элементов верхнего уровня 
    //совпадают с именами валидируемых полей формы. Содержимое же их (массивов) представляет собой 
    //набор опций для валидации (минимальное количество символов, максимальное и тд). 
    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                // echo "{$item} {$rule} must be {$rule_value}.<br>";
                $value = trim($source[$item]);
                $item = escape($item);
                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required!");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            };

                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} characters.");
                            };

                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must mutch {$item}");
                            }

                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if ($check->count()) {
                                $this->addError("{$item} already exists.");
                            }

                            break;
                    }
                }
            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }
    private function addError($error)
    {
        $this->_errors[] = $error;
    }

    public function errors()
    {
        return $this->_errors;
    }

    public function passed()
    {
        return $this->_passed;
    }
}
