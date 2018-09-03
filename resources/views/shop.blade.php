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
                    <form id="add-shop" 
                          method="post" 
                          action="{{ URL::to('/shops') }}"
                          data-bv-message="This value is not valid"
                          data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                          data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                          data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
                        
                        {{ csrf_field() }}
                        
                        <div class="form-group">
                            <label class="sr-only" for="client_id">Client*</label>
                            <select id="client_id" name="client_id" class="form-control" size="1" required data-bv-notempty-message="The client is required">
                                <option value=""<?php if (empty($shop['client_id'])) { echo ' selected'; } ?>>-- Choose Client --</option>
                                @foreach ($clients as $curclient)
                                    <option value="{{ $curclient->id }}"<?php if ($shop['client_id'] == $curclient->id) { echo ' selected'; } ?>>{{ $curclient->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="name">Name*</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name*" value="{{ $shop['name'] }}" 
                                   required data-bv-notempty-message="The site name is required" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="code">Code</label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="Code" value="{{ $shop['code'] }}" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="address1">Address Row 1</label>
                            <input type="text" class="form-control" id="address1" name="address1" placeholder="Address Row 1" value="{{ $shop['address1'] }}" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="address2">Address Row 2</label>
                            <input type="text" class="form-control" id="address2" name="address2" placeholder="Address Row 2" value="{{ $shop['address2'] }}" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="town">Town</label>
                            <input type="text" class="form-control" id="town" name="town" placeholder="Town" value="{{ $shop['town'] }}" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="postcode">Postcode</label>
                            <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Postcode" value="{{ $shop['postcode'] }}" />
                        </div>
    
                        <button type="button" id="save-shop" name="save-shop" onclick="submitNewShopForm()" class="btn btn-info">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection