<?php

namespace App\Http\Controllers;

use App\Models\Hotels;
use App\Models\Orders;
use App\Models\Countries;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Orders::where('user_id', auth()->user()->id)->get();

        $generatedOrders = [];

        foreach ($orders as $order) {
            $hotel = Hotels::find($order->hotel_id);
            $country = Countries::find($hotel->country_id);
            $order['hotel_name'] = $hotel->name;
            $order['price'] = $hotel->price;
            $order['photo'] = $hotel->photo;
            $order['travel_duration'] = $hotel->travel_duration;
            $order['country_name'] = $country->name;
            $generatedOrders[] = $order;
        }

        if ($generatedOrders) {
            return response()->json([
                'success' => true,
                'message' => $generatedOrders
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko gauti užsakymų sąrašo'
            ], 500);
        }
    }

    public function all()
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $orders = Orders::all();

        $generatedOrders = [];

        foreach ($orders as $order) {
            $hotel = Hotels::find($order->hotel_id);
            $country = Countries::find($hotel->country_id);
            $order['hotel_name'] = $hotel->name;
            $order['price'] = $hotel->price;
            $order['photo'] = $hotel->photo;
            $order['travel_duration'] = $hotel->travel_duration;
            $order['country_name'] = $country->name;
            $generatedOrders[] = $order;
        }

        return response()->json([
            'success' => true,
            'message' => $generatedOrders
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'hotel_id' => 'required'
        ]);


        $order = new Orders();
        $order->hotel_id = $request->hotel_id;
        $order->approved = 0;
        $order->user_id = auth()->user()->id;

        if ($order->save())
            return response()->json([
                'success' => true,
                'message' => $order->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko sukurti užsakymo'
            ], 500);
    }

    public function approve($id, Request $request)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $order = Orders::where('id', $id);

        if ($order->update(['approved', 1]))
            return response()->json([
                'success' => true,
                'message' => 'Užsakymas sėkmingai patvirtintas'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko patvirtinti užsakymo'
            ], 500);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Orders $orders)
    {
        //Authentification
        if (auth()->user()->role != 0)
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);

        $order = Orders::where('id', $id);

        if ($order->delete())
            return response()->json([
                'success' => true,
                'message' => 'Užsakymas sėkmingai ištrintas'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Nepavyko ištrinti užsakymo'
            ], 500);
    }
}