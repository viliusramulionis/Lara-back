<?php

namespace App\Http\Controllers;

use App\Models\Hotels;
use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hotels = Hotels::all();

        foreach ($hotels as $hotel) {
            $country = Countries::find($hotel->country_id);
            $hotel->country = $country->name;
        }

        if ($hotels)
            return response()->json([
                'success' => true,
                'message' => $hotels
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko gauti viešbučių sąrašo'
            ], 500);
    }
    public function show($id, Request $request)
    {

        $hotel = Hotels::where('id', $id);

        if ($hotel->get())
            return response()->json([
                'success' => true,
                'message' => $hotel->get()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko rasti viešbučio'
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
            'price' => 'required',
            'photo' => 'required',
            'travel_duration' => 'required',
            'country_id' => 'required'
        ]);
        $hotel = new Hotels();
        $hotel->name = $request->name;
        $hotel->price = $request->price;
        $hotel->travel_duration = $request->travel_duration;
        if ($request->country_id !== '0')
            $hotel->country_id = $request->country_id;


        $uploadedFile = $request->file('photo');
        $filename = time() . $uploadedFile->getClientOriginalName();
        $filepath = str_replace(' ', '_', $filename);
        $storage = Storage::disk('local')->putFileAs(
            'public',
            $uploadedFile,
            $filepath
        );

        if (!$storage)
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko išsaugoti viešbučio nuotraukos'
            ], 500);

        $hotel->photo = '/storage/' . $filepath;

        if ($hotel->save())
            return response()->json([
                'success' => true,
                'message' => $hotel->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko išsaugoti viešbučio'
            ], 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hotels  $hotels
     * @return \Illuminate\Http\Response
     */
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
            'price' => 'required',
            'photo' => 'required',
            'travel_duration' => 'required',
            'country_id' => 'required'
        ]);

        $hotel = Hotels::find($id);
        $data = [];

        $data['name'] = $request->name;
        $data['price'] = $request->price;
        $data['travel_duration'] = $request->travel_duration;

        if ($request->country_id !== '0')
            $data['country_id'] = $request->country_id;


        $uploadedFile = $request->file('photo');
        $filename = time() . $uploadedFile->getClientOriginalName();
        $filepath = str_replace(' ', '_', $filename);
        $storage = Storage::disk('local')->putFileAs(
            'public',
            $uploadedFile,
            $filepath
        );

        if (!$storage)
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko išsaugoti viešbučio nuotraukos'
            ], 500);

        $data['photo'] = '/storage/' . $filepath;

        if ($hotel->update($data))
            return response()->json([
                'success' => true,
                'message' => $hotel->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko atnaujinti viešbučio'
            ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hotels  $hotels
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $hotel = Hotels::where('id', $id);

        if ($hotel->delete())
            return response()->json([
                'success' => true,
                'message' => 'Viešbutis sėkmingai ištrintas'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko ištrinti viešbučio'
            ], 500);
    }

    public function byCountry($id)
    {
        $hotels = Hotels::where('country_id', $id);

        if ($hotels->get())
            return response()->json([
                'success' => true,
                'message' => $hotels->get()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko gauti viešbučių sąrašo'
            ], 500);
    }

    public function sortByPrice()
    {
        $hotels = Hotels::orderBy('price');

        if ($hotels->get())
            return response()->json([
                'success' => true,
                'message' => $hotels->get()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko gauti viešbučių sąrašo'
            ], 500);
    }
    public function search($keyword)
    {
        $hotels = Hotels::where('name', 'like', '%' . $keyword . '%');

        if ($hotels->get())
            return response()->json([
                'success' => true,
                'message' => $hotels->get()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko gauti viešbučių sąrašo'
            ], 500);
    }
}