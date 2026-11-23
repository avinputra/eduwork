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

    public function coba(){

        $title = 'ini adalah halaman title coba';
        $header = 'ini adalah header coba';
        $conten = 'ini adalah conten coba';
        $ifLogin = false;
        $products =['Laptop','Keyboard','Mouse','Monitor'];
        return view('coba',compact('title','header','conten','ifLogin','products'));
    }

    
}
