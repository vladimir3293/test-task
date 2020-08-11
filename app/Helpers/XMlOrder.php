<?php


namespace App\Helpers;

use App\Helpers\AbstractClasses\AbstractOrder;

/**
 * Class XMlOrder
 * @package App\Helpers
 */
class XMlOrder extends AbstractOrder
{
    /**
     * xml file name
     * @var string
     */
    public $fileName;

    /**
     * xml parsed object
     * @var \SimpleXMLElement
     */
    protected $xmlParsedData;

    /**
     * XMlOrder constructor.
     * @param string $xmlOrderData
     * @param string $orderFileName
     */
    public function __construct(string $xmlOrderData, string $orderFileName)
    {
        $this->fileName = $orderFileName;
        $this->xmlParsedData = new \SimpleXMLElement($xmlOrderData);
    }

    /**
     * @return int
     */
    public function getOrderNo(): int
    {
        return intval($this->xmlParsedData->OrderNo);
    }

    /**
     * @return string
     */
    public function getShipmentNo(): string
    {
        return strval($this->xmlParsedData->ShipmentNo);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return strval($this->xmlParsedData->Name);
    }

    /**
     * @return string
     */
    public function getOrderDate(): string
    {
        return strval($this->xmlParsedData->OrderDate);
    }
}
