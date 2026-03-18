@extends('layouts.app')
@section('title', 'Déposer une annonce — AirsoftPACA')
@section('content')
@include('listings._form', ['listing' => null, 'categories' => $categories])
@endsection
