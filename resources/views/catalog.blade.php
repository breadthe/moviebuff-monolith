@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card">
                <div class="card-header d-flex">
                    <div class="flex-grow-1 align-self-center">
                        <a href="{{ route('catalogs') }}">Catalogs</a>
                        &nbsp;&gt;&nbsp;
                        {{ $catalog->name }}
                    </div>
                </div>

                <div class="card-body">
                    @if (count($movies))
                        <movie-items :catalog-id="{{ $catalog->id }}" :movies="{{ $movies }}"></movie-items>
                    @else
                        There are no movies in this catalog.
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
