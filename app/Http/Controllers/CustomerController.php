<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use App\Customer;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 

class CustomerController extends Controller
{
    public $successStatus = 200;

    public function getCustomers()
    {
        $customers = Customer::all();
        return response()->json(CustomerResource::collection($customers), $this-> successStatus);
    }

    public function createUpdateCustomer(Request $request)
    {
        $user = Auth::user(); 

        if($request->id != null) {
            $customer = Customer::findOrFail($request->id);
        } else {
            $customer = new Customer;
        }

        $customer->firstName = $request->input('firstName');
        $customer->lastName = $request->input('lastName');
        $customer->address = $request->input('address');
        $customer->email = $request->input('email');
        $customer->phoneNumber = $request->input('phoneNumber');
        $customer->companyName = $request->input('companyName');
        $customer->lastInsertUpdateBy = $user->firstName.' '.$user->lastName;
        $customer->lastInsertUpdateTS = Carbon::now();
        $customer->save();

        return response()->json(new CustomerResource($customer), $this-> successStatus);
    }
}
