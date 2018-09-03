@extends('layouts.fancy')

@section('title')
    {{ config('app.name') . ' - ' . $title }}
@endsection

@section('header-title', $title)

@section('main-content')
    <div class="row gutter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-body">
                    @if ($nfloors > 0)
                    <div id="tabfloors" class="tabbable tabs-left clearfix">
                        <ul class="nav nav-tabs">
                            @foreach ($floors as $n => $floor)
                            <li class="<?php if ($n == 0) { echo "active"; } ?>">
                                <a href="/inspections/{{ $ukasnumber }}/{{ $floor->code }}" class="floorref" data-toggle="tab" aria-expanded="false">{{ $floor->code }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection