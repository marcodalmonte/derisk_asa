@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - Clients' }}
@endsection

@section('header-title', 'Clients')

@section('main-content')
    <div class="row gutter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-heading">
                    <h4>Clients</h4>
                    <a href="{{ url('/client/new') }}" data-fancybox-type="iframe" class="btn btn-info new_entity">New Client</a>
               </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="responsiveTableAsbestos" class="table table-striped table-bordered no-margin responsiveTable">
                            <thead>
                                <tr>
                                    <th>Company Name</th>
                                    <th>Derisk Number</th>
                                    <th>Address</th>
                                    <th>Phones</th>
                                    <th>Emails</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
@foreach ($clients as $curclient)
@php
    $name = array($curclient->name, $curclient->companyname);

    $address = array();
    
    if (!empty($curclient->address1)) {
        $address[] = $curclient->address1;
    }
    
    if (!empty($curclient->address2)) {
        $address[] = $curclient->address2;
    }
    
    if (!empty($curclient->city)) {
        $address[] = $curclient->city . ' ' . $curclient->postcode;
    } 
@endphp
   
                                <tr>
                                    <td><?php echo implode('<br/>',$name) ?></td>
                                    <td><?php echo $curclient->derisk_number ?></td>
                                    <td><?php echo implode('<br/>',$address) ?></td>
                                    <td><?php echo str_replace(';','<br/>',$curclient->phones) ?></td>
                                    <td><?php echo str_replace(';','<br/>',$curclient->emails) ?></td>
                                    <td>
                                        <a href="{{ url('/client/' . $curclient->name) }}" title="Update client information" data-fancybox-type="iframe" class="actions new_entity update">
                                            <img src="/img/update.png" class="img_actions" alt="Update client information" title="Update client information" />
                                        </a> 
                                        <a href="javascript:;" title="Delete client" rel="{{ $curclient->name }}" class="actions delete-client">
                                            <img src="/img/delete.png" class="img_actions" alt="Delete client" title="Delete client" />
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
