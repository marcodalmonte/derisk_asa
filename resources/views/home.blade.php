@extends('layouts.standard')

@section('title', 'Dashboard')

@section('header-title', 'Dashboard')

@section('main-content')
    <fieldset>
        <legend>General Section</legend>
        
        <a href="/users" class="dashboard-link btn btn-warning" title="Users"><i class="icon-account_circle icon-left"></i>Users</a>
        <a href="/clients" class="dashboard-link btn btn-warning" title="Clients"><i class="icon-old-phone icon-left"></i>Clients</a>
    </fieldset>
    
    <fieldset>
        <legend>Derisk UK Section</legend>
        
        <a href="/surveys" class="dashboard-link btn btn-fb" title="Derisk UK Surveys"><i class="icon-notification2 icon-left"></i>Surveys</a>
    </fieldset>

    <fieldset>
        <legend>FRA Section</legend>
        
        <a href="/shops" class="dashboard-link btn btn-success" title="FRA Site Locations"><i class="icon-shopping-cart icon-left"></i>Site Locations</a>
        <a href="/fire-risk-assessments" class="dashboard-link btn btn-success" title="FRA Assessments"><i class="icon-open-book icon-left"></i>Fire Risk Assessments</a>
    </fieldset>
@endsection
