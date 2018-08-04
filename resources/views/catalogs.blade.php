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
                    <p>Some generic text describing what these things are, even though it's obvious that they are catalogs used to organize your movies.</p>
                    <ul class="list-group list-group-flush">
                        @foreach ($catalogs as $catalog)
                            <catalog-item :catalog="{{ $catalog }}"></catalog-item>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
