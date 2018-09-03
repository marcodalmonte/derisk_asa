@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - Removals' }}
@endsection

@section('header-title', 'Removals')

@section('main-content')
    <div class="row gutter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-heading">
                    <h4>Removals</h4>
                    <a href="{{ url('/spec/new') }}" class="btn btn-info new_entity">New Removal</a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="responsiveTableRemovals" class="table table-striped table-bordered no-margin responsiveTable">
                            <thead>
                                <tr>
                                    <th>Project Ref</th>
                                    <th>Client</th>   
                                    <th>Site Address</th>
                                    <th>Issues</th>
                                </tr>
                            </thead>
                            <tbody>
@foreach ($removals as $proj => $curremovals)
                                <tr>
                                    <td title="Project Ref: {{ $curremovals[0]->project_ref }}">{{ $curremovals[0]->project_ref }}</td>
                                    <td>{{ $curremovals[0]->client_name }}</td>
                                    <td><?php echo implode("<br/>",$curremovals[0]->address) ?></td>
                                    <td>
    @foreach ($curremovals as $n => $currev)
                                        <a href="/spec/{{ $currev->id }}" title="Revision {{ $n + 1 }} - {{ $currev->print_date }}" class="a-link-revision">Issue {{ $n + 1 }} - {{ $currev->print_date }}</a>
                                        <a href="javascript:;" title="Delete Issue" onclick="deleteRevision({{ $currev->id }})" class="actions delete-revision">
                                            <img src="/img/delete.png" class="img_actions" alt="Delete Issue" title="Delete Issue" />
                                        </a>
                                        <br/>
    @endforeach
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
