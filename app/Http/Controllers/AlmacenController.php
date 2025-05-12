<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Http\Requests\StoreAlmacenRequest;
use App\Http\Requests\UpdateAlmacenRequest;
use App\Http\Resources\AlmacenResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Almacen::class);
        return Inertia::render('panel/almacen/indexAlmacen');
    }
    public function listarAlmacens(Request $request)
    {
        Gate::authorize('viewAny', Almacen::class);
        try {
            $name = $request->get('name');
            $almacens = Almacen::when($name, function ($query, $name) {
                return $query->where('name', 'like', "%$name%");
            })->orderBy('id','asc')->paginate(12);

            return response()->json([
                'almacens' => AlmacenResource::collection($almacens),
                'pagination' => [
                    'total' => $almacens->total(),
                    'current_page' => $almacens->currentPage(),
                    'per_page' => $almacens->perPage(),
                    'last_page' => $almacens->lastPage(),
                    'from' => $almacens->firstItem(),
                    'to' => $almacens->lastItem()
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al listar los almacenes',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function create()
    {
        return Inertia::render('panel/almacen/components/formAlmacen');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlmacenRequest $request)
    {
        Gate::authorize('create', Almacen::class);
        $validated = $request->validated();
        $validated = $request->safe()->except(['state']);
        $almacen = Almacen::create(Arr::except($validated, ['state']));
        return redirect()->route('panel.almacens.index')->with('message', 'Almacen creado correctamente'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Almacen $almacen)
    {
        Gate::authorize('view', $almacen);
        return response()->json([
            'state' => true,
            'message' => 'Almacen encontrado',
            'almacen' => new AlmacenResource($almacen),
        ], 200);
    }

    public function update(UpdateAlmacenRequest $request, Almacen $almacen)
    {
        Gate::authorize('update', $almacen);
        $validated = $request->validated();
        $validated['state'] = ($validated['state'] ?? 'inactivo') === 'activo';
        $almacen->update($validated);
        return response()->json([
            'state' => true,
            'message' => 'Almacen actualizado de manera correcta',
            'almacen' => new AlmacenResource($almacen->refresh()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Almacen $almacen)
    {
        Gate::authorize('delete', $almacen);
        $almacen->delete();
        return response()->json([
            'state' => true,
            'message' => 'Almacen eliminado de manera correcta',
        ]);
    }
     // Método para obtener solo el ID y el nombre de los almacenes
    public function getAlmacensOption()
    {
        try {
            // Obtener todos los almacenes con solo los campos id y name
            $almacens = Almacen::select('id', 'name')->get();

            return response()->json([
                'almacens' => $almacens
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener los almacenes',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
