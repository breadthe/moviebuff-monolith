@extends('layouts.app')

@section('content')

@foreach($data as $movie)
    {{ $movie }}
@endforeach

@endsection