@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - ' . $title }}
@endsection

@section('header-title', $title)

@section('main-content')
    <div class="row gutter">
        <form id="add-issue" 
            method="post" 
            data-bv-message="This value is not valid"
            data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
            data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
            data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

            {{ csrf_field() }}
                        
            <input type="hidden" id="job_number" name="job_number" value="{{ $job_number }}" />
            <input type="hidden" id="ukas_number" name="ukas_number" value="{{ $ukasnumber }}" />
            
            <div class="clearfix"></div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        <a href="/issues/{{ $ukasnumber }}/new" id="new-issue-link" class="btn btn-info btn-lg">New Issue</a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="issuesTable" class="table table-striped table-bordered no-margin responsiveTable">
                                <thead>
                                    <tr>
                                        <th>Issue Number</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
    @foreach ($issues as $curissue)
                                    <tr id="issue_{{ $curissue->id }}" class="issue_tr">
                                        <td>
                                            <a href="/issues/{{ $ukasnumber }}/{{ $curissue->revision }}">{{ $curissue->revision }}</a>
                                        </td>
                                        <td>
                                            <a href="/issues/{{ $ukasnumber }}/{{ $curissue->revision }}" title="Update issue information" class="actions new_entity update">
                                                <img src="/img/update.png" class="img_actions" alt="Update issue information" title="Update issue information" />
                                            </a>
                                            <a href="javascript:;" title="Delete Issue" onclick="deleteSurveyReportRevision({{ $curissue->id }})" class="actions delete-survey-issue">
                                                <img src="/img/delete.png" class="img_actions" alt="Delete Issue" title="Delete Issue" />
                                            </a>
                                        </td>
                                    </tr>
    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="dialog-confirm" title="Are you sure?">
        <p><span class="ui-icon ui-icon-alert"></span>Are you sure to delete this issue?</p>
    </div>
@endsection
