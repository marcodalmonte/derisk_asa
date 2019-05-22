@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - Surveys - Settings' }}
@endsection

@section('header-title', 'Surveys - Settings')

@section('main-content')
<script type="text/javascript" src="/js/settings.js"></script>

<div class="row gutter">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab-surveyors" data-toggle="tab">
                <img src="/img/settings/surveyor.png" alt="Surveyors" title="Surveyors" class="tab-icon" />
            </a>
        </li>
        <li>
            <a href="#tab-surveytypes" data-toggle="tab">
                <img src="/img/settings/surveytype.png" alt="Survey Types" title="Survey Types" class="tab-icon" />
            </a>
        </li>
        <li>
            <a href="#tab-labs" data-toggle="tab">
                <img src="/img/settings/lab.png" alt="Labs" title="Labs" class="tab-icon" />
            </a>
        </li>
        <li>
            <a href="#tab-rooms" data-toggle="tab">
                <img src="/img/settings/room.png" alt="Rooms" title="Rooms" class="tab-icon" />
            </a>
        </li>
        <li>
            <a href="#tab-floors" data-toggle="tab">
                <img src="/img/settings/floor.png" alt="Floors" title="Floors" class="tab-icon" />
            </a>
        </li>
        <li>
            <a href="#tab-products" data-toggle="tab">
                <img src="/img/settings/product.png" alt="Products" title="Products" class="tab-icon" />
            </a>
        </li>
        <li>
            <a href="#tab-extents" data-toggle="tab">
                <img src="/img/settings/extent.png" alt="Extents of Damage" title="Extents of Damage" class="tab-icon" />
            </a>
        </li>
        <li>
            <a href="#tab-treatments" data-toggle="tab">
                <img src="/img/settings/treatment.png" alt="Surface Treatments" title="Surface Treatments" class="tab-icon" />
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="tab-surveyors" class="tab-pane active">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        <h4>Surveyors</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-surveyors" class="table table-bordered table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="surveyor-name" name="surveyor-name" class="form-control" value="" /></td>
                                        <td><input type="text" id="surveyor-surname" name="surveyor-surname" class="form-control" value="" /></td>
                                        <td><input type="email" id="surveyor-email" name="surveyor-email" class="form-control" value="" /></td>
                                        <td><button type="button" id="save-surveyor" name="save-surveyor" class="btn btn-info btn-lg">Save</button></td>
                                    </tr>
                                    @foreach ($surveyors as $surveyor)
                                    <tr>
                                        <td>{{ $surveyor->name }}</td>
                                        <td>{{ $surveyor->surname }}</td>
                                        <td>{{ $surveyor->email }}</td>
                                        <td>
                                            @if ($surveyor->active == 1)
                                            <a href="javascript:;" rel="surveyor-{{ $surveyor->id }}" title="Delete Surveyor" class="actions delete-surveyor">
                                                <img src="/img/delete.png" class="img_actions" alt="Delete Surveyor" title="Delete Surveyor" />
                                            </a>
                                            @else
                                            <a href="javascript:;" rel="surveyor-{{ $surveyor->id }}" title="Enable Surveyor" class="actions enable-surveyor">
                                                <img src="/img/change_password.png" class="img_actions" alt="Enable Surveyor" title="Enable Surveyor" />
                                            </a>
                                            @endif
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
        
        <div id="tab-surveytypes" class="tab-pane">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        <h4>Survey Types</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-surveytypes" class="table table-bordered table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th>Survey Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="surveytype" name="surveytype" class="form-control" value="" /></td>
                                        <td><button type="button" id="save-surveytype" name="save-surveytype" class="btn btn-info btn-lg">Save</button></td>
                                    </tr>
                                    @foreach ($surveytypes as $surveytype)
                                    <tr>
                                        <td>{{ $surveytype->surveytype }}</td>
                                        <td>
                                            @if ($surveytype->active == 1)
                                            <a href="javascript:;" rel="surveytype-{{ $surveytype->id }}" title="Delete Survey Type" class="actions delete-surveytype">
                                                <img src="/img/delete.png" class="img_actions" alt="Delete Survey Type" title="Delete Survey Type" />
                                            </a>
                                            @else
                                            <a href="javascript:;" rel="surveytype-{{ $surveytype->id }}" title="Enable Survey Type" class="actions enable-surveytype">
                                                <img src="/img/change_password.png" class="img_actions" alt="Enable Survey Type" title="Enable Survey Type" />
                                            </a>
                                            @endif
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
        
        <div id="tab-labs" class="tab-pane">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        <h4>Labs</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-labs" class="table table-bordered table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Building</th>
                                        <th>Address</th>
                                        <th>Town</th>
                                        <th>Postcode</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="lab-company" name="lab-company" class="form-control" value="" /></td>
                                        <td><input type="text" id="lab-building" name="lab-building" class="form-control" value="" /></td>
                                        <td><input type="text" id="lab-address" name="lab-address" class="form-control" value="" /></td>
                                        <td><input type="text" id="lab-town" name="lab-town" class="form-control" value="" /></td>
                                        <td><input type="text" id="lab-postcode" name="lab-postcode" class="form-control" value="" /></td>
                                        <td><button type="button" id="save-lab" name="save-lab" class="btn btn-info btn-lg">Save</button></td>
                                    </tr>
                                    @foreach ($labs as $lab)
                                    <tr>
                                        <td>{{ $lab->company }}</td>
                                        <td>{{ $lab->building }}</td>
                                        <td>{{ $lab->address }}</td>
                                        <td>{{ $lab->town }}</td>
                                        <td>{{ $lab->postcode }}</td>
                                        <td>
                                            @if ($lab->active == 1)
                                            <a href="javascript:;" rel="lab-{{ $lab->id }}" title="Delete Lab" class="actions delete-lab">
                                                <img src="/img/delete.png" class="img_actions" alt="Delete Lab" title="Delete Lab" />
                                            </a>
                                            @else
                                            <a href="javascript:;" rel="lab-{{ $lab->id }}" title="Enable Lab" class="actions enable-lab">
                                                <img src="/img/change_password.png" class="img_actions" alt="Enable Lab" title="Enable Lab" />
                                            </a>
                                            @endif
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
        
        <div id="tab-rooms" class="tab-pane">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        <h4>Rooms</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-rooms" class="table table-bordered table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th>Room</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="room-name" name="room-name" class="form-control" value="" /></td>
                                        <td><button type="button" id="save-room" name="save-room" class="btn btn-info btn-lg">Save</button></td>
                                    </tr>
                                    @foreach ($rooms as $room)
                                    <tr>
                                        <td>{{ $room->name }}</td>
                                        <td>
                                            @if ($room->active == 1)
                                            <a href="javascript:;" rel="room-{{ $room->id }}" title="Delete Room" class="actions delete-room">
                                                <img src="/img/delete.png" class="img_actions" alt="Delete Room" title="Delete Room" />
                                            </a>
                                            @else
                                            <a href="javascript:;" rel="room-{{ $room->id }}" title="Enable Room" class="actions enable-room">
                                                <img src="/img/change_password.png" class="img_actions" alt="Enable Room" title="Enable Room" />
                                            </a>
                                            @endif
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
        
        <div id="tab-floors" class="tab-pane">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        <h4>Floors</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-floors" class="table table-bordered table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Menu Order</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="floor-code" name="floor-code" class="form-control" value="" /></td>
                                        <td><input type="text" id="floor-name" name="floor-name" class="form-control" value="" /></td>
                                        <td><input type="number" id="floor-menu-order" name="floor-menu-order" min="1" step="1" class="form-control" value="" /></td>
                                        <td><button type="button" id="save-floor" name="save-floor" class="btn btn-info btn-lg">Save</button></td>
                                    </tr>
                                    @foreach ($floors as $floor)
                                    <tr>
                                        <td>{{ $floor->code }}</td>
                                        <td>{{ $floor->name }}</td>
                                        <td>{{ $floor->menu }}</td>
                                        <td>
                                            @if ($floor->active == 1)
                                            <a href="javascript:;" rel="floor-{{ $floor->id }}" title="Delete Floor" class="actions delete-floor">
                                                <img src="/img/delete.png" class="img_actions" alt="Delete Floor" title="Delete Floor" />
                                            </a>
                                            @else
                                            <a href="javascript:;" rel="floor-{{ $floor->id }}" title="Enable Floor" class="actions enable-floor">
                                                <img src="/img/change_password.png" class="img_actions" alt="Enable Floor" title="Enable Floor" />
                                            </a>
                                            @endif
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
        
        <div id="tab-products" class="tab-pane">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        <h4>Products</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-products" class="table table-bordered table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Score</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="product-name" name="product-name" class="form-control" value="" /></td>
                                        <td><input type="number" id="product-score" name="product-score" min="1" max="3" step="1" class="form-control" value="" /></td>
                                        <td><button type="button" id="save-product" name="save-product" class="btn btn-info btn-lg">Save</button></td>
                                    </tr>
                                    @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->score }}</td>
                                        <td>
                                            @if ($product->active == 1)
                                            <a href="javascript:;" rel="product-{{ $product->id }}" title="Delete Product" class="actions delete-product">
                                                <img src="/img/delete.png" class="img_actions" alt="Delete Product" title="Delete Product" />
                                            </a>
                                            @else
                                            <a href="javascript:;" rel="product-{{ $product->id }}" title="Enable Product" class="actions enable-product">
                                                <img src="/img/change_password.png" class="img_actions" alt="Enable Product" title="Enable Product" />
                                            </a>
                                            @endif
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
        
        <div id="tab-extents" class="tab-pane">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        <h4>Extents of Damage</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-extents" class="table table-bordered table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Score</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="extent-code" name="extent-code" class="form-control" value="" /></td>
                                        <td><input type="text" id="extent-name" name="extent-name" class="form-control" value="" /></td>
                                        <td><input type="number" id="extent-score" name="extent-score" min="0" max="3" step="1" class="form-control" value="" /></td>
                                        <td><button type="button" id="save-extent" name="save-extent" class="btn btn-info btn-lg">Save</button></td>
                                    </tr>
                                    @foreach ($extents as $extent)
                                    <tr>
                                        <td>{{ $extent->code }}</td>
                                        <td>{{ $extent->name }}</td>
                                        <td>{{ $extent->score }}</td>
                                        <td>
                                            @if ($extent->active == 1)
                                            <a href="javascript:;" rel="extent-{{ $extent->id }}" title="Delete Extent" class="actions delete-extent">
                                                <img src="/img/delete.png" class="img_actions" alt="Delete Extent" title="Delete Extent" />
                                            </a>
                                            @else
                                            <a href="javascript:;" rel="extent-{{ $extent->id }}" title="Enable Extent" class="actions enable-extent">
                                                <img src="/img/change_password.png" class="img_actions" alt="Enable Extent" title="Enable Extent" />
                                            </a>
                                            @endif
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
        
        <div id="tab-treatments" class="tab-pane">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        <h4>Surface Treatments</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="table-treatments" class="table table-bordered table-condensed no-margin">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Score</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="treatment-code" name="treatment-code" class="form-control" value="" /></td>
                                        <td><input type="text" id="treatment-description" name="treatment-description" class="form-control" value="" /></td>
                                        <td><input type="number" id="treatment-score" name="treatment-score" min="0" max="3" step="1" class="form-control" value="" /></td>
                                        <td><button type="button" id="save-treatment" name="save-treatment" class="btn btn-info btn-lg">Save</button></td>
                                    </tr>
                                    @foreach ($surface_treatments as $treatment)
                                    <tr>
                                        <td>{{ $treatment->code }}</td>
                                        <td>{{ $treatment->description }}</td>
                                        <td>{{ $treatment->score }}</td>
                                        <td>
                                            @if ($treatment->active == 1)
                                            <a href="javascript:;" rel="treatment-{{ $treatment->id }}" title="Delete Surface Treatment" class="actions delete-treatment">
                                                <img src="/img/delete.png" class="img_actions" alt="Delete Surface Treatment" title="Delete Surface Treatment" />
                                            </a>
                                            @else
                                            <a href="javascript:;" rel="treatment-{{ $treatment->id }}" title="Enable Surface Treatment" class="actions enable-treatment">
                                                <img src="/img/change_password.png" class="img_actions" alt="Enable Surface Treatment" title="Enable Surface Treatment" />
                                            </a>
                                            @endif
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
    </div>
</div>
@endsection
