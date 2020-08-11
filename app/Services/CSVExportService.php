<?php


namespace App\Services;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * working with csv data and files
 * Class CSVExportService
 * @package App\Services
 */
class CSVExportService
{
    /**
     * @var int
     */
    public $foundShippingCount;

    /**
     * prepared csv data
     * @var Collection
     */
    public $CSVData;

    /**
     * csv file name
     * @var string
     */
    protected $fileNameCSV;

    /**
     * CSVExportService constructor.
     */
    public function __construct()
    {
        $this->createFileNameCSV();
        $this->CSVData = new Collection();
    }

    /**
     * @param Collection $xmlOrders
     */
    public function createCSVData(Collection $xmlOrders): void
    {
        $resultCSVData = new Collection();
        foreach ($xmlOrders as $order) {
            $shippingOrders = $this->findShippingOrders($order->getOrderNo());
            if (!empty($shippingOrders)) {
                foreach ($shippingOrders as $item) {
                    $item->ShipmentNo = $order->getShipmentNo();
                    $resultCSVData->push($item);
                }
            }
        }
        $this->foundShippingCount = $resultCSVData->count();
        $this->CSVData = $resultCSVData;
    }

    /**
     * @return mixed
     */
    public function getCSVData()
    {
        return $this->CSVData;
    }

    /**
     * @return mixed
     */
    public function getFoundShippingCount()
    {
        return $this->foundShippingCount;
    }

    /**
     * find shipping orders in database
     * @param int $orderNo
     * @return Collection
     */
    protected function findShippingOrders(int $orderNo): Collection
    {
        $result = DB::table('shipping')
            ->where('order_id', $orderNo)
            ->get();

        return $result;
    }

    /**
     * search for already processed shipments and delete CSVdata
     * @return int
     */
    public function checkDuplicateCSVShipping(): int
    {
        $existingShipping = DB::table('csv_tracking_number')->pluck('tracking_number');
        $countOfDuplicates = 0;
        foreach ($this->CSVData as $key => $value) {
            if ($existingShipping->contains($value->tracking_number)) {
                $countOfDuplicates++;
                unset($this->CSVData[$key]);
            }
        }

        return $countOfDuplicates;
    }

    /**
     * @return int
     */
    public function saveCSVInDatabase(): int
    {
        $csvShippingId = DB::table('csv_shipping')->insertGetId([
            'processed_date' => today(),
            'file_name' => $this->fileNameCSV
        ]);

        $ordersInsert = [];
        foreach ($this->getCSVData() as $order) {
            $ordersInsert[] = ['tracking_number' => $order->tracking_number, 'csv_shipping_id' => $csvShippingId];
        }
        DB::table('csv_tracking_number')->insert($ordersInsert);

        return count($ordersInsert);
    }

    /**
     * create csv file name
     */
    protected function createFileNameCSV(): void
    {
        $this->fileNameCSV = 'csv_shipping_' . Str::random(5) . '_' . today()->format('Y_m_d');
    }

    /**
     * create csv file
     */
    public function createCSVFile(): void
    {
        $handle = fopen(storage_path('/app/shipping/' . $this->fileNameCSV), 'w+');
        fputcsv($handle, ['order_id', 'type', 'tracking_number', 'sending_date', 'ShipmentNo'], ';');
        foreach ($this->CSVData as $order) {
            fputcsv($handle, [
                $order->order_id,
                $order->type,
                $order->tracking_number,
                $order->sending_date,
                $order->ShipmentNo
            ], ';');
        }
        fclose($handle);
    }
}
