@extends('layouts.app')

@section('content')

<search-page
    search-string="{{ $search_string }}"
    api-key="{{ config('services.omdb.key') }}"
    api-url="{{ config('services.omdb.url') }}"
></search-page>

@endsection