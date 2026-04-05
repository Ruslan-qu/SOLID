<?php

/*Liskov Substitution Principle (Принцип подстановки Барбары Лисков).*/

/*Принцип подстановки Лисков (LSP) гласит, что если в программе используется объект базового класса, 
то любой его наследник может быть использован вместо него без изменения корректности работы программы. 
Это означает, что наследник не должен нарушать ожидания, основанные на поведении базового класса.*/

/*В примере класс Driver возвращает строку. Его наследники, BusDriver и TruckDriver, также должны возвращать строку. 
Это обусловлено тем, что согласно принципу наследники не могут изменять возвращаемый тип метода. Они могут только дополнять, 
но не изменять поведение базового класса Driver.*/

interface DriverInterface
{
    public function drive(string $string): string;
}

class Driver implements DriverInterface
{
    public function drive(string $string): string
    {
        return $string;
    }
}

class BusDriver extends Driver implements DriverInterface
{
    public function drive(string $string): string /*int, bool, т.д, запрещено*/
    {
        return $string; /*int, bool, т.д, запрещено*/
    }
}

class TruckDriver implements DriverInterface
{
    public function drive(string $string): string /*int, bool, т.д, запрещено*/
    {
        return $string; /*int, bool, т.д, запрещено*/
    }
}
