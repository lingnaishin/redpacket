<?php

namespace App\Http\Controllers;

use App\Models\RedPacket;
use App\Models\RedPacketTransaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RedPacketController extends Controller
{
    /**
     * To create a red packet
     *
     * @param Request $request request
     */
    public function createRedPacket(Request $request)
    {
        // sorry about the ugly validator in controller, usually I create new request class xD
        $validator = Validator::make($request->all(), [
            'login_as' => 'required|exists:users,email',
            'amount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'random' => 'required|boolean'
        ]);

        // if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }
        // get user
        $loginUser = User::where('email', $request->login_as)->first();
        // check user balance
        if ($request->amount > $loginUser->balance) {
            return response()->json(['User does not have enough balance'], 422);
        }

        // prevent float go brrr
        $redPacketAmount = round($request->amount, 2);

        $redPacket = new RedPacket();
        $redPacket->creator_user_id = $loginUser->id;
        // prevent float goes crazy (PHP >_>)
        $redPacket->amount = $redPacketAmount;
        $redPacket->quantity = $request->quantity;
        $redPacket->random = $request->random;
        $redPacket->remaining_amount = $redPacket->amount;
        $redPacket->remaining_quantity = $redPacket->quantity;

        // Update user remaining balance after create packet
        // TODO: create wallet module (Shin, 5/11/2020)
        $loginUser->balance -= $redPacketAmount;
        if ($loginUser->save() && $redPacket->save()) {
            return response()->json(['Red packet created.'], 201);
        }
    }

    /**
     * To receive red packet
     *
     * @param Request $request request
     */
    public function receiveRedPacket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_as' => 'required|exists:users,email',
            'selected_red_packet_id' => 'required|exists:red_packets,id'
        ]);

        // if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        $loginUser = User::where('email', $request->login_as)->first();
        $redPacket = RedPacket::where('id', $request->selected_red_packet_id)->first();

        $allowReceive = true;
        $unsuccessfulMessages = [];

        $userReceived = RedPacketTransaction::where('target_red_packet_id', $redPacket->id)
                                            ->where('receiver_user_id', $loginUser->id)
                                            ->where('success', true)
                                            ->exists();

        if ($userReceived) {
            $allowReceive = false;
            $unsuccessfulMessages[] = 'User has already received before';
        }

        if ($loginUser->id === $redPacket->creator_user_id) {
            $allowReceive = false;
            $unsuccessfulMessages[] = 'Cannot receive own red packet';
        }

        if ($redPacket->remaining_amount == 0 || $redPacket->remaining_quantity == 0) {
            $allowReceive = false;
            $unsuccessfulMessages[] = 'Red packet is empty already';
        }

        $receiveAmount = $allowReceive ? $redPacket->generateReceiveAmount() : 0;
        $unsuccessfulMessage = implode(',', $unsuccessfulMessages);


        if ($allowReceive) {
            $redPacket->remaining_quantity--;
            $redPacket->remaining_amount -= $receiveAmount;
            $loginUser->balance += $receiveAmount;
            $transaction = RedPacketTransaction::record($loginUser, $redPacket, $receiveAmount, $allowReceive, $unsuccessfulMessage);

            try {
                if ($redPacket->save() && $loginUser->save()) {
                    $transaction->success = true;
                    $transaction->save();
                    return response()->json(['Congratulation, you have received ' . $receiveAmount], 200);
                }
            } catch (\Exception $ex) {
                $transaction->success = false;
                $transaction->unsuccessful_message = $ex->getMessage();
                $transaction->save();
                Log::error($ex);
            }
        } else {
            return response()->json(['Unable to receive due to ' . strtolower($unsuccessfulMessage)]);
        }
    }
}
