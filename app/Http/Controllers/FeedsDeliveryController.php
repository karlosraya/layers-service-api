<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\FeedsDeliveryResource;
use App\FeedsDelivery;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 

class FeedsDeliveryController extends Controller
{
    public $successStatus = 200;

    public function getFeedsDeliveryByDate($date)
    {
        if($date != null) {
            $feedsDelivery = DB::table('feeds_deliveries')->where('deliveryDate', '=', $date)->first();

            $productions = DB::table('productions')->where('reportDate', '<=', $date);

            if($feedsDelivery == null) {
                $feedsDelivery = new FeedsDelivery;
            } 
            
            $feedsDelivery->totalConsumption = intval(DB::table('productions')->where('reportDate', '<', $date)->sum('feeds'));
            $feedsDelivery->dailyConsumption = intval(DB::table('productions')->where('reportDate', '=', $date)->sum('feeds'));
            $feedsDelivery->totalAvailable = DB::table('feeds_deliveries')->where('deliveryDate', '<', $date)->sum('delivery');
           
            return response()->json($feedsDelivery);
        } else {
            abort(500, 'No date found from the request!');
        }
    }

    public function getFeedsDelivered()
    {
        $feedsDelivery = FeedsDelivery::all();
        return response()->json(FeedsDeliveryResource::collection($feedsDelivery));
    }

    public function createUpdateFeedsDelivery(Request $request)
    {
        $user = Auth::user(); 

        $feedsDelivery = null;

        if($request->id != null) {
            $feedsDelivery = FeedsDelivery::findOrFail($request->id);
        } else {
            $feedsDelivery = new FeedsDelivery;
        }

        $feedsDelivery->deliveryReceiptNo = $request->input('deliveryReceiptNo');
        $feedsDelivery->delivery = $request->input('delivery');
        $feedsDelivery->deliveryDate = $request->input('deliveryDate');
        $feedsDelivery->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
        $feedsDelivery->lastInsertUpdateTS = Carbon::now();;
        $feedsDelivery->save();

        return response()->json(new FeedsDeliveryResource($feedsDelivery));
    }
}
