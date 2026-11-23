@extends('layout')
@section('title',$title)
@section('header', $header)
@section('conten')
   
<h1>{{ $conten }}</h1>

@if ($ifLogin)

    <p>ini if login</p>
    
@else
    <p>ini else login</p>
@endif

<ol>
    @foreach ($products as $item)
        <li>{{ $item }}</li>
    @endforeach
</ol>


<x-alert>
    ini text alert
</x-alert>

@endsection 

