<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\HouseResource;
use App\House;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

class HouseController extends Controller
{
    public $successStatus = 200;

    public function getHouses()
    {
        $rawQuery = "CAST(houses.name AS unsigned) ASC";

        $activeBatches =  DB::table('batches')
                            ->where('batches.endDate', null);
                         
        $houses = DB::table('houses')
                    ->leftJoinSub($activeBatches, 'batches', function ($join) {
                        $join->on('houses.id', '=', 'batches.houseId');
                    })
                    ->select('houses.*', 'batches.batch', 'batches.id as batchId')
                    ->orderByRaw($rawQuery)
                    ->get();

        return response()->json($houses, $this-> successStatus);
    }

    public function createUpdateHouse(Request $request)
    {
        $user = Auth::user(); 

        if($request->id) {
            $house = House::findOrFail($request->id);
        } else {
            $house = new House;
        }

        $house->name = $request->input('name');
        $house->stockman = $request->input('stockman');
        $house->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
        $house->lastInsertUpdateTS = Carbon::now();
        $house->save();

        return response()->json(new HouseResource($house), $this-> successStatus); 
    }
}
