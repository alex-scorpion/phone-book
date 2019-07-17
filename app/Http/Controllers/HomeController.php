<?php

namespace App\Http\Controllers;

use Core\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home()
    {
        return $this->htmlResponse(view('home'));
    }
}
