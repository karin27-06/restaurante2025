<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use App\Models\ClientType;
use App\Models\Category;
 use App\Models\Laboratory;
 use App\Models\Supplier;
 use App\Models\TypeMovement;
 use App\Models\User;
 use Illuminate\Http\Request;

class SelectController extends Controller
{
    // get client_type list
    public function getClientTypeList()
    {
        $clientTypes = ClientType::select('id', 'name')
            ->where('state', 1)
            ->orderBy('id')
            ->get();
        return response()->json($clientTypes);
    }

    // get category list
    public function getCategoryList(){
        $category = Category::select('id', 'name')
            ->orderBy('id')
            ->get();
        return response()->json($category);
    }
}
