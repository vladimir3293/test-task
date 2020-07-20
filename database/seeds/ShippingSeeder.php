<?php

use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    private $parcels = [
        [
            'order_id'        => 146532,
            'type'            => 'DPD',
            'tracking_number' => '15505561807868',
            'sending_date'    => '2020-05-13 12:54:17',
        ],
        [
            'order_id'        => 146532,
            'type'            => 'DPD',
            'tracking_number' => '15505561807869',
            'sending_date'    => '2020-05-13 12:54:32',
        ],
        [
            'order_id'        => 146532,
            'type'            => 'DPD',
            'tracking_number' => '15505561807870',
            'sending_date'    => '2020-05-13 12:55:06',
        ],
        [
            'order_id'        => 146534,
            'type'            => 'GLS',
            'tracking_number' => '954465675465',
            'sending_date'    => '2020-05-11 15:23:21',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->parcels as $parcel) {
            $shipping = new \App\Models\Shipping();
            foreach ($parcel as $field => $value) {
                $shipping->{$field} = $value;
            }
            $shipping->save();
        }
    }

}
