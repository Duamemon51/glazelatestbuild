@extends('layouts.app')

@section('title', 'Home | Ricona')

@section('content')

    {{-- Hero Section Include --}}
    @include('components.hero')

   
 @include('components.favorites')

  @include('components.categories')
    {{-- Design Section Include --}}
    @include('components.design')
 @include('components.top-sellers')
@endsection
