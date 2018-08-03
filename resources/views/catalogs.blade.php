@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card">
                <div class="card-header d-flex">
                    <div class="flex-grow-1 align-self-center">
                        Catalogs
                    </div>
                    <div>
                        <!-- <button type="button" class="btn btn-primary">NEW</button> -->
                    </div>
                </div>

                <div class="card-body">
                    <p>These are your catalogs that you have created to organize your movies.</p>
                    <ul class="list-group list-group-flush">
                        @foreach ($catalogs as $catalog)
                            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <a href="{{ route('catalog', ['catalog' => $catalog]) }}">{{ $catalog->name }}</a>
                                @if ($catalog->movies->count())
                                    <span class="badge badge-primary badge-pill">{{ $catalog->movies->count() }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
