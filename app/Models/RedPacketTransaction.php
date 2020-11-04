<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RedPacketTransaction
 * @package App\Models
 *
 * @property integer $target_red_packet_id Targeted red packet id
 * @property integer $receiver_user_id Receiving user id
 * @property double $received_amount Received amount
 * @property boolean $success If transaction success
 * @property string $unsuccessful_reason Message why transaction fail
 */
class RedPacketTransaction extends Model
{
    protected $table = 'red_packet_transactions';

    protected $fillable = [
        'target_red_packet_id',
        'receiver_user_id',
        'received_amount',
        'success',
        'unsuccessful_reason',
        'created_at',
        'updated_at'
    ];

    /**
     * To record transactions of receiving of red packets
     *
     * @param User $user user object
     * @param RedPacket $redPacket red packet object
     * @param double $receivedAmount receiving amount
     * @param boolean $success transaction success
     * @param string|null $unsuccessfulReason unsucessful reason
     *
     * @return RedPacketTransaction
     */
    public static function record(User $user, RedPacket $redPacket, float $receivedAmount, bool $success = false, string $unsuccessfulReason = null)
    {
        $transaction = new RedPacketTransaction();
        $transaction->target_red_packet_id = $redPacket->id;
        $transaction->receiver_user_id = $user->id;
        $transaction->received_amount = $receivedAmount;
        $transaction->success = $success;
        $transaction->unsuccessful_reason = $unsuccessfulReason;
        $transaction->save();

        return $transaction;
    }
}
