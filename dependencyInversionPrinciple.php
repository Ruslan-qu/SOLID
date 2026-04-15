<?php

/*Dependency Inversion Principle (Принцип инверсии зависимостей).*/

/* */
/* */

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

class TruckDriver extends Driver implements DriverInterface
{
    public function drive(string $string): string /*int, bool, т.д, запрещено*/
    {
        return $string; /*int, bool, т.д, запрещено*/
    }
}

class Operator
{
    private DriverInterface $driverInterface; /*class TruckDriver или class BusDriver запрещено нарушают принцип*/

    public function setDriverInterface(DriverInterface $driverInterface)
    {
        $this->driverInterface = $driverInterface;
    }

    public function getDriverInterface(): DriverInterface
    {
        return $this->driverInterface;
    }
}
