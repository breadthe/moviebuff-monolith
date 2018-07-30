@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Catalogs</div>

                <div class="card-body">
                    <p>These are your catalogs that you have created to organize your movies.</p>
                    <ul class="list-group list-group-flush">
                        @foreach ($catalogs as $catalog)
                            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                {{ $catalog->name }}
                                <span class="badge badge-primary badge-pill">14</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
