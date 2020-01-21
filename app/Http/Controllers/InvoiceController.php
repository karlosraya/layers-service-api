<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\InvoiceResource;
use App\Invoice;
use App\Item;
use App\Http\Requests;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function getInvoicesByDate($date)
    {
        if($date!= null) {
            $invoices = Invoice::where('invoiceDate', $date)
                                    ->join('customers', 'customers.id', '=', 'invoices.customerId')
                                    ->select('invoices.*', 'customers.firstName', 'customers.lastName')->get();

            foreach ($invoices as $key => $invoice) {
                 $invoice->items = DB::table('items')->where('invoiceId', '=', $invoice->id)->get();
            }

            return response()->json($invoices);
        } else {
            abort(500, 'No date found from the request!');
        }
    }

    public function getInvoicesById($id)
    {
        if($id!= null) {
            $invoice = Invoice::find($id);

            $items = DB::table('items')->where('invoiceId', '=', $invoice->invoiceId)->get();
            $invoice->items = $items;

            return response()->json($invoice);
        } else {
            abort(500, 'No invoice found for id: ' + $id);
        }
    }

    public function getInvoicesByCustomerId($id)
    {
        $invoices = DB::table('invoices')->where('customerId', '=', $id)
                                         ->select('invoices.*')->get();

        return response()->json($invoices);
    }

    public function createUpdateInvoice(Request $request)
    {
        if($request->id != null) {
            $invoice = Invoice::findOrFail($request->id);
        } else {
            $invoice = new Invoice;
        }

        $invoice->customerId = $request->customerId;
        $invoice->invoiceNumber = $request->invoiceNumber;
        $invoice->invoiceDate = $request->invoiceDate;
        $invoice->subtotal = $request->subtotal;
        $invoice->discount = $request->discount;
        $invoice->total = $request->total;
        $invoice->amountPaid = $request->amountPaid;
        $invoice->lastInsertUpdateBy = $request->input('lastInsertUpdateBy');
        $invoice->lastInsertUpdateTS = Carbon::now();
        $invoice->save();

        $itemModels = [];

        foreach($request->items as $key => $item) {
            $newItem = new Item($item);
            $newItem->invoiceId = $invoice->id;

            if($newItem ->id) {
                $existingItem = Item::find($newItem->id);

                $existingItem->item = $newItem->item;
                $existingItem->description = $newItem->description;
                $existingItem->price = $newItem->price;
                $existingItem->quantity = $newItem->quantity;

                $existingItem->save();

            } else {
                $newItem->save();
            }
        }
        
        return response()->json($invoice);
    }
}
