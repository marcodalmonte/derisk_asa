@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - ' . $title }}
@endsection

@section('header-title', $title)

@section('main-content')

<div class="row gutter">
    <form id="add-report-issue"
        enctype="multipart/form-data" 
        method="post" 
        action="{{ URL::to('/saveReportIssue') }}">

        {{ csrf_field() }}
        
        <div class="form-group" style="clear:left;float:left;margin-left:15px;">
            <button type="button" id="save-revision-start" name="save-report-revision" onclick="submitNewReportIssueForm()" class="btn btn-info btn-lg">Save</button>
        </div>

        <div class="clearfix"></div>
            
        <input type="hidden" id="revision" name="revision" value="{{ $issue->revision }}" />
        <input type="hidden" id="survey_id" name="survey_id" value="{{ $issue->survey_id }}" />
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-body">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 row_authors">
                        <div class="row gutter">
                            <input type="text" class="form-control authors" id="author0" name="author0" value="" placeholder="Author" />
                            <button type="button" class="btn btn-link authors_add"><i class="icon-circle-with-plus"></i>Add</button>
                        </div>
                        <div id="authors_set">
    @foreach ($issue->authors as $i => $curauthor)
        @if (empty($curauthor))
            @continue
        @endif
                            <div id="div_author{{ (1+$i) }}" class="author_line row gutter">
                                <input type="text" class="form-control authors" id="author{{ (1+$i) }}" name="authors[]" value="{{ $curauthor }}" />
                                <button type="button" rel="author{{ (1+$i) }}" class="btn btn-link authors_del"><i class="icon-circle-with-minus"></i>Delete</button>
                            </div>
    @endforeach
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 row_surveyors">
                        <div class="row gutter">
                            <select class="form-control surveyors" id="surveyor0" name="surveyor0" placeholder="Surveyor" title="Surveyor" size="1">
                                <option value="">-- Surveyors --</option>
    @foreach ($surveyors as $cursurveyor)
                                <option value="{{ $cursurveyor->name . ' ' . $cursurveyor->surname }}">{{ $cursurveyor->name . ' ' . $cursurveyor->surname }}</option>
    @endforeach
                            </select>
                            <button type="button" class="btn btn-link surveyors_add"><i class="icon-circle-with-plus"></i>Add</button>
                        </div>
                        <div id="surveyors_set">
    @foreach ($issue->surveyors as $i => $cursurveyor)
        @if (empty($cursurveyor))
            @continue
        @endif
                            <div id="div_surveyor{{ (1+$i) }}" class="surveyor_line row gutter">
                                <input type="text" class="form-control surveyors" id="surveyor{{ (1+$i) }}" name="surveyors[]" value="{{ $cursurveyor }}" />
                                <button type="button" rel="surveyor{{ (1+$i) }}" class="btn btn-link surveyors_del"><i class="icon-circle-with-minus"></i>Delete</button>
                            </div>
    @endforeach
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="form-group">
                        <label class="control-label" for="date_completed"><b>Date Completed</b></label>
                        <div id="date_completed_div">
                            <input type="text" class="form-control" id="date_completed" name="date_completed" title="Date Completed" placeholder="Date Completed" value="{{ $issue->date_completed }}" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label" for="date_checked"><b>Date Checked</b></label>
                        <div id="date_checked_div">
                            <input type="text" class="form-control" id="date_checked" name="date_checked" title="Date Checked" placeholder="Date Checked" value="{{ $issue->date_checked }}" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label" for="quality_check"><b>Quality Check</b></label>
                        <div id="quality_check_div">
                            <input type="text" class="form-control" id="quality_check" name="quality_check" title="Quality Check" placeholder="Quality Check" value="{{ $issue->quality_check }}" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label" for="date_authorised"><b>Date Authorised</b></label>
                        <div id="date_authorised_div">
                            <input type="text" class="form-control" id="date_authorised" name="date_authorised" title="Date Authorised" placeholder="Date Authorised" value="{{ $issue->date_authorised }}" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label" for="date_issued"><b>Date Issued</b></label>
                        <div id="date_issued_div">
                            <input type="text" class="form-control" id="date_issued" name="date_issued" title="Date Issued" placeholder="Date Issued" value="{{ $issue->date_issued }}" />
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 row_issue_tos">
                        <div class="row gutter">
                            <input type="text" class="form-control issuedtos" id="issuedto0" name="issuedto0" value="" placeholder="Issued To" />
                            <button type="button" class="btn btn-link issuedtos_add"><i class="icon-circle-with-plus"></i>Add</button>
                        </div>
                        <div id="issuedtos_set">
    @foreach ($issue->issued_to as $i => $curissued)
        @if (empty($curissued))
            @continue
        @endif
                            <div id="div_issuedto{{ (1+$i) }}" class="issuedto_line row gutter">
                                <input type="text" class="form-control issuedtos" id="issuedto{{ (1+$i) }}" name="issuedto[]" value="{{ $curissued }}" />
                                <button type="button" rel="issuedto{{ (1+$i) }}" class="btn btn-link issuedtos_del"><i class="icon-circle-with-minus"></i>Delete</button>
                            </div>
    @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="clear:left;float:left;margin-left:15px;">
            <button type="button" id="save-revision-end" name="save-report-revision" onclick="submitNewReportIssueForm()" class="btn btn-info btn-lg">Save</button>
        </div>        
    </form>
</div>
@endsection
