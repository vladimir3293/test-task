<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CSVShipping
 * @package App\Models
 */
class CSVShipping extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'csv_shipping';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the orders for the csv file.
     */
    public function orders()
    {
        return $this->hasMany(CSVTrackingNumber::class);
    }
}
