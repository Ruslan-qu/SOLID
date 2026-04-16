<?php

/*Dependency Inversion Principle (Принцип инверсии зависимостей).*/

/*Модули верхних уровней не должны зависеть от модулей нижних уровней.
Оба типа модулей должны зависеть от абстракций.
Абстракции не должны зависеть от деталей.
Детали должны зависеть от абстракций.*/

/*Ниже в классе Operator используется не конкретная реализация классов BusDriver и TruckDriver, 
а абстракция в виде интерфейса DriverInterface. Это обеспечивает гибкость: можно легко заменять реализации, 
не внося изменений в сам код.*/

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
    public function drive(string $string): string
    {
        return $string;
    }
}

class TruckDriver extends Driver implements DriverInterface
{
    public function drive(string $string): string
    {
        return $string;
    }
}

class Operator
{
    private DriverInterface $driverInterface; /*class TruckDriver или class BusDriver запрещено, нарушают принцип*/

    public function setDriverInterface(DriverInterface $driverInterface)
    {
        $this->driverInterface = $driverInterface;
    }

    public function getDriverInterface(): DriverInterface
    {
        return $this->driverInterface;
    }
}
