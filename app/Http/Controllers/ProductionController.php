<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\ProductionResource;
use App\Production;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 

class ProductionController extends Controller
{
    public $successStatus = 200;

   function getProductionReportsOfActiveBatches() {
        $batches = DB::table('batches')
                        ->where('batches.endDate', null)
                        ->join('houses', 'houses.id', '=', 'batches.houseId')
                        ->select('batches.id', 'batches.batch', 'batches.houseId', 'batches.startDate', 'batches.initialBirdBalance', 'batches.startAge', 'houses.name')
                        ->get();

        foreach ($batches as $batch) {
            $productions = DB::table('productions')
                        ->where('batchId', $batch->id)
                        ->get();

            $batch->productions = $productions; 
        }

        return response()->json($batches, $this-> successStatus)
                         ->withHeaders([
                            'Pragma' => "no-cache",
                            'Cache-Control' => 'no-cache, must-revalidate, no-store, max-age=0, private'
                            ]);
    }

    function getProductionReportsOfActiveBatchesUptoDate($date) {

        $batches = DB::table('batches')
                        ->where([
                            ['batches.startDate', '<=', $date],
                            ['batches.endDate', '=', null]
                            ])
                        ->join('houses', 'houses.id', '=', 'batches.houseId')
                        ->select('batches.id', 'batches.batch', 'batches.houseId', 'batches.startDate', 'batches.initialBirdBalance', 'batches.startAge', 'houses.name')
                        ->orderBy('houses.name', 'ASC')
                        ->get();

        foreach ($batches as $batch) {
            $productionByDate = DB::table('productions')
                                ->where([
                                    ['batchId', '=', $batch->id],
                                    ['productions.reportDate', '=', $date]
                                    ])
                                ->first();

            $totals = DB::table('productions')
                     ->where([
                        ['batchId', '=', $batch->id],
                        ['productions.reportDate', '<', $date]
                        ])
                    ->select(DB::raw('SUM(mortality) as totalMortality, SUM(cull) as totalCull, SUM(eggProduction) as totalProduction'))
                    ->first();        


            $batch->productionByDate = $productionByDate;
            $batch->totals = $totals; 
        }

        return response()->json($batches, $this-> successStatus)
                         ->withHeaders([
                            'Pragma' => "no-cache",
                            'Cache-Control' => 'no-cache, must-revalidate, no-store, max-age=0, private'
                            ]);
    }

    function getProductionReportsByHouseId($houseId) {
        $batch = DB::table('batches')
                        ->where([
                            ['batches.endDate', '=', null],
                            ['batches.houseId', '=', $houseId]
                            ])
                        ->join('houses', 'houses.id', '=', 'batches.houseId')
                        ->select('batches.id', 'batches.batch', 'batches.houseId', 'batches.startDate', 'batches.initialBirdBalance', 'batches.startAge', 'houses.name', 'houses.stockman')
                        ->first();

        if($batch != null) {
            $productions = DB::table('productions')
                        ->where('batchId', $batch->id)
                        ->orderBy('productions.reportDate', 'ASC')
                        ->get();

            $batch->productions = $productions; 

            return response()->json($batch, $this-> successStatus)
                             ->withHeaders([
                                'Pragma' => "no-cache",
                                'Cache-Control' => 'no-cache, must-revalidate, no-store, max-age=0, private'
                                ]);
        } else {
            abort(500, 'No active batch found for houseId: '.$houseId);
        } 
    }

     function getProductionReportsByBatchId($batchId) {
        $batch = DB::table('batches')
                        ->where('batches.id', $batchId)
                        ->join('houses', 'houses.id', '=', 'batches.houseId')
                        ->select('batches.id', 'batches.batch', 'batches.houseId', 'batches.startDate', 'batches.initialBirdBalance', 'batches.startAge', 'houses.name', 'houses.stockman')
                        ->first();

        if($batch != null) {
            $productions = DB::table('productions')
                        ->where('batchId', $batch->id)
                        ->orderBy('productions.reportDate', 'ASC')
                        ->get();

            $batch->productions = $productions; 

            return response()->json($batch, $this-> successStatus)
                             ->withHeaders([
                                'Pragma' => "no-cache",
                                'Cache-Control' => 'no-cache, must-revalidate, no-store, max-age=0, private'
                                ]);
        } else {
            abort(500, 'No batch found for batchId: '.$batchId);
        } 
    }

    function createUpdateProductionReport(Request $request) {
        $user = Auth::user(); 
        
        $production = Production::find($request->id);
        
        if($production != null) {
            $production->reportDate = $request->input('reportDate');
            $production->feeds = $request->input('feeds');
            $production->eggProduction = $request->input('eggProduction');
            $production->cull = $request->input('cull');
            $production->mortality = $request->input('mortality');
            $production->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
            $production->lastInsertUpdateTS = Carbon::now();
            $production->save();

            return response()->json($production);
        } else {
            $production = new Production;
            $production->batchId = $request->input('batchId');
            $production->houseId = $request->input('houseId');
            $production->reportDate = $request->input('reportDate');
            $production->feeds = $request->input('feeds');
            $production->eggProduction = $request->input('eggProduction');
            $production->cull = $request->input('cull');
            $production->mortality = $request->input('mortality');
            $production->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
            $production->lastInsertUpdateTS = Carbon::now();
            $production->save();
        }

        return response()->json($production, $this-> successStatus);
    }

    function deleteProductionReport($id) {

        DB::table('productions')->where('id', '=', $id)->delete();

        return response()->json(['success'=>true])
                         ->withHeaders([
                            'Pragma' => "no-cache",
                            'Cache-Control' => 'no-cache, must-revalidate, no-store, max-age=0, private'
                            ]);;
    }
}
