<?php

namespace App\Console\Commands\Order;

use App\Helpers\Interfaces\OrdersServiceInterface;
use App\Services\CSVExportService;
use App\Services\OrdersXMLService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportOrders extends Command
{
    private $logger;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import orders shipping to csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->logger = Log::channel('xml-importing-orders');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(OrdersServiceInterface $ordersService, CSVExportService $CSVExportService)
    {
        $this->line('Starting import orders');
        try {
            $xmlOrders = $ordersService->getOrders();
            $this->line("Found {$ordersService->getOrdersCount()} orders in files");
            $CSVExportService->createCSVData($xmlOrders);

            $this->line("Found {$CSVExportService->getFoundShippingCount()} sent packages");
            $this->line("Found {$CSVExportService->checkDuplicateCSVShipping()} duplicates tracking_number in database");

            if ($CSVExportService->getCSVData()->isEmpty()) {
                $ordersService->deleteOrders();
                $this->line('Deleting xml files');
                $this->logger->info('Nothing to import into csv');
                $this->info('Nothing to import into csv');
            } else {
                DB::beginTransaction();
                $countInsertingShipping = $CSVExportService->saveCSVInDatabase();
                $CSVExportService->createCSVFile();
                $this->line("csv file successful created");
                $ordersService->deleteOrders();
                $this->line('Deleting xml files');

                $this->line("Inserted $countInsertingShipping new rows into database");
                DB::commit();
                $this->info('Import of orders was successful');
                $this->logger->info('Import of orders was successful');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->error($exception->getMessage());
            $this->logger->error($exception->getMessage());
        }
    }
}
