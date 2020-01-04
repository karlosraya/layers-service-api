<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\GradedEggsResource;
use App\GradedEggs;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 

class GradedEggsController extends Controller
{
    public $successStatus = 200;

    public function getGradedEggsByDate($date)
    {
        if($date!= null) {
            //graded eggs input by date
            $gradedEggsData = DB::table('graded_eggs')
                                ->where('inputDate', '=', $date)
                                ->first();

            //graded eggs input prior to date
            $gradedEggs = DB::table('graded_eggs')
                            ->where('inputDate', '<', $date);

            //total sales data
            $totalSalesData = DB::table('invoices')
                                ->where('invoiceDate', '=', $date)
                                ->join('items', 'invoices.id', '=', 'items.invoiceId')
                                ->get();

            //total sales prior to date
            $totalSales = DB::table('invoices')
                            ->selectRaw('items.item, sum(quantity) as total')
                            ->where('invoiceDate', '<', $date)
                            ->join('items', 'invoices.id', '=', 'items.invoiceId')
                            ->groupBy('items.item')
                            ->get();

            //total egg productions priort to date
            $totalEggProductions = DB::table('productions')
                                    ->where('reportDate', '<', $date)
                                    ->sum('eggProduction');

            //total egg productions by date
            $eggProductions = DB::table('productions')
                                    ->where('reportDate', '=', $date)
                                    ->sum('eggProduction');

            $gradedEggsProduction = new class{};
            $gradedEggsTotal = new class{};

            if($gradedEggsData != null) {
                $gradedEggsProduction->id  = $gradedEggsData->id;
                $gradedEggsProduction->pww = $gradedEggsData->pww;
                $gradedEggsProduction->pw = $gradedEggsData->pw;
                $gradedEggsProduction->pullets = $gradedEggsData->pullets;
                $gradedEggsProduction->small = $gradedEggsData->small;
                $gradedEggsProduction->medium = $gradedEggsData->medium;
                $gradedEggsProduction->large = $gradedEggsData->large;
                $gradedEggsProduction->extraLarge = $gradedEggsData->extraLarge;
                $gradedEggsProduction->jumbo = $gradedEggsData->jumbo;
                $gradedEggsProduction->crack = $gradedEggsData->crack;
                $gradedEggsProduction->spoiled = $gradedEggsData->spoiled;
            }

            if(count($gradedEggs->get()) > 0) {
                $gradedEggsTotal->pww = intval($gradedEggs->sum('pww'));
                $gradedEggsTotal->pw = intval($gradedEggs->sum('pw'));
                $gradedEggsTotal->pullets = intval($gradedEggs->sum('pullets'));
                $gradedEggsTotal->small = intval($gradedEggs->sum('small'));
                $gradedEggsTotal->medium = intval($gradedEggs->sum('medium'));
                $gradedEggsTotal->large = intval($gradedEggs->sum('large'));
                $gradedEggsTotal->extraLarge = intval($gradedEggs->sum('extraLarge'));
                $gradedEggsTotal->jumbo = intval($gradedEggs->sum('jumbo'));
                $gradedEggsTotal->crack = intval($gradedEggs->sum('crack'));
                $gradedEggsTotal->spoiled = intval($gradedEggs->sum('spoiled'));
            }

            $response = new class{};
            $response->gradedEggsProduction = $gradedEggsProduction;
            $response->gradedEggsTotal = $gradedEggsTotal;
            $response->totalDailySales = $totalSalesData;
            $response->totalSales = $totalSales;
            $response->totalEggProductions = intval($totalEggProductions);
            $response->eggProductions = intval($eggProductions);

            return response()->json($response, $this-> successStatus);
        } else {
            abort(500, 'No date found from the request!');
        }
    }

    public function getAvailableByDate($date) {
        if($date!= null) {
            $gradedEggs = DB::table('graded_eggs')->where('inputDate', '<=', $date);
            $totalSales = DB::table('invoices')->selectRaw('items.item, sum(quantity) as total')
                                                   ->where('invoiceDate', '<', $date)
                                                   ->join('items', 'invoices.id', '=', 'items.invoiceId')
                                                   ->groupBy('items.item')->get();

            $gradedEggsTotal = new class{};

            if(count($gradedEggs->get()) > 0) {
                $gradedEggsTotal->pww = intval($gradedEggs->sum('pww'));
                $gradedEggsTotal->pw = intval($gradedEggs->sum('pw'));
                $gradedEggsTotal->pullets = intval($gradedEggs->sum('pullets'));
                $gradedEggsTotal->small = intval($gradedEggs->sum('small'));
                $gradedEggsTotal->medium = intval($gradedEggs->sum('medium'));
                $gradedEggsTotal->large = intval($gradedEggs->sum('large'));
                $gradedEggsTotal->extraLarge = intval($gradedEggs->sum('extraLarge'));
                $gradedEggsTotal->jumbo = intval($gradedEggs->sum('jumbo'));
                $gradedEggsTotal->crack = intval($gradedEggs->sum('crack'));
                $gradedEggsTotal->spoiled = intval($gradedEggs->sum('spoiled'));
            }

            $response = new class{};
            $response->gradedEggsTotal = $gradedEggsTotal;
            $response->totalSales = $totalSales;

            return response()->json($response, $this-> successStatus);
        } else {
            abort(500, 'No date found from the request!');
        }

    }

    public function createUpdateGradedEggs(Request $request)
    {
        $user = Auth::user(); 

        if($request->id != null) {
            $gradedEggs = GradedEggs::findOrFail($request->id);
        } else {
            $gradedEggs = new GradedEggs;
        }
        
        $gradedEggs->inputDate = $request->input('inputDate');
        $gradedEggs->pww = $request->input('pww');
        $gradedEggs->pw = $request->input('pw');
        $gradedEggs->pullets = $request->input('pullets');
        $gradedEggs->small = $request->input('small');
        $gradedEggs->medium = $request->input('medium');
        $gradedEggs->large = $request->input('large');
        $gradedEggs->extraLarge = $request->input('extraLarge');
        $gradedEggs->jumbo = $request->input('jumbo');
        $gradedEggs->crack = $request->input('crack');
        $gradedEggs->spoiled = $request->input('spoiled');
        $gradedEggs->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
        $gradedEggs->lastInsertUpdateTS = Carbon::now();
        $gradedEggs->save();

        return response()->json(new GradedEggsResource($gradedEggs), $this-> successStatus);
    }
}