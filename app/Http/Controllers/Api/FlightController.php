<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flight;


class FlightController extends Controller
{
    //
    public function index(Request $request)
    {
        $flights = Flight::query();
    
        if ($request->boardingLocation) {
            $flights->where('takeoff_location', 'like', '%' . $request->boardingLocation . '%');
        }
    
        if ($request->destinationLocation) {
            $flights->where('landing_location', 'like', '%' . $request->destinationLocation . '%');
        }
    
        // if ($request->dateOfTravel) {
        //     // Assuming you have a 'date_of_travel' column in your flights table
        //     $flights->where('date_of_travel', $request->dateOfTravel);
        // }
    
        $flights = $flights->get();
    
        return response()->json($flights);
    }
    
}
