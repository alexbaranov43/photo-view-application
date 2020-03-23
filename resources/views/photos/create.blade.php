@extends('layouts.app')
@section('content')
<div class="container">
<h1>Photo Parser</h1>
<div>
    {{-- DOWNLOAD FILE FOR CORRECT CSV --}}
    <br><br>
    <h2>Download This File First</h2>
    <br>
    <a href="{{ route('csv_download') }}">
        <button class="btn btn-info">Download CSV File</button>
    </a>
</div>
  <div class="panel-body">
      {{-- FORM TO PARSE CSV --}}
    <form class="form-horizontal" method="POST"  action="{{ route('csv_import_parse') }}" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="form-group">
            <div>
                <br><br>
                <span>Select the downloaded CSV file to import.</span>
                <br>
            </div>
            <div class="col-md-3 custom-file">
                <input required accept=".csv" id="csv_file" name="csv_file" type="file" class="custom-file-input" id="inputGroupFile01" onchange="labelChange()">
                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
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
  @if (isset($message))
    <div class="alert alert-success col-md-5">
        {{$message }}
        <br><br>
    </div>
    @if ($invalidEntryCount > 0)
    <div class="alert alert-danger col-md-5">
        {{ $warning }}
    </div>
    @endif
  @endif
</div>
@endsection
<script>
    function labelChange(){
        //get the file name for the label display
        var fileName = $('#csv_file').val();
        $('.custom-file-label').html(fileName)
    }

</script>