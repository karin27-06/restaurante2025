<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use App\Models\ClientType;
use App\Models\Category;
 use App\Models\Product;
 use App\Models\Floor;
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

    // Obtener lista de productos
    public function getProductList()
    {
        $products = Product::select('id', 'name', 'category_id', 'state')
            ->with('category') // Para incluir la categoría asociada al producto
            ->orderBy('id')
            ->get();
        
        return response()->json($products);
    }

    // Obtener lista de pisos
    public function getFloorList(){
        $floor = Floor::select('id', 'name')
            ->orderBy('id')
            ->get();
        return response()->json($floor);
    }
}
