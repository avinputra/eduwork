@extends('layout')
@section('title', 'ini adalah title halaman product')
@section('header', $title)
@section('conten')



<hr>
    <ol>
        @foreach ($products as $value)

            <li>
                {{ $value }}
            </li>
            
        @endforeach
    </ol>
<hr>

    
@endsection
    



