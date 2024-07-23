<?php

/**
 * (ɔ) LARAVEL.Sillo.org - 2015-2024
 */

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
	public function index()
	{
		// return 'ok2';
		return view('welcome');
	}
}
