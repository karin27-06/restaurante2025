<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFloorRequest;
use App\Http\Requests\UpdateFloorRequest;
use App\Http\Resources\FloorResource;
use App\Models\Floor;
use App\Pipelines\FilterByName;
use App\Pipelines\FilterById;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Floor::class);
        return Inertia::render('panel/floor/indexFloor');
    }

    public function listarFloors(Request $request)
    {
        // authorization so you can access the method

        Gate::authorize('viewAny', Floor::class);

        try {
            $name = $request->get('name');
            $id = $request->get('id');
            $floors = app(Pipeline::class)
                ->send(Floor::query())
                ->through([
                    new FilterByName($name),
                    new FilterById($id),
                ])
                ->thenReturn()->orderBy('id','asc')->paginate(12);
            return response()->json([
                'floors'=> FloorResource::collection($floors),
                'pagination' => [
                    'total' => $floors->total(),
                    'current_page' => $floors->currentPage(),
                    'per_page' => $floors->perPage(),
                    'last_page' => $floors->lastPage(),
                    'from' => $floors->firstItem(),
                    'to' => $floors->lastItem(),
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al listar los pisos',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('panel/floor/components/formFloor');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFloorRequest $request)
    {
        Gate::authorize('create', Floor::class);
        $validated = $request->validated();
        $validated = $request->safe()->except(['state']);
        $floor = Floor::create(Arr::except($validated, ['state']));
        // // $validated['state'] = $validated['state'] === 'activo' ? true : false;
        return redirect()->route('panel.floors.index')->with('message', 'Piso creado correctamente');   
    }

    /**
     * Display the specified resource.
     */
    public function show(Floor $floor)
    {
        Gate::authorize('view', $floor);
        return response()->json([
            'state' => true,
            'message' => 'Categoría encontrada',
            'floor' => new FloorResource($floor),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFloorRequest $request, Floor $floor)
    {
        Gate::authorize('update', $floor);
        $validated = $request->validated();
        $validated['state'] = ($validated['state'] ?? 'inactivo') === 'activo';
        $floor->update($validated);
        return response()->json([
            'state' => true,
            'message' => 'Piso actualizado correctamente',
            'floor' => new FloorResource($floor->refresh()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Floor $floor)
    {
        Gate::authorize('delete', $floor);
        $floor->delete();
        return response()->json([
            'state' => true,
            'message' => 'Piso eliminada correctamente',
        ]);
    }

         // Método para obtener solo el ID y el nombre de los pisos
    public function getCategoriesOption()
    {
        try {
            // Obtener todos los pisos con solo los campos id y name
            $floors = Floor::select('id', 'name')->get();

            return response()->json([
                'floors' => $floors
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener los pisos',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
