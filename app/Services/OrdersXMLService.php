<?php


namespace App\Services;


use App\Helpers\Interfaces\OrdersServiceInterface;
use App\Helpers\XMlOrder;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;

/**
 * Class OrdersXMLService
 * @package App\Services
 */
class OrdersXMLService implements OrdersServiceInterface
{
    /**
     * @var Collection
     */
    public $orders;

    /**
     * customized filesystem to path /storage/app/orders
     * @var Filesystem
     */
    public $filesystem;

    /**
     * OrdersXMLService constructor.
     * @param Filesystem $filesystemXMLOrders
     */
    public function __construct(Filesystem $filesystemXMLOrders)
    {
        $this->orders = collect();
        $this->filesystem = $filesystemXMLOrders;
    }

    /**
     * @return Collection
     */
    public function getOrders(): Collection
    {
        $this->importXMLOrders();
        return $this->orders;
    }

    /**
     * @return int
     */
    public function getOrdersCount(): int
    {
        return $this->orders->count();
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function importXMLOrders()
    {
        $files = $this->filesystem->files();
        foreach ($files as $fileName) {
            if (mb_substr($fileName, -4) == '.xml') {
                $this->orders[] = new XMlOrder($this->filesystem->get($fileName), $fileName);
            }
        }
    }

    /**
     * delete orders
     */
    public function deleteOrders(): void
    {
        foreach ($this->orders as $order){
            $this->filesystem->delete($order->fileName);
        }
    }
}
