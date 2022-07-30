<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Countries::all();

        if ($countries)
            return response()->json([
                'success' => true,
                'message' => $countries
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko gauti šalių sąrašo'
            ], 500);
    }

    public function show($id, Request $request)
    {
        $country = Countries::where('id', $id);

        if ($country->get())
            return response()->json([
                'success' => true,
                'message' => $country->get()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Šalis su tokiu id nerasta'
            ], 500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $this->validate($request, [
            'name' => 'required',
            'season' => 'required'
        ]);


        $country = new Countries();
        $country->name = $request->name;
        $country->season = $request->season;

        if ($country->save())
            return response()->json([
                'success' => true,
                'message' => $country->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko išsaugoti šalies'
            ], 500);
    }


    public function update($id, Request $request)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $this->validate($request, [
            'name' => 'required',
            'season' => 'required'
        ]);


        $country = Countries::where('id', $id);

        if ($country->update($request->all()))
            return response()->json([
                'success' => true,
                'message' => 'Šalis sėkmingai atnaujinta'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko išsaugoti šalies'
            ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Countries  $countries
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        
        try {
            $country = Countries::where('id', $id);

            $country->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Šalis sėkmingai ištrinta'
            ]);
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Šalis negali būti ištrinta, kadangi yra priskirta prie viešbučio'
            ], 500);
        }
    }
}