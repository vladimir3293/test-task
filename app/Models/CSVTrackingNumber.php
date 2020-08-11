<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CSVOrder
 * @package App\Models
 *
 */
class CSVTrackingNumber extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'csv_tracking_number';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the csv shipping that owns the order.
     */
    public function csvShipping()
    {
        return $this->belongsTo(CSVShipping::class);
    }
}
