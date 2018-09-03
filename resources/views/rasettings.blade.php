@extends('layouts.fancy')

@section('title')
    {{ config('app.name') . ' - ' . $title }}
@endsection

@section('header-title', $title)

@section('main-content')
    <div class="row gutter">
        <form id="add-settings" 
            method="post" 
            action="{{ URL::to('/saveSettings') }}"
            data-bv-message="This value is not valid"
            data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
            data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
            data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

            {{ csrf_field() }}
                        
            <div class="form-group" style="clear:left;float:left;margin-left:15px;">
                <button type="button" id="save-settings-start" name="save-settings" onclick="submitSettingsForm()" class="btn btn-info btn-lg">Save</button>
            </div>
            
            <div class="clearfix"></div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label" for="sender_name"><b>Sender Name*</b></label>
                            <input type="text" class="form-control" id="sender_name" name="sender_name" title="Sender Name" placeholder="Sender Name" value="{{ $rasettings['sender_name'] }}" 
                                   required data-bv-notempty-message="The Sender Name is required" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="sender_email"><b>Sender Email*</b></label>
                            <input type="email" class="form-control" id="sender_email" name="sender_email" title="Sender Email" placeholder="Sender Email" value="{{ $rasettings['sender_email'] }}" 
                                   required data-bv-notempty-message="The Sender Email is required" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="receiver_name"><b>Receiver Name*</b></label>
                            <input type="text" class="form-control" id="receiver_name" name="receiver_name" title="Receiver Name" placeholder="Receiver Name" value="{{ $rasettings['receiver_name'] }}" 
                                   required data-bv-notempty-message="The Receiver Name is required" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="receiver_email"><b>Receiver Email*</b></label>
                            <input type="email" class="form-control" id="receiver_email" name="receiver_email" title="Receiver Email" placeholder="Receiver Email" value="{{ $rasettings['receiver_email'] }}" 
                                   required data-bv-notempty-message="The Receiver Email is required" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="email_subject"><b>Email Subject*</b></label>
                            <input type="text" class="form-control" id="email_subject" name="email_subject" title="Email Subject" placeholder="Email Subject" value="{{ $rasettings['email_subject'] }}" 
                                   required data-bv-notempty-message="The Email Subject is required" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="email_text"><b>Email Text</b></label>
                            <textarea class="form-control" id="email_text" name="email_text" rows="5" title="Email Text" placeholder="Email Text">{{ $rasettings['email_text'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" style="clear:left;float:left;margin-left:15px;">
                <button type="button" id="save-settings-end" name="save-settings" onclick="submitSettingsForm()" class="btn btn-info btn-lg">Save</button>
            </div>
        </form>
    </div>
@endsection