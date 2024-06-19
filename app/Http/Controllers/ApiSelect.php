<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiSelect extends Controller
{
    public function getDate()
    {
        $dates = [];
        for ($year = 1990; $year <= 2024; $year++) {
            $dates[] = [
                'id' => $year.'-01-01',
                'year' => $year
            ];
        }

        return response()->json($dates);
    }
}
