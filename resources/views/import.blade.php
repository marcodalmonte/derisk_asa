@extends('layouts.fancy')

@section('title')
    {{ config('app.name') . ' - ' . $title }}
@endsection

@section('header-title', $title)

@section('main-content')
    <div class="row gutter">
        <form id="import-inspections" 
            enctype="multipart/form-data" 
            method="post" 
            data-bv-message="This value is not valid"
            data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
            data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
            data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

            {{ csrf_field() }}
                        
            <input type="hidden" id="job_number" name="job_number" value="{{ $job_number }}" />
            
            <div class="clearfix"></div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        <h4>Choose your CSV file</h4>
                    </div>
                    <div class="panel-body">
                        <input type="file" id="csv-inspections" name="csv-inspections" value="" />
                        <button type="button" id="import-csv" name="import-csv" onclick="importCSV()" class="btn btn-info">Import</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection