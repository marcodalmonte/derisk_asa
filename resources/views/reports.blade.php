@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - ' . $title }}
@endsection

@section('header-title', $title)

@section('main-content')
    <div class="row gutter">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-body">
                    <form id="add-file" 
                        method="post" 
                        action="{{ URL::to('/saveFile') }}"
                        data-bv-message="This value is not valid"
                        data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                        data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                        data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

                        {{ csrf_field() }}

                        <input type="hidden" id="job_number_file" name="job_number" value="{{ $job_number }}" />
                        
                        <table id="responsiveTableFile" class="table table-striped table-bordered no-margin responsiveTable">
                            <thead>
                                <tr>
                                    <th>Link</th>
                                    <th>Comments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="file" id="upload_file" name="upload_file" class="hide" value="" accept="application/pdf" /> 
                                        <label id="label_file" for="upload_file" class="btn btn-info">Select file</label>
                                    </td>
                                    <td><textarea id="new-file-comments" name="new-file-comments" class="form-control" rows="3" title="Comments" placeholder="Comments"></textarea></td>
                                    <td><button type="button" id="save-file" name="save-file" onclick="saveFile()" class="btn btn-info">Save</button></td>
                                </tr>
                                @foreach ($files as $file)
                                <tr>
                                    <td><a href="/files/{{ $file->path }}" target="_blank" title="{{ $file->comments }}">{{ $file->path }}</a></td>
                                    <td>{{ $file->comments }}</td>
                                    <td>
                                        <a href="javascript:;" title="Remove File" rel="{{ $file->id }}" class="actions remove-file">
                                            <img src="/img/delete.png" class="img_actions" alt="Remove File" title="Remove File" />
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-body">
                    <form id="add-report" 
                        method="post" 
                        action="{{ URL::to('/saveReport') }}"
                        data-bv-message="This value is not valid"
                        data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                        data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                        data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

                        {{ csrf_field() }}

                        <input type="hidden" id="job_number_report" name="job_number" value="{{ $job_number }}" />
                        
                        <table id="responsiveTableReport" class="table table-striped table-bordered no-margin responsiveTable">
                            <thead>
                                <tr>
                                    <th>Link</th>
                                    <th>Comments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="file" id="upload_report" name="upload_report" class="hide" value="" accept="application/pdf" /> 
                                        <label id="label_file" for="upload_report" class="btn btn-info">Select file</label>
                                    </td>
                                    <td><textarea id="new-report-comments" name="new-report-comments" class="form-control" rows="3" title="Comments" placeholder="Comments"></textarea></td>
                                    <td><button type="button" id="save-report" name="save-report" onclick="saveReport()" class="btn btn-info">Save</button></td>
                                </tr>
                                @foreach ($reports as $report)
                                <tr>
                                    <td><a href="/reports/{{ $report->path }}" target="_blank" title="{{ $report->comments }}">Issue {{ $report->issue }}</a></td>
                                    <td>{{ $report->comments }}</td>
                                    <td>
                                        <a href="javascript:;" title="Remove Report" rel="{{ $report->id }}" class="actions remove-report">
                                            <img src="/img/delete.png" class="img_actions" alt="Remove Report" title="Remove Report" />
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection