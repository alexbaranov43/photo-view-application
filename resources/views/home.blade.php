@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Welcome {{ auth()->user()->name }}</div>
                <div class="card-header card-header-right">
                    <a href="/photos/add"><button class="btn btn-primary">Add CSV File</button></a>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    

                    <photo-index-component></photo-index-component>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
