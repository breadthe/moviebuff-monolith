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
                        <!-- <ul class="list-group list-group-flush">
                            @foreach ($movies as $movie)
                                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    {{ $movie->title }}
                                </li>
                            @endforeach
                        </ul> -->
                        @foreach ($movies as $movie)
                            <div class="movie-item">
                                <div class="left-side">
                                    @if ($movie->poster)
                                        <img src="{{ $movie->poster }}">
                                    @else
                                        <img src="http://via.placeholder.com/75x100?text=NO+IMAGE">
                                    @endif
                                </div>
                                <div class="right-side">
                                    <div class="movie-year is-size-6">{{ $movie->year }}</div>
                                    <div class="movie-title is-size-4 has-text-black">{{ $movie->title }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        There are no movies in this catalog.
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
