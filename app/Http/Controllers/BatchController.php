<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\BatchResource;
use App\Batch;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 

class BatchController extends Controller
{
    public $successStatus = 200;

    public function getActiveByHouseId($id)
    {
        $batch = DB::table('batches')->where([
                    ['houseId', '=', $id],
                    ['endDate', '=', null]
                ])->first();
        
        if($batch != null) {
            return response()->json($batch, $this-> successStatus);
        } else {
            return null;
        }
    }

    public function editBatch(Request $request, $id)
    {
        $user = Auth::user(); 

        $batch = Batch::findOrFail($id);
        
        $batch->batch = $request->input('batch');
        $batch->startDate = $request->input('startDate');
        $batch->initialBirdBalance = $request->input('initialBirdBalance');
        $batch->startAge = $request->input('startAge');
        $batch->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
        $batch->lastInsertUpdateTS = Carbon::now();
        $batch->save();

        return response()->json($batch, $this-> successStatus);
    }

    public function startBatch(Request $request)
    {
        $user = Auth::user(); 

        $batch = new Batch;
        
        $batch->houseId = $request->input('houseId');
        $batch->batch = $request->input('batch');
        $batch->startDate = $request->input('startDate');
        $batch->initialBirdBalance = $request->input('initialBirdBalance');
        $batch->startAge = $request->input('startAge');
        $batch->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
        $batch->lastInsertUpdateTS = Carbon::now();
        $batch->save();

        return response()->json($batch, $this-> successStatus);
    }

    public function endBatch(Request $request, $id)
    {
        $user = Auth::user(); 

        $batch = Batch::findOrFail($id);
        
        $batch->endDate = $request->input('endDate');
        $batch->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
        $batch->lastInsertUpdateTS = Carbon::now();
        $batch->save();

        return response()->json($batch, $this-> successStatus);
    }

    public function getBatchesByHouseId($houseId) {
        $batches = DB::table('batches')->where('houseId', $houseId)->get();

        return response()->json($batches, $this-> successStatus);
    }
}