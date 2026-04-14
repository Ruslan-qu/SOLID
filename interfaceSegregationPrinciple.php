<?php

/*Interface Segregation Principle (Принцип разделения интерфейса).*/

/*Принцип заключается в том, что пользователи системы не должны зависеть от функций, которые они не используют. 
Вместо того чтобы создавать универсальные интерфейсы, следует разрабатывать специализированные, соответствующие конкретным потребностям. 
Такой подход уменьшает зависимость между частями системы, повышает её гибкость и облегчает повторное использование кода.*/

/*Пример 1 демонстрирует универсальный интерфейс DriverInterface, который содержит две функции: driveBus и driveTruck. 
Однако классы BusDriver и TruckDriver используют только одну из этих функций. 
Класс BusDriver не нуждается в функции driveTruck, а класс TruckDriver — в функции driveBus. 
Это нарушает принцип разделения интерфейса, поскольку каждый из классов должен реализовывать только те методы, 
которые ему действительно необходимы.*/

/*Пример 2 показывает два специализированных интерфейса: BusDriverInterface с функцией driveBus и TruckDriverInterface с функцией driveTruck. 
Классы BusDriver и TruckDriver используют только те функции, которые им необходимы. 
Если потребуется добавить обе функции в класс, как в случае с UniversalDriver, можно указать оба интерфейса. 
Этот подход не нарушает принцип разделения интерфейса.*/

/*Пример 1 */
interface DriverInterface /*Универсальный интерфейс.*/
{
    public function driveBus(string $string): string;
    public function driveTruck(string $string): string;
}

class BusDriver implements DriverInterface
{
    public function driveBus(string $string): string
    {
        return $string;
    }

    public function driveTruck(string $string): string /*Функций, которая не используется.*/
    {
        return $string;
    }
}

class TruckDriver implements DriverInterface
{
    public function driveTruck(string $string): string
    {
        return $string;
    }

    public function driveBus(string $string): string /*Функций, которая не используется.*/
    {
        return $string;
    }
}

/*Пример 2 */
interface BusDriverInterface /*Специализированные интерфейс.*/
{
    public function driveBus(string $string): string;
}

interface TruckDriverInterface /*Специализированные интерфейс.*/
{
    public function driveTruck(string $string): string;
}

class BusDriver implements BusDriverInterface
{
    public function driveBus(string $string): string
    {
        return $string;
    }
}

class TruckDriver implements TruckDriverInterface
{
    public function driveTruck(string $string): string
    {
        return $string;
    }
}

class UniversalDriver implements BusDriverInterface, TruckDriverInterface
{
    public function driveBus(string $string): string
    {
        return $string;
    }

    public function driveTruck(string $string): string
    {
        return $string;
    }
}
