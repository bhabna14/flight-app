<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flight;

class FlightController extends Controller
{
    //
 
    public function create()
    {
        return view('admin.add-flight'); // This points to resources/views/admin/add-flight.blade.php
    }

    // Store a new flight in the database
    public function store(Request $request)
    {
        $request->validate([
            'flight_name' => 'required|string|max:255',
            'takeoff_location' => 'required|string|max:255',
            'landing_location' => 'required|string|max:255',
            'operating_days' => 'required|array',
        ]);

        Flight::create([
            'flight_name' => $request->flight_name,
            'takeoff_location' => $request->takeoff_location,
            'landing_location' => $request->landing_location,
            'operating_days' => implode(',', $request->operating_days), // Store as a comma-separated string
        ]);

        return redirect()->route('admin.addflight')->with('success', 'Flight added successfully!');
    }
    public function mngflight()
{
    $flights = Flight::all(); // Fetch all flight data
    return view('admin.manage-flight', compact('flights')); // Pass data to view
}
public function edit($id)
{
    $flight = Flight::findOrFail($id);
    return view('admin.edit-flight', compact('flight'));
}

public function update(Request $request, $id)
{
    $flight = Flight::findOrFail($id);

    // Validate the incoming request data
    $validatedData = $request->validate([
        'flight_name' => 'required|string|max:255',
        'takeoff_location' => 'required|string|max:255',
        'landing_location' => 'required|string|max:255',
        'operating_days' => 'required|array', // Change to array
        'operating_days.*' => 'string|max:255', // Each selected day must be a string
    ]);

    // Convert the array of operating days into a comma-separated string
    $validatedData['operating_days'] = implode(',', $validatedData['operating_days']);

    // Update the flight with the validated data
    $flight->update($validatedData);

    return redirect()->route('admin.mngflight')->with('success', 'Flight updated successfully.');
}


public function destroy($id)
{
    $flight = Flight::findOrFail($id);
    $flight->delete();

    return redirect()->route('admin.mngflight')->with('success', 'Flight deleted successfully.');
}
}
