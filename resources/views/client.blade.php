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
                    <form id="add-client" 
                          method="post" 
                          action="{{ URL::to('/clients') }}"
                          data-bv-message="This value is not valid"
                          data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                          data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                          data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
                        
                        {{ csrf_field() }}
                        
                        <div class="form-group">
                            <label class="sr-only" for="name">Name*</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name*" value="{{ $client['name'] }}" 
                                   required data-bv-notempty-message="The name is required" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="derisk_number">Derisk Number*</label>
                            <input type="text" class="form-control" id="derisk_number" name="derisk_number" placeholder="Derisk Number*" value="{{ $client['derisk_number'] }}" 
                                   required data-bv-notempty-message="The derisk number for the client is required" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="companyname">Company Name*</label>
                            <input type="text" class="form-control" id="companyname" name="companyname" placeholder="Company Name*" value="{{ $client['companyname'] }}" 
                                   required data-bv-notempty-message="The company name is required" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="contact">Contact*</label>
                            <input type="text" class="form-control" id="contact" name="contact" placeholder="Contact*" value="{{ $client['contact'] }}" 
                                   required data-bv-notempty-message="The contact name is required" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="address1">Address 1</label>
                            <input type="text" class="form-control" id="address1" name="address1" placeholder="Address 1" value="{{ $client['address1'] }}" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="address2">Address 2</label>
                            <input type="text" class="form-control" id="address2" name="address2" placeholder="Address 2" value="{{ $client['address2'] }}" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{ $client['city'] }}" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="postcode">Postcode</label>
                            <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Postcode" value="{{ $client['postcode'] }}" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 row_phones">
                            <div class="row gutter">
                                <input type="text" class="form-control phones" id="phone0" name="phone0" value="" placeholder="Phone Number" />
                                <button type="button" class="btn btn-link phones_add"><i class="icon-circle-with-plus"></i>Add</button>
                            </div>
                            <div id="phones_set">
    @foreach ($client['phones'] as $i => $phone)
        @if (empty($phone))
            @continue
        @endif
                                <div id="div_phone{{ (1+$i) }}" class="phone_line row gutter">
                                    <input type="text" class="form-control phones" id="phone{{ (1+$i) }}" name="phones[]" value="{{ $phone }}" />
                                    <button type="button" rel="phone{{ (1+$i) }}" class="btn btn-link phones_del"><i class="icon-circle-with-minus"></i>Delete</button>
                                </div>
    @endforeach
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 row_emails">
                            <div class="row gutter">
                                <input type="text" class="form-control emails" id="email0" name="email0" value="" placeholder="Email Address" />
                                <button type="button" class="btn btn-link emails_add"><i class="icon-circle-with-plus"></i>Add</button>
                            </div>
                            <div id="emails_set">
    @foreach ($client['emails'] as $i => $email)
        @if (empty($email))
            @continue
        @endif
                                <div id="div_email{{ (1+$i) }}" class="email_line row gutter">
                                    <input type="text" class="form-control emails" id="email{{ (1+$i) }}" name="emails[]" value="{{ $email }}" />
                                    <button type="button" rel="email{{ (1+$i) }}" class="btn btn-link emails_del"><i class="icon-circle-with-minus"></i>Delete</button>
                                </div>
    @endforeach
                            </div>
                        </div>
                        <button type="button" id="save-client" name="save-client" onclick="submitNewClientForm()" class="btn btn-info">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection