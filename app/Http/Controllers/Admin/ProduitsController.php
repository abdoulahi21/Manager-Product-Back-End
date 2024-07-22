<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Scalar\String_;

class ProduitsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $produit = Produit::all();
            return response()->json($produit);
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'description' => 'required',
            'prix' => 'required',
            'quantite' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories_id'=>'required'
        ]);

        $fileName = time() . '.' . $request->photo->extension();
        $request->photo->storeAs('public/images', $fileName);

        try {
            $produit = new Produit();
            $produit->nom = $request->nom;
            $produit->description = $request->description;
            $produit->prix = $request->prix;
            $produit->quantite = $request->quantite;
            $produit->photo = $fileName;
            $produit->categories_id = $request->categories_id;
            $produit->save();
            return response()->json([$produit,
                'message' => 'Produit created successfully',
                'code' => 200,
            ]);
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
    public function show(String $id)
    {
        $produit = Produit::find($id);
        if (!$produit) {
            return response()->json([
                'message' => 'Produit not found',
                'code' => 404,
            ]);
        }
        return response()->json($produit);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'description' => 'required',
            'prix' => 'required',
            'quantite' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories_id'=>'required'
        ]);
        $produit=Produit::find($request->id);
        $imageName = '';
        if ($request->hasFile('photo')) {
            $imageName = time() . '.' . $request->photo->extension();
            $request->photo->storeAs('public/images', $imageName);
            if ($produit->photo) {
                Storage::delete('public/images/' . $produit->photo);
            }
        } else {
            $imageName = $produit->photo;
        }
        Produit::create([
            'nom' => $request->input('nom'),
            'description' => trim($request->input('description')),
            'prix' => $request->input('prix'),
            'quantite' => $request->input('quantite'),
            'photo' => $imageName,
            'categories_id'=>$request->input('categories_id')
        ]);
        return response()->json([
            'message' => 'Produit updated successfully',
            'code' => 200,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $produits= Produit::find($id);
        if (!$produits) {
            return redirect()->route('produit.index')->with('error', 'produit non trouvé');
        }
        // Supprimer l'image associée s'il y en a une
        if ($produits->photo) {
            Storage::delete('public/images/' . $produits->photo);
        }
        // Supprimer l'étudiant
        $produits->delete();


        return response()->json([
            'message' => 'Produit deleted successfully',
            'code' => 200,
        ]);
    }

  /*  public function export()
    {
        return Excel::download(new ProduitsExport(), 'produits.xlsx');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
   /* public function import()
    {
        Excel::import(new ProduitssImport(),request()->file('file'));

        return back()->withSuccess('Produit is import  successefuly');
    }*/
}
