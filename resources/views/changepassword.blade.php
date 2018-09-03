@extends('layouts.fancy')

@section('title')
    {{ config('app.name') . ' - ' . $title }}
@endsection

@section('header-title')
    {{ $title }}
@endsection

@section('main-content')
    <div class="row gutter">
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-body">
                    <form id="change-password" 
                          method="post" 
                          action="{{ URL::to('/users') }}"
                          data-bv-message="This value is not valid"
                          data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                          data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                          data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
                        
                        {{ csrf_field() }}
                        
                        <input type="hidden" id="email" name="email" value="{{ $user['email'] }}" />
                        
                        <div class="form-group">
                            <label class="sr-only" for="password">Password*</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password*" value="" 
                                   required data-bv-notempty-message="The password is required" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="password2">Confirm Password*</label>
                            <input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm Password*" value="" 
                                   required data-bv-notempty-message="The confirm for the password is required" />
                        </div>
                        <button type="button" id="save-password" name="save-password" onclick="changePassword()" class="btn btn-info">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection