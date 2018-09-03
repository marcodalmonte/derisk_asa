@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - Fire Risk Assessments - Reports' }}
@endsection

@section('header-title', 'Fire Risk Assessments - Reports')

@section('main-content')
    <div class="row gutter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-heading">
                    <h4>Reports</h4>
                    <a href="{{ url('/rasettings') }}" id="settings-link" data-fancybox-type="iframe" class="btn btn-info">Settings</a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="responsiveTableFra" class="table table-striped table-bordered no-margin responsiveTable">
                            <thead>
                                <tr>
                                    <th>Site Locations</th>
                                    <th>Issues</th>
                                </tr>
                            </thead>
                            <tbody>
<?php $last = 0; ?>
                                
@foreach ($reports as $curreport)
   
                                <tr>
                                    <td>{{ $curreport['client_name'] }}<br/>{{ $curreport['rashop_name'] }} <?php if (1 == $curreport['client_id']) { echo "<br/>" . $curreport['shnum']; } ?></td>
                                    <td>
    @foreach ($curreport['revisions'] as $n => $currev)
        <?php $last = $currev['revision']; ?>
                                        <a href="/fire-risk-assessment/{{ $curreport['rashop_id'] }}/{{ $currev['revision'] }}" title="Issue {{ $currev['revision'] }} - {{ date('d/m/Y',$currev['issue_date']) }}" class="a-link-revision">Issue {{ $currev['revision'] }} - {{ date('d/m/Y',$currev['issue_date']) }}</a>
                                        <a href="javascript:;" title="Delete Issue" onclick="deleteRevision({{ $currev['id'] }})" class="actions delete-revision">
                                            <img src="/img/delete.png" class="img_actions" alt="Delete Issue" title="Delete Issue" />
                                        </a>
                                        <br/>
    @endforeach
    
    @if (empty($curreport['revisions']))
                                        
    @endif
                                        <a href="/fire-risk-assessment/{{ $curreport['rashop_id'] }}/{{ 1 + $last }}" title="Create new report for this shop" id="create_new_fra_report">Create New Report</a>
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
    <div id="dialog-confirm" title="Are you sure?">
        <p><span class="ui-icon ui-icon-alert"></span>Are you sure to delete this issue?</p>
    </div>
@endsection
