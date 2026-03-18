@extends('layouts.app')
@section('title', 'Modifier l\'annonce — AirsoftPACA')
@section('content')
@include('listings._form', ['listing' => $listing, 'categories' => $categories])
@endsection
