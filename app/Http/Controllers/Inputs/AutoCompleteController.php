<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class AutoCompleteController extends Controller
{
    public function getCustomerList(Request $request)
    {
        $name = $request->get('texto');

        $customers = Customer::select('id', 'name')
            ->where('state', true)
            ->when($name, function ($query, $name) {
                return $query->whereLike('name', "%$name%");
            })
            ->orderBy('id')
            ->limit(5)
            ->get();
        return response()->json($customers);
    }
}
