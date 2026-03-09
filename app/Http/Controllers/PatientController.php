<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::paginate(10);

        return response()->json($patients);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'gender' => 'required|in:male,female',
            'diagnosis' => 'required|string',
            'symptoms' => 'required|string',
        ]);

        $patient = Patient::create($validated);

        return response()->json($patient);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return response()->json($patient);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'gender' => 'required|in:male,female',
            'diagnosis' => 'required|string',
            'symptoms' => 'required|string',
        ]);

        $patient->update($validated);

        return response()->json($patient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json(['message' => 'Patient deleted successfully']);
    }
}
