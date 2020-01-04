<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\PricesResource;
use App\Prices;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 

class PricesController extends Controller
{
    public $successStatus = 200;

    public function getPrices()
    {
        $prices = DB::table('prices')->orderBy('lastInsertUpdateTS', 'desc')->first();

        if($prices != null) {
            return response()->json($prices, $this-> successStatus);
        } else {
            abort(500, 'No existing records found. Please update prices!');
        }
    }

    public function updatePrices(Request $request)
    {
        $user = Auth::user(); 

        $prices = new Prices;
        $prices->pww = $request->input('pww');
        $prices->pw = $request->input('pw');
        $prices->pullets = $request->input('pullets');
        $prices->small = $request->input('small');
        $prices->medium = $request->input('medium');
        $prices->large = $request->input('large');
        $prices->extraLarge = $request->input('extraLarge');
        $prices->jumbo = $request->input('jumbo');
        $prices->crack = $request->input('crack');
        $prices->spoiled = $request->input('spoiled');
        $prices->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
        $prices->lastInsertUpdateTS = Carbon::now();
        $prices->save();

        return response()->json(new PricesResource($prices), $this-> successStatus);
    }
}
