<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContohController extends Controller
{
    //
    public function index(){
        $title = 'ini adalah halaman product';
        $products =['Laptop','Keyboard','Mouse','Monitor'];
        //return view('contoh',['products' => $products, 'title' => $title]);
        
        //atau bisa disingkat seperti ini 
        return view('contoh',compact('title','products'));
    }
}
