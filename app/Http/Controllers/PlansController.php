<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PlansController extends Controller {

    public function __construct() {

    }

    public function getChoose(){
        return view('plans.choose');
    }
}
