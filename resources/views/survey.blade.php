@extends('layouts.fancy')

@section('title')
    {{ config('app.name') . ' - ' . $title }}
@endsection

@section('header-title', $title)

@section('main-content')
    <div class="row gutter">
        <form id="add-survey" 
            method="post" 
            action="{{ URL::to('/saveJob') }}"
            data-bv-message="This value is not valid"
            data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
            data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
            data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

            {{ csrf_field() }}
                        
            <input type="hidden" id="job_number" name="job_number" value="{{ $job_number }}" />
            
            <div class="form-group" style="clear:left;float:left;margin-left:15px;">
                <button type="button" id="save-survey-start" name="save-survey" onclick="submitNewSurveyForm()" class="btn btn-info btn-lg">Save</button>
                <button type="button" id="export-info-start" name="export-info" onclick="exportSurveyInfo()" class="btn btn-info btn-lg">Export CSV</button>
            </div>
            
            <div class="clearfix"></div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label" for="ukasnumber"><b>UKAS Number*</b></label>
                            <input type="text" class="form-control" id="ukas_number" name="ukas_number" title="UKAS Number" placeholder="UKAS Number" value="{{ $survey['ukasnumber'] }}" 
                                   required data-bv-notempty-message="The UKAS number is required" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="client_id"><b>Client*</b></label>
                            <select id="client_id" name="client_id" class="form-control" title="Client"
                                    required data-bv-notempty-message="The client is required">
                                <option value="">-- Client --</option>
    @php
        foreach ($clients as $curclient) {
            $selected = '';
            
            if ($curclient->id == $survey['client_id']) {
                $selected = ' selected';
            }
    @endphp
                                <option value="{{ $curclient->id }}"{{ $selected }}>{{ $curclient->companyname }}</option>
    @php
        }
    @endphp
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="siteaddress"><b>Site Address</b></label>
                            <textarea class="form-control" id="siteaddress" name="siteaddress" rows="5" title="Site Address" placeholder="Site Address">{{ $survey['siteaddress'] }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="sitedescription"><b>Site Description</b></label>
                            <textarea class="form-control" id="sitedescription" name="sitedescription" rows="12" title="Site Description" placeholder="Site Description">{{ $survey['sitedescription'] }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="scope"><b>Scope</b></label>
                            <textarea class="form-control" id="scope" name="scope" rows="12" title="Scope" placeholder="Scope">{{ $survey['scope'] }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="agreed_excluded_areas"><b>Agreed excluded areas</b></label>
                            <textarea class="form-control" id="agreed_excluded_areas" name="agreed_excluded_areas" rows="5" title="Agreed excluded areas" placeholder="Agreed excluded areas">{{ $survey['agreed_excluded_areas'] }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="deviations_from_standard_methods"><b>Deviations from standard methods</b></label>
                            <textarea class="form-control" id="deviations_from_standard_methods" name="deviations_from_standard_methods" rows="10" title="Deviations from standard methods" placeholder="Deviations from standard methods">{{ $survey['deviations_from_standard_methods'] }}</textarea>
                        </div>
                        <div class="form-group" style="height:57px;">
                            <label class="control-label" for="surveydate" style="float:left;"><b>Survey Date*</b></label>
                            <input type="text" id="surveydate" name="surveydate" class="form-control" value="{{ $survey['surveydate'] }}" title="Survey Date" placeholder="Survey Date"
                                   required data-bv-notempty-message="The survey date is required" />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control othersdates" id="other0" name="other0" value="" placeholder="Other Date" />
                            <button type="button" class="btn btn-link otherdate_add"><i class="icon-circle-with-plus"></i>Add</button>
                        </div>
                        <div id="othersdates_set">
    @foreach ($survey['othersdates'] as $i => $otherdate)
        @if (empty($otherdate))
            @continue
        @endif
                            <div id="div_otherdate{{ (1+$i) }}" class="otherdate_line row gutter">
                                <input type="text" class="form-control othersdates" id="otherdate{{ (1+$i) }}" name="othersdates[]" value="{{ $otherdate }}" />
                                <button type="button" rel="otherdate{{ (1+$i) }}" class="btn btn-link othersdates_del"><i class="icon-circle-with-minus"></i>Delete</button>
                            </div>
    @endforeach
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="surveyors"><b>Surveyors</b></label>
                            <select class="form-control surveyors" id="surveyors" name="surveyors[]" placeholder="Surveyors" title="Surveyors" multiple="multiple">
                                <option value="">-- Surveyors --</option>
    @foreach ($surveyors as $cursurveyor)
        @php
            $selected = '';
            
            foreach ($surveysurveyors as $i => $surveyor) {                
                if ($cursurveyor->id == $surveyor->surveyor_id) {
                    $selected = ' selected';
                    break;
                }
            }
        @endphp
        
                                <option value="{{ $cursurveyor->id }}"{{ $selected }}>{{ $cursurveyor->name . ' ' . $cursurveyor->surname }}</option>
    @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="surveytype_id"><b>Survey type*</b></label>
                            <select id="surveytype_id" name="surveytype_id" class="form-control" title="Survey type"
                                    required data-bv-notempty-message="The survey type is required">
                                <option value="">-- Survey Type --</option>
    @php
        foreach ($surveytypes as $cursurveytype) {
            $selected = '';
            
            if ($cursurveytype->id == $survey['surveytype_id']) {
                $selected = ' selected';
            }
    @endphp
        
                                <option value="{{ $cursurveytype->id }}"{{ $selected }}>{{ $cursurveytype->surveytype }}</option>
    @php
        }
    @endphp
                            </select>
                        </div>
                        
    @if (count($surveys) > 0)
                        <div class="form-group">
                            <label class="control-label" for="reinspectionof"><b>Reinspection of</b></label>
                            <select id="reinspectionof" name="reinspectionof" class="form-control" title="Reinspection of">
                                <option value="">-- Reinspection Of --</option>
        @php
            foreach ($surveys as $cursurvey) {
                $selected = '';
            
                if ($cursurvey->id == $survey['reinspectionof']) {
                    $selected = ' selected';
                }
        @endphp
                                <option value="{{ $cursurvey->id }}"{{ $selected }}>{{ $cursurvey->ukasnumber }}</option>
        @php
            }
        @endphp
                            </select>
                        </div>
    @else
                        <input type="hidden" id="reinspectionof" name="reinspectionof" value="" />
    @endif                    
                        
                        <div class="form-group">
                            <label class="control-label" for="lab_id"><b>Designated laboratory</b></label>
                            <select id="lab_id" name="lab_id" class="form-control" title="Designated laboratory">
                                <option value="">-- Designated laboratory --</option>
        @php
            foreach ($labs as $curlab) {
                $selected = '';
            
                if ($curlab->id == $survey['lab_id']) {
                    $selected = ' selected';
                }
        @endphp
                                <option value="{{ $curlab->id }}"{{ $selected }}>{{ $curlab->company }}</option>
        @php
            }
        @endphp
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="issued_to"><b>Issued to*</b></label>
                            <input type="text" class="form-control" id="issued_to" name="issued_to" title="Issued to" placeholder="Issued to*" value="{{ $survey['issued_to'] }}" 
                                   required data-bv-notempty-message="The destination of the issued report is required" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="urgency"><b>Urgency</b></label>
                            <select id="urgency" name="urgency" class="form-control" title="Urgency">
                                <option value="Standard"<?php if (empty($survey['urgency']) or ("Standard" == $survey['urgency'])) { echo "selected"; } ?>>Standard</option>
                                <option value="Urgent"<?php if (!empty($survey['urgency']) and ("Urgent" == $survey['urgency'])) { echo "selected"; } ?>>Urgent</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" style="clear:left;float:left;margin-left:15px;">
                <button type="button" id="save-survey-end" name="save-survey" onclick="submitNewSurveyForm()" class="btn btn-info btn-lg">Save</button>
                <button type="button" id="export-info-end" name="export-info" onclick="exportSurveyInfo()" class="btn btn-info btn-lg">Export CSV</button>
            </div>
        </form>
    </div>
@endsection