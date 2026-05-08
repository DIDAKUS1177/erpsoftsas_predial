<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorRequestController extends Controller
{
    public function error_request() {
        return redirect('/');
    }
}
