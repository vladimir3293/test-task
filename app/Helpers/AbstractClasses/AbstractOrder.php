<?php


namespace App\Helpers\AbstractClasses;

/**
 * Class AbstractOrder
 * @package App\Helpers\AbstractClasses
 */
abstract class AbstractOrder
{
    /**
     * get order No
     * @return int
     */
    public abstract function getOrderNo(): int;

    /**
     * get order shipment No
     * @return string
     */
    public abstract function getShipmentNo(): string;

    /**
     * get order name
     * @return string
     */
    public abstract function getName(): string;

    /**
     * get order date
     * @return string
     */
    public abstract function getOrderDate(): string;
}
