@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - ' . $title }}
@endsection

@section('header-title', $title)

@section('main-content')
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#idclient').on('change',function() {
            var idclient = jQuery(this).val();
            
            if (idclient.length == 0) {
                return false;
            }
        
            location.href = '/recommendations-and-comments/' + idclient;
        });
    });
</script>

<div id="filter-row" class="row gutter">    
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div id="client-filter-div" class="form-group">
            <label class="control-label">Template for</label>
            <select id="idclient" name="idclient" class="form-control" size="1">
                <option value="">-- Select client --</option>
                @foreach ($clients as $client)
                <option value="{{ $client->id }}" @if ($client->id == $client_id) selected @endif>{{ $client->companyname }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
    
<div class="clearfix"></div>

<div class="row gutter">    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">              
        <ul class="nav nav-tabs">
            @foreach ($sections as $k => $section)
            <li class="<?php if ($k == 0) { echo ' active'; }?>">
                <a href="#tab-{{ $section->id }}" data-toggle="tab">
                    <img src="/img/fra-sections/<?php echo (($section->id < 10) ? ('0' . $section->id) : $section->id) ?>.png" alt="{{ ucfirst(strtolower($section->name)) }}" title="{{ ucfirst(strtolower($section->name)) }}" class="tab-icon" />
                </a>
            </li>
            @endforeach
        </ul>
        
        <div class="tab-content">
            @foreach ($sections as $k => $section) 
            <div id="tab-{{ $section->id }}" class="tab-pane<?php if ($k == 0) { echo ' active'; }?>">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-light">
                        <div class="panel-heading">
                            <h4>{{ $section->id }}. {{ $section->name }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed no-margin">
                                    <thead>
                                        <tr>
                                            <td>Question</td>
                                            <td>Comments</td>
                                            <td>Recommendations</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($questions[$section->id] as $n => $question)
                                        <tr id="tr_{{ $question->id }}" name="tr_{{ $section->id }}_{{ 1 + $n }}" class="answers">
                                            <td>
                                                {{ $section->id }}.{{ 1 + $n }} {{ $question->question }}
                                            </td>
                                            <td>
                                                <table cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td>
                                                            <textarea id="comment_new_{{ $question->id }}" name="comment_new_{{ $question->id }}" class="form-control"></textarea>
                                                        </td>
                                                        <td class="icon-td">
                                                            <a href="javascript:;" title="Save" class="save_comment" rel="comment_new_{{ $question->id }}">
                                                                <img src="/img/update.png" class="img_actions" alt="Save" title="Save" />
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @foreach ($comments[$question->id] as $comment)
                                                    <tr>
                                                        <td>
                                                            <textarea id="comment_{{ $comment->id }}" name="comment_{{ $comment->id }}" class="form-control">{{ $comment->text }}</textarea>
                                                        </td>
                                                        <td class="icon-td">
                                                            <a href="javascript:;" title="Update" class="update_comment" rel="comment_{{ $comment->id }}">
                                                                <img src="/img/update.png" class="img_actions" alt="Update" title="Update" />
                                                            </a>
                                                            <a href="javascript:;" title="Delete" class="delete_comment" rel="comment_{{ $comment->id }}">
                                                                <img src="/img/delete.png" class="img_actions" alt="Delete" title="Delete" />
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                            <td>
                                                <table cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td>
                                                            <textarea id="recomm_new_{{ $question->id }}" name="recomm_new_{{ $question->id }}" class="form-control"></textarea>
                                                        </td>
                                                        <td class="icon-td">
                                                            <a href="javascript:;" title="Save" class="save_recomm" rel="recomm_new_{{ $question->id }}">
                                                                <img src="/img/update.png" class="img_actions" alt="Save" title="Save" />
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @foreach ($recommendations[$question->id] as $recomm)
                                                    <tr>
                                                        <td>
                                                            <textarea id="recomm_{{ $recomm->id }}" name="recomm_{{ $recomm->id }}" class="form-control">{{ $recomm->text }}</textarea>
                                                        </td>
                                                        <td class="icon-td">
                                                            <a href="javascript:;" title="Update" class="update_recomm" rel="recomm_{{ $recomm->id }}">
                                                                <img src="/img/update.png" class="img_actions" alt="Update" title="Update" />
                                                            </a>
                                                            <a href="javascript:;" title="Delete" class="delete_recomm" rel="recomm_{{ $recomm->id }}">
                                                                <img src="/img/delete.png" class="img_actions" alt="Delete" title="Delete" />
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
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
            @endforeach
        </div>
    </div>
</div>
@endsection
