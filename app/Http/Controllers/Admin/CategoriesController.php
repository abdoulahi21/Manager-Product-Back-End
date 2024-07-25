<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $categorie = Categories::with('produits')->get();
            return response()->json($categorie);
        }catch (\Exception $e) {
                return response()->json([
                        'message' => 'Error: ' .
                        $e->getMessage(),
                        'code' => $e->getCode()
                ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('categorie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
           'libelle'=>'required'
        ]);
        try {
            $categories = new Categories();
            $categories->libelle = $request->libelle;
            $categories->save();
            return response()->json([$categories,
                'message' => 'Categorie created successfully',
                'code' => 200,
            ] );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' .
                    $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categories $categories)
    {
        //
        $request->validate([
            'libelle'=>'required'
        ]);
        $categories->update($request->all());
        return redirect()->route('categorie.index')
            ->withSuccess('categori is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $categories=Categories::find($id);
        $categories->delete();
        return redirect()->route('categorie.index')
            ->withSuccess('categori is deleted successfully.');
    }
}
