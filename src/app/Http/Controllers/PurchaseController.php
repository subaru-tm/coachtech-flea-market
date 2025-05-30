<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function purchase($item_id) {
        return view('purchase');
    }
}
