@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - Surveys' }}
@endsection

@section('header-title', 'Surveys')

@section('main-content')
    <div class="row gutter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-heading">
                    <h4>Surveys</h4>
                    <a href="{{ url('/survey/new') }}" data-fancybox-type="iframe" class="btn btn-info new_entity">New Job Number</a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="responsiveTableAsbestos" class="table table-striped table-bordered no-margin responsiveTable">
                            <thead>
                                <tr>
                                    <th>Job Number</th>
                                    <th>Reinspection Of</th>
                                    <th>Survey Type</th>
                                    <th>Client</th>
                                    <th>Date</th>                                                    
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
@foreach ($surveys as $cursurvey)
                                <tr>
                                    <td title="Derisk Number: {{ $cursurvey->ukasnumber }}">{{ $cursurvey->ukasnumber }}</td>
                                    <td>{{ $cursurvey->reinspectionof }}</td>
                                    <td>{{ $cursurvey->surveytype }}</td>
                                    <td>{{ $cursurvey->companyname }}</td>
                                    <td>{{ $cursurvey->surveydate }}</td>
                                    <td>
                                        <a href="{{ url('/survey/' . $cursurvey->jobnumber) }}" title="Update survey information" data-fancybox-type="iframe" class="actions new_entity update">
                                            <img src="/img/update.png" class="img_actions" alt="Update survey information" title="Update survey information" />
                                        </a>
                                        <a href="{{ url('/inspections/' . $cursurvey->ukasnumber) }}" title="See inspections" data-fancybox-type="iframe" class="actions new_entity update see_inspections">
                                            <img src="/img/camera.png" class="img_actions" alt="See inspections" title="See inspections" />
                                        </a>
                                        <a href="{{ url('/import/' . $cursurvey->jobnumber) }}" title="Import CSV Inspections" data-fancybox-type="iframe" class="actions new_entity update">
                                            <img src="/img/upload.png" class="img_actions" alt="Import CSV Inspections" title="Import CSV Inspections" />
                                        </a>
                                        <a href="javascript:;" title="Print Report" rel="{{ $cursurvey->jobnumber }}" class="actions new_entity print">
                                            <img src="/img/print.png" class="img_actions" alt="Print Report" title="Print Report" />
                                        </a>
                                        <a href="{{ url('/reports/' . $cursurvey->jobnumber) }}" title="Reports" class="actions new_entity reports">
                                            <img src="/img/reports.png" class="img_actions" alt="Reports" title="Reports" />
                                        </a>
                                        <a href="{{ url('/issues/' . $cursurvey->jobnumber) }}" title="Issues" class="actions new_entity">
                                            <img src="/img/assessment.png" class="img_actions" alt="Issues" title="Issues" />
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
    </div>
@endsection
