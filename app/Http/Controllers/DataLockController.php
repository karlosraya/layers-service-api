<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataLock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DataLockResource;

class DataLockController extends Controller
{
    
    public function getLatestLockedDate()
    {
        $latest = DB::table('data_locks')->orderBy('lockDate', 'desc')->first();
        
        if($latest != null) {
            return response()->json($latest);
        } else {
            return response()->json(null);
        }
    } 
    
    public function lockDataByDate($date)
    {
        $user = Auth::user(); 
        
        $lockDate = new DataLock;
        $lockDate->lockDate = $date;
        $lockDate->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
        $lockDate->lastInsertUpdateTS = Carbon::now();
        $lockDate->save();
        
        return response()->json(new DataLockResource($lockDate));
    }
}
