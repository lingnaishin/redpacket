<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RedPacket
 * @package App\Models
 *
 * @property integer $creator_user_id creator user id
 * @property double $amount Amount
 * @property integer $quantity Quantity of red packet able to receive
 * @property boolean $random Random amount or equally divided
 * @property integer remaining_quantity Remaining quantity that can receive in red packet
 * @property double $remaining_amount Remaining amount that can receive in red packet
 */
class RedPacket extends Model
{
    protected $table = 'red_packets';

    protected $fillable = [
        'creator_user_id',
        'amount',
        'quantity',
        'random',
        'remaining_quantity',
        'remaining_amount',
        'created_at',
        'updated_at'
    ];

    /**
     * To generate receive amount base on random value
     *
     * @return float|int
     */
    public function generateReceiveAmount()
    {
        // if it's the last packet quantity, return all remaining amount
        if ($this->remaining_quantity === 1) {
            return $this->remaining_amount;
        }

        // if random, use some random formula to generate receive amount
        if ($this->random) {
            return round($this->remaining_amount / $this->remaining_quantity * (rand(1,200)/ 100), 2);
        } else {
            // if not random, split exactly
            return round($this->amount * 1.0 / $this->quantity, 2);
        }
    }
}
