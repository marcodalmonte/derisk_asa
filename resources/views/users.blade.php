@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - Users' }}
@endsection

@section('header-title', 'Users')

@section('main-content')
    <div class="row gutter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-heading">
                    <h4>Users</h4>
                    <a href="{{ url('/user/new') }}" data-fancybox-type="iframe" class="btn btn-info new_entity">New User</a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="responsiveTableAsbestos" class="table table-striped table-bordered no-margin responsiveTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Qualification</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
@foreach ($users as $curuser)
   
                                <tr>
                                    <td>{{ $curuser->name }} {{ $curuser->surname }}</td>
                                    <td>{{ $curuser->email }}</td>
                                    <td>{{ $curuser->qualification }}</td>
                                    <td>
                                        <a href="{{ url('/user/' . $curuser->email) }}" title="Update user information" data-fancybox-type="iframe" class="actions new_entity update">
                                            <img src="/img/update.png" class="img_actions" alt="Update user information" title="Update user information" />
                                        </a>                             
                                        <a href="{{ url('/changepassword/' . $curuser->email) }}" title="Change user password" data-fancybox-type="iframe" class="actions new_entity changepwd">
                                            <img src="/img/change_password.png" class="img_actions" alt="Change user password" title="Change user password" />
                                        </a>
                                        <a href="javascript:;" title="Delete user" rel="{{ $curuser->email }}" class="actions delete-user">
                                            <img src="/img/delete.png" class="img_actions" alt="Delete user" title="Delete user" />
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
