<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }
}
