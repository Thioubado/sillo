<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetsController extends Controller
{
    //
    public function index()
	{
		// return 'ok2';
		return view('pet');
	}
}
