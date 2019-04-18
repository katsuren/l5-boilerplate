<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index($path)
    {
        $path = str_replace('/', '.', $path);
        $path = str_replace('..', '.', $path);
        try {
            return view('pages.' . $path);
        } catch (Exception $e) {
            return abort(404);
        }
    }
}
