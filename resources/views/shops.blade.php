@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - Fire Risk Assessments - Site Locations' }}
@endsection

@section('header-title', 'Fire Risk Assessments - Site Locations')

@section('main-content')
    <div class="row gutter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-heading">
                    <h4>Site Locations</h4>
                    <a href="{{ url('/shop/new') }}" data-fancybox-type="iframe" class="btn btn-info new_entity">New Site</a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="responsiveTableFra" class="table table-striped table-bordered no-margin responsiveTable">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
@foreach ($shops as $curshop)
   
                                <tr>
                                    <td>{{ $curshop['client'] }}</td>
                                    <td>{{ $curshop['name'] }} <?php if (1 == $curshop['client_id']) { echo "<br/>" . $curshop['shnum']; } ?></td>
                                    <td>{{ $curshop['address1'] }} 
                                        @if (!empty($curshop['address2'])) 
                                        <br/>{{ $curshop['address2'] }} 
                                        @endif
                                        <br/>{{ $curshop['town'] }} {{ $curshop['postcode'] }}
                                    </td>
                                    <td>
                                        <a href="{{ url('/shop/' . $curshop['id']) }}" title="Update Location information" data-fancybox-type="iframe" class="actions new_entity update">
                                            <img src="/img/update.png" class="img_actions" alt="Update Location information" title="Update Location information" />
                                        </a> 
                                        @if (1 == $curshop['okdel'])
                                        <a href="javascript:;" title="Delete Site" rel="{{ $curshop['id'] }}" class="actions delete-shop">
                                            <img src="/img/delete.png" class="img_actions" alt="Delete Site" title="Delete Site" />
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
@endsection
