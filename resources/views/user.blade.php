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
                    <form id="add-user" 
                          method="post" 
                          action="{{ URL::to('/users') }}"
                          data-bv-message="This value is not valid"
                          data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                          data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                          data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
                        
                        {{ csrf_field() }}
                        
                        <div class="form-group">
                            <label class="sr-only" for="name">Name*</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name*" value="{{ $user['name'] }}" 
                                   required data-bv-notempty-message="The name is required" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="surname">Surname*</label>
                            <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname*" value="{{ $user['surname'] }}" 
                                   required data-bv-notempty-message="The surname is required" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="email">Email*</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email*" value="{{ $user['email'] }}" 
                                   required data-bv-notempty-message="The email is required, it will be the username" />
                        </div>
    @if ('' == $user['name'])
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
    @else
                        <input type="hidden" id="password" name="password" value="*" />
                        <input type="hidden" id="password2" name="password2" value="*" />
    @endif
    
                        <div class="form-group">
                            <label class="sr-only" for="usertype">User Type*</label>
                            <select id="usertype" name="usertype" class="form-control" size="1">
                                <option value="1"<?php if (empty($user['usertype']) or ('1' == $user['usertype'])) { echo ' selected'; } ?>>Administrator</option>
                                <option value="2"<?php if ('2' == $user['usertype']) { echo ' selected'; } ?>>Fire Risk Manager</option>
                                <option value="3"<?php if ('3' == $user['usertype']) { echo ' selected'; } ?>>Surveyor</option>
                            </select>
                        </div>
    
                        <div class="form-group">
                            <label class="sr-only" for="qualification">Qualification</label>
                            <input type="text" class="form-control" id="qualification" name="qualification" placeholder="Qualification" value="{{ $user['qualification'] }}" />
                        </div>
    
                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" id="fassessor" name="assessor" value="1" <?php echo ((1 == $user['assessor']) ? 'checked ' : '') ?>/> FRA Assessor</label>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" id="reviewer" name="reviewer" value="1" <?php echo ((1 == $user['reviewer']) ? 'checked ' : '') ?>/> FRA Reviewer</label>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" id="udisabled" name="udisabled" value="1" <?php echo ((1 == $user['disabled']) ? 'checked ' : '') ?>/> Disabled</label>
                            </div>
                        </div>
    
                        <button type="button" id="save-user" name="save-user" onclick="submitNewUserForm()" class="btn btn-info">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection