<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function show()
    {
        echo '123456';
        return view('index.welcome');
    }
}