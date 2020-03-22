@extends('layouts.app')
@section('content')

<h1>Photos</h1>
  <div class="panel-body">
    <form class="form-horizontal" method="POST"  action="{{ route('csv_import_parse') }}" enctype="multipart/form-data">
        @csrf
        @method('POST')
        
        <div class="form-group">
            <div>
                <br><br>
                <span>Select a CSV file to import.</span>
                <br>
            </div>
            <div class="col-md-6">
                <input id="csv_file" type="file" class="btn" name="csv_file" required accept=".csv">

                @if ($errors->has('csv_file'))
                    <span class="help-block">
                    <strong>{{ $errors->first('csv_file') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <a href="/photos"><button type="submit" class="btn btn-primary">
                    Parse CSV
                </button></a>
            </div>
        </div>
    </form>
  </div>
  <div>
    <br><br>
    <h2>CSV File</h2>
    <br>
    <a href="{{ route('csv_download') }}">
        <button class="btn btn-info">Download CSV File</button>
    </a>
</div>
@endsection