@extends('layouts.standard')

@section('title')
    {{ config('app.name') . ' - ' . $title }}
@endsection

@section('header-title', $title)

@section('main-content')

<script type="text/javascript">
    var picture_url = '';
    
    jQuery(document).ready(function() {
<?php

if (!empty($fra['signature'])) {
    
?>
        picture_url = escape("/fra<?php echo $fra['signature'] ?>");            

        jQuery('#signature').val(picture_url);
        adaptImage(picture_url,'#signright',198,198);
        
<?php
    
}

if (!empty($fra['review_signature'])) {
    
?>
        picture_url = escape("/fra<?php echo $fra['review_signature'] ?>");            

        jQuery('#review_signature').val(picture_url);
        adaptImage(picture_url,'#review-signright',198,198);
        
<?php
    
}


if (!empty($fra['main_picture'])) {
    
?>
        picture_url = escape("/fra<?php echo $fra['main_picture'] ?>");            

        jQuery('#main_picture').val(picture_url);
        adaptImage(picture_url,'#mpright',198,198);
        
<?php
    
}

foreach ($rasections as $rasection) {
    foreach ($raquestions[$rasection->id] as $n => $raquestion) {
        if (empty($raanswers[$raquestion->id]->picture)) {
            continue;
        }
        
?>
        picture_url = escape("/fra<?php echo $raanswers[$raquestion->id]->picture ?>");            

        jQuery('#picture-<?php echo $raquestion->id ?>').val(picture_url);
        adaptImage(picture_url,'#pictureright_<?php echo $raquestion->id ?>',198,198);
   
<?php
        
    }
}

$keys = array_keys($raanswers);

foreach ($rasections as $rasection) {
    foreach ($raquestions[$rasection->id] as $n => $raquestion) {
        $background = '#FFFFFF';
        
        if (!in_array($raquestion->id, $keys) or empty($raanswers[$raquestion->id])) {
            continue;
        }
        
        switch ($raanswers[$raquestion->id]->priority_code) {
            case '1':
                $background = '#FF0000';
                break;
            case '2':
                $background = '#FFFF00';
                break;
            case '3':
                $background = '#92D050';
                break;
            case '4':
                $background = '#00B0F0';
                break;
            case '5':
                $background = '#CCC0D9';
                break;
            default:
                $background = '#FFFFFF';
                break;
        }
        
?>
        jQuery('#priority-<?php echo $raquestion->id ?>').css('background','<?php echo $background ?>');
   
<?php
        
    }
}

?>
        
        jQuery('#new_revision').on('change', function() {
            if (jQuery(this).is(':checked')) {
                jQuery('#completed').attr('checked',false);
            }
        });
        
        jQuery('#country_context').on('change', function() {
            if ('uk' == jQuery(this).val()) {
                jQuery('#resp_label').html('<b>Responsible Person*</b>');
                
                jQuery('#responsible_person').attr('title','Responsible Person');
                jQuery('#responsible_person').attr('placeholder','Responsible Person');
                jQuery('#responsible_person').attr('data-bv-notempty-message','The Responsible Person is required');
            } else {
                jQuery('#resp_label').html('<b>Duty Holder*</b>');
                
                jQuery('#responsible_person').attr('title','Duty Holdern');
                jQuery('#responsible_person').attr('placeholder','Duty Holder');
                jQuery('#responsible_person').attr('data-bv-notempty-message','The Duty Holder is required');
            }
        });
    });
</script>

<div class="row gutter">
    <form id="add-fra"
        enctype="multipart/form-data" 
        method="post" 
        action="{{ URL::to('/saveFra') }}"
        data-bv-message="This value is not valid"
        data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
        data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
        data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

        {{ csrf_field() }}
        
        <div class="form-group" style="clear:left;float:left;margin-left:15px;">
            <button type="button" id="save-fra-start" name="save-fra" onclick="submitNewFraForm()" class="btn btn-info btn-lg">Save</button>
            <button type="button" id="print-fra-start" name="print-fra" onclick="printFraPdf()" class="btn btn-info btn-lg fra-print">Print</button>
        </div>

        <div class="clearfix"></div>
            
        <input type="hidden" id="shop_id" name="shop_id" value="{{ $curshop->id }}" />
        <input type="hidden" id="revision" name="revision" value="{{ $revision }}" />
        <input type="hidden" id="user_email" name="user_email" value="{{ Auth::user()->email }}" />
        <input type="hidden" id="signature" name="signature" value="<?php echo (!empty($fra['signature']) ? ('/fra' . $fra['signature']) : '') ?>" />
        <input type="hidden" id="review_signature" name="review_signature" value="<?php echo (!empty($fra['review_signature']) ? ('/fra' . $fra['review_signature']) : '') ?>" />
        <input type="hidden" id="main_picture" name="main_picture" value="<?php echo (!empty($fra['main_picture']) ? ('/fra' . $fra['main_picture']) : '') ?>" />
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-body">
                    <div class="form-group">
                        <div class="checkbox">
                            <label><input type="checkbox" id="new_revision" name="new_revision" value="1" <?php echo ((1 == $isnew) ? 'checked ' : '') ?>/> New Issue?</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="revision_comments"><b>Revision Comments</b></label>
                        <textarea class="form-control" id="revision_comments" name="revision_comments" title="Comments" placeholder="Comments">{{ $fra['comments'] }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label" for="country_context"><b>Where?*</b></label>
                        <select id="country_context" name="country_context" class="form-control" size="1" required data-bv-notempty-message="The Risk Level Rate is required">
                            <option value="uk"<?php echo (('uk' == $fra['countrylaw']) ? ' selected' : '') ?>>UK</option>
                            <option value="scotland"<?php echo (('scotland' == $fra['countrylaw']) ? ' selected' : '') ?>>Scotland</option>
                        </select>
                    </div>
                    <div id="signparent" class="imgdiv">
                        <div id="signleft" class="imgleft">
                            <input type="file" id="signature-file" name="signature-file" value="" />
                            <button type="button" id="import-signature" name="import-signature" onclick="importSignature()" class="btn btn-info">Load Signature</button>
                            <button type="button" id="clean-signature" name="clean-signature" onclick="cleanSignature()" class="btn btn-info">Clean Signature</button>
                        </div>
                        <div id="signright" class="imgright">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="risk_level_rate"><b>Risk Level Rate*</b></label>
                        <select id="risk_level_rate" name="risk_level_rate" class="form-control" size="1" required data-bv-notempty-message="The Risk Level Rate is required">
                            <option value="">-- Choose --</option>
                            <option value="trivial"<?php echo (('trivial' == $fra['risk_level_rate']) ? ' selected' : '') ?>>Trivial</option>
                            <option value="tolerable"<?php echo (('tolerable' == $fra['risk_level_rate']) ? ' selected' : '') ?>>Tolerable</option>
                            <option value="moderate"<?php echo (('moderate' == $fra['risk_level_rate']) ? ' selected' : '') ?>>Moderate</option>
                            <option value="substantial"<?php echo (('substantial' == $fra['risk_level_rate']) ? ' selected' : '') ?>>Substantial</option>
                            <option value="intolerable"<?php echo (('intolerable' == $fra['risk_level_rate']) ? ' selected' : '') ?>>Intolerable</option>
                        </select>
                    </div>
                    <div id="mpparent" class="imgdiv">
                        <div id="mpleft" class="imgleft">
                            <input type="file" id="mainpicture-file" name="mainpicture-file" value="" />
                            <button type="button" id="import-mainpicture" name="import-mainpicture" onclick="importMainPicture()" class="btn btn-info">Load Main Picture</button>
                            <button type="button" id="clean-mainpicture" name="clean-mainpicture" onclick="cleanMainPicture()" class="btn btn-info">Clean Main Picture</button>
                        </div>
                        <div id="mpright" class="imgright">

                        </div>
                    </div>
                    <div class="form-group">
                        <label id="resp_label" class="control-label" for="responsible_person"><b><?php echo (('uk' == $fra['countrylaw']) ? 'Responsible Person' : 'Duty Holder') ?>*</b></label>
                        @if (1 == $curshop->client_id)
                        <select class="form-control" id="responsible_person" name="responsible_person" title="<?php echo (('uk' == $fra['countrylaw']) ? 'Responsible Person' : 'Duty Holder') ?>" required data-bv-notempty-message="The <?php echo (('uk' == $fra['countrylaw']) ? 'Responsible Person' : 'Duty Holder') ?> is required">
                            <option value="">-- Choose --</option>
                            <option value="Pret A Manger (Europe) Limited 2013"<?php echo (('Pret A Manger (Europe) Limited 2013' == $fra['responsible_person']) ? ' selected' : '') ?>>Pret A Manger (Europe) Limited 2013</option>
                        </select>
                        @else
                        <input type="text" class="form-control" id="responsible_person" name="responsible_person" title="<?php echo (('uk' == $fra['countrylaw']) ? 'Responsible Person' : 'Duty Holder') ?>" placeholder="<?php echo (('uk' == $fra['countrylaw']) ? 'Responsible Person' : 'Duty Holder') ?>" value="{{ $fra['responsible_person'] }}" 
                                required data-bv-notempty-message="The <?php echo (('uk' == $fra['countrylaw']) ? 'Responsible Person' : 'Duty Holder') ?> is required" />
                        @endif                            
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="assessor"><b>Assessor*</b></label>
                        <select id="assessor" name="assessor" class="form-control" size="1" required data-bv-notempty-message="The Assessor is required">
                            <option value="">-- Choose --</option>
<?php

    foreach ($fassessors as $curassessor) {
        $fullname = $curassessor->name .  ' '  . $curassessor->surname;
        if (!empty($curassessor->qualification)) {
            $fullname .= ' (' . $curassessor->qualification . ')';
        }
        
?>
                            <option value="<?php echo $curassessor->id ?>"<?php if ($curassessor->id == $fra['assessor']) { echo ' selected'; } ?>><?php echo $fullname ?></option>
<?php
        
    }

?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="person_to_meet"><b>Person to meet*</b></label>
                        <input type="text" class="form-control" id="person_to_meet" name="person_to_meet" title="Person to meet" placeholder="Person to meet" value="{{ $fra['person_to_meet'] }}" 
                               required data-bv-notempty-message="The Person to meet is required" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="use_of_building"><b>Use of building*</b></label>
                        <input type="text" class="form-control" id="use_of_building" name="use_of_building" title="Use of building" placeholder="Use of building" value="{{ $fra['use_of_building'] }}" 
                               required data-bv-notempty-message="The Use of building is required" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="number_of_floors"><b>Number of floors*</b></label>
                        <input type="text" class="form-control" id="number_of_floors" name="number_of_floors" title="Number of floors" placeholder="Number of floors" value="{{ $fra['number_of_floors'] }}" 
                               required data-bv-notempty-message="The Number of floors is required" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="construction_type"><b>Construction Type*</b></label>
                        <select class="form-control" id="construction_type" name="construction_type" title="Construction Type" required data-bv-notempty-message="The Construction Type is required">
                            <option value="">-- Choose --</option>
                            <option value="Steel frame with glazed façade"<?php echo (('Steel frame with glazed façade' == $fra['construction_type']) ? ' selected' : '') ?>>1. Steel frame with glazed façade</option>
                            <option value="Traditional construction, masonry, concrete, steel and plasterboard internal partitions"<?php echo (('Traditional construction, masonry, concrete, steel and plasterboard internal partitions' == $fra['construction_type']) ? ' selected' : '') ?>>2. Traditional construction, masonry, concrete, steel and plasterboard internal partitions</option>
                            <option value="Other"<?php echo (('Other' == $fra['construction_type']) ? ' selected' : '') ?>>3. Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="max_number_occupants"><b>Max Number of occupants*</b></label>
                        <input type="text" class="form-control" id="max_number_occupants" name="max_number_occupants" title="Max Number of occupants" placeholder="Max Number of occupants" value="{{ $fra['max_number_occupants'] }}" 
                               required data-bv-notempty-message="The Max Number of occupants is required" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="number_employees"><b>Number of employees*</b></label>
                        <input type="text" class="form-control" id="number_employees" name="number_employees" title="Number of employees" placeholder="Number of employees" value="{{ $fra['number_employees'] }}" 
                               required data-bv-notempty-message="The Number of employees is required" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="disabled_occupants"><b>Disabled occupants*</b></label>
                        <input type="text" class="form-control" id="disabled_occupants" name="disabled_occupants" title="Disabled occupants" placeholder="Disabled occupants" value="{{ $fra['disabled_occupants'] }}" 
                               required data-bv-notempty-message="The Disabled occupants is required" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="remote_occupants"><b>Remote occupants*</b></label>
                        <input type="text" class="form-control" id="remote_occupants" name="remote_occupants" title="Remote occupants" placeholder="Remote occupants" value="{{ $fra['remote_occupants'] }}" 
                               required data-bv-notempty-message="The Remote occupants is required" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="hours_operation"><b>Hours of Operation*</b></label>
                        <textarea class="form-control" id="hours_operation" name="hours_operation" title="Hours of Operation" placeholder="Hours of Operation" required data-bv-notempty-message="The Hours of Operation is required">{{ $fra['hours_operation'] }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="survey_date"><b>Survey Date*</b></label>
                        <div id="survey_date_div">
                            <input type="text" class="form-control" id="survey_date" name="survey_date" title="Survey Date" placeholder="Survey Date" value="{{ $fra['survey_date'] }}" 
                                   required data-bv-notempty-message="The Survey Date is required" />
                        </div>
                    </div>
                    <div id="next_date_recommended_parent" class="form-group">
                        <label class="control-label" for="next_date_recommended"><b>Next Date recommended</b></label>
                        <div id="next_date_recommended_div">
                            <input type="text" class="form-control" id="next_date_recommended" name="next_date_recommended" title="Next Date recommended" placeholder="Next Date recommended" value="{{ $fra['next_date_recommended'] }}" />
                        </div>
                        <div id="ndr_choice_time">
                            <label id="ndr_choice_label_start" class="control-label" for="revision_time"><b>Next Revision in</b></label>
                            <select id="revision_time" name="revision_time" class="form-control" size="1">
                                <?php for ($n = 1; $n < 5; $n++) { ?>
                                <option value="<?php echo $n ?>"><?php echo $n ?></option>
                                <?php } ?>
                            </select>
                            <label id="ndr_choice_label_end" class="control-label" for="revision_time"><b>years</b></label>
                        </div>
                    </div>                        
                    <div class="form-group">
                        <label class="control-label" for="review_date"><b>Review Date</b></label>
                        <div id="review_date_div">
                            <input type="text" class="form-control" id="review_date" name="review_date" title="Review Date" placeholder="Review Date" value="{{ $fra['review_date'] }}" />
                        </div>
                    </div>
                    <div id="review_by_parent" class="form-group">
                        <label class="control-label" for="review_by"><b>Review by</b></label>
                        <select id="review_by" name="review_by" class="form-control" size="1">
                            <option value="">-- Choose --</option>
<?php

    foreach ($freviewers as $curreviewer) {
        $fullname = $curreviewer->name .  ' '  . $curreviewer->surname;
        if (!empty($curreviewer->qualification)) {
            $fullname .= ' (' . $curreviewer->qualification . ')';
        }
        
?>
                            <option value="<?php echo $curreviewer->id ?>"<?php if ($curreviewer->id == $fra['review_by']) { echo ' selected'; } ?>><?php echo $fullname ?></option>
<?php
        
    }

?>
                        </select>
                    </div>
                    <div id="review-signparent" class="imgdiv">
                        <div id="review-signleft" class="imgleft">
                            <input type="file" id="review-signature-file" name="review-signature-file" value="" />
                            <button type="button" id="import-review-signature" name="import-review-signature" onclick="importReviewSignature()" class="btn btn-info">Load Review Signature</button>                                
                            <button type="button" id="clean-review-signature" name="clean-review-signature" onclick="cleanReviewSignature()" class="btn btn-info">Clean Review Signature</button>
                        </div>
                        <div id="review-signright" class="imgright">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group" style="clear:left;float:left;margin-left:15px;">
            <button type="button" id="save-fra-middle" name="save-fra" onclick="submitNewFraForm()" class="btn btn-info btn-lg">Save</button>
            <button type="button" id="print-fra-middle" name="print-fra" onclick="printFraPdf()" class="btn btn-info btn-lg fra-print">Print</button>
        </div>

        <div class="clearfix"></div>

        <ul class="nav nav-tabs">
            @foreach ($rasections as $rasection)
            <li>
                <a href="#tab-{{ $rasection->id }}" data-toggle="tab">
                    <img src="/img/fra-sections/<?php echo (($rasection->id < 10) ? ('0' . $rasection->id) : $rasection->id) ?>.png" alt="{{ ucfirst(strtolower($rasection->name)) }}" title="{{ ucfirst(strtolower($rasection->name)) }}" class="tab-icon" />
                </a>
            </li>
            @endforeach
            <li>
                <a href="#tab-pictures" data-toggle="tab">
                    <img src="/img/fra-sections/others_pictures.png" alt="Others pictures" title="Others pictures" class="tab-icon" />
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            @foreach ($rasections as $k => $rasection) 
            <div id="tab-{{ $rasection->id }}" class="tab-pane<?php if ($k == 1) { echo ' active'; }?>">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-light">
                        <div class="panel-heading">
                            <h4>{{ $rasection->id }}. {{ $rasection->name }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed no-margin">
                                    <tbody>
                                        @foreach ($raquestions[$rasection->id] as $n => $raquestion)
                                        <tr id="tr_{{ $raquestion->id }}" name="tr_{{ $rasection->id }}_{{ 1 + $n }}" class="answers">
                                            <td class="quest-col1">
                                                {{ $rasection->id }}.{{ 1 + $n }} {{ $raquestion->question }}
                                            </td>
                                            <td class="quest-col2">
                                                <label class="radio-inline">
                                                    <input type="radio" id="answer_{{ $raquestion->id }}_yes" onclick="checkAnswer({{ $raquestion->id }},'yes')" name="answer_{{ $rasection->id }}_{{ 1 + $n }}"<?php echo ((!is_object($raanswers[$raquestion->id]) or (is_object($raanswers[$raquestion->id]) and !('No' == $raanswers[$raquestion->id]->answer) and !('N/A' == $raanswers[$raquestion->id]->answer))) ? ' checked' : '') ?> value="Yes" /> Yes
                                                </label>
                                                <br/>
                                                <label class="radio-inline">
                                                    <input type="radio" id="answer_{{ $raquestion->id }}_no" onclick="checkAnswer({{ $raquestion->id }},'no')" name="answer_{{ $rasection->id }}_{{ 1 + $n }}"<?php echo ((is_object($raanswers[$raquestion->id]) and ('No' == $raanswers[$raquestion->id]->answer)) ? ' checked' : '') ?> value="No" /> No
                                                </label>
                                                <br/>
                                                <label class="radio-inline">
                                                    <input type="radio" id="answer_{{ $raquestion->id }}_na" onclick="checkAnswer({{ $raquestion->id }},'na')" name="answer_{{ $rasection->id }}_{{ 1 + $n }}"<?php echo ((is_object($raanswers[$raquestion->id]) and ('N/A' == $raanswers[$raquestion->id]->answer)) ? ' checked' : '') ?> value="N/A" /> N/A
                                                </label>
                                                <br/>
                                                <label class="radio-inline">
                                                    <input type="radio" id="answer_{{ $raquestion->id }}_notknown" onclick="checkAnswer({{ $raquestion->id }},'notknown')" name="answer_{{ $rasection->id }}_{{ 1 + $n }}"<?php echo ((is_object($raanswers[$raquestion->id]) and ('Not Known' == $raanswers[$raquestion->id]->answer)) ? ' checked' : '') ?> value="Not Known" /> N/K
                                                </label>
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" id="answer_{{ $raquestion->id }}_info" name="answer_{{ $raquestion->id }}_info" class="nocolor" value="1" <?php echo ((is_object($raanswers[$raquestion->id]) and ('1' == $raanswers[$raquestion->id]->info)) ? ' checked' : '') ?>/> No Color</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="quest-col3">
                                                <select id="ready_comments_{{ $raquestion->id }}" name="ready_comments_{{ $raquestion->id }}" class="form-control ready_comments" size="1">
                                                    <option value="">-- Choose --</option>
                                                    @foreach ($rareadycomments[$raquestion->id] as $comms)
                                                    <option value="{{ $comms->id }}">{{ $comms->text }}</option>                                                            
                                                    @endforeach
                                                </select>
                                                <textarea id="comments_{{ $raquestion->id }}" name="comments_{{ $rasection->id }}_{{ 1 + $n }}" class="form-control textarea-comments ready_comments" placeholder="Comments" title="Comments"><?php echo (is_object($raanswers[$raquestion->id]) ? trim($raanswers[$raquestion->id]->comments) : '') ?></textarea>
                                                <select id="ready_recommendations_{{ $raquestion->id }}" name="ready_recommendations_{{ $raquestion->id }}" class="form-control ready_recommendations" size="1">
                                                    <option value="">-- Choose --</option>
                                                    @foreach ($rareadyrecommendations[$raquestion->id] as $recomms)
                                                    <option value="{{ $recomms->id }}">{{ $recomms->text }}</option>                                                            
                                                    @endforeach
                                                </select>
                                                <textarea id="recommendation_{{ $raquestion->id }}" name="recommendation_{{ $rasection->id }}_{{ 1 + $n }}" class="form-control ready_recommendations" placeholder="Recommendation" title="Recommendation"><?php echo (is_object($raanswers[$raquestion->id]) ? trim($raanswers[$raquestion->id]->recommendation) : '') ?></textarea>
                                            </td>
                                            <td class="quest-col4">
                                                <select name="priority-{{ $raquestion->id }}" class="form-control select-priority" size="1" title="Priority Code">
                                                    <option value="0">0</option>
                                                    <option value="1"<?php echo ((!empty($raanswers[$raquestion->id]) and ('1' == $raanswers[$raquestion->id]->priority_code)) ? ' selected' : '') ?>>1</option>
                                                    <option value="2"<?php echo ((!empty($raanswers[$raquestion->id]) and ('2' == $raanswers[$raquestion->id]->priority_code)) ? ' selected' : '') ?>>2</option>
                                                    <option value="3"<?php echo ((!empty($raanswers[$raquestion->id]) and ('3' == $raanswers[$raquestion->id]->priority_code)) ? ' selected' : '') ?>>3</option>
                                                    <option value="4"<?php echo ((!empty($raanswers[$raquestion->id]) and ('4' == $raanswers[$raquestion->id]->priority_code)) ? ' selected' : '') ?>>4</option>
                                                    <option value="5"<?php echo ((!empty($raanswers[$raquestion->id]) and ('5' == $raanswers[$raquestion->id]->priority_code)) ? ' selected' : '') ?>>5</option>
                                                </select>
                                                <div id="priority-{{ $raquestion->id }}" class="div-priority" title="Grading color"></div>
                                                <select id="action_by_whom-{{ $raquestion->id }}" name="action_by_whom-{{ $raquestion->id }}" class="form-control" title="Action by Whom" size="1">
                                                    <option value="">-- Choose --</option>
                                                    <option value="Store Manager"<?php echo ((!empty($raanswers[$raquestion->id]) and ('Store Manager' == $raanswers[$raquestion->id]->action_by_whom)) ? ' selected' : '') ?>>Store Manager</option>
                                                    <option value="Maintenance Team"<?php echo ((!empty($raanswers[$raquestion->id]) and ('Maintenance Team' == $raanswers[$raquestion->id]->action_by_whom)) ? ' selected' : '') ?>>Maintenance Team</option>
                                                    <option value="H & S Dept"<?php echo ((!empty($raanswers[$raquestion->id]) and ('H & S Dept' == $raanswers[$raquestion->id]->action_by_whom)) ? ' selected' : '') ?>>H & S Dept</option>
                                                    <option value="Other"<?php echo ((!empty($raanswers[$raquestion->id]) and ('Other' == $raanswers[$raquestion->id]->action_by_whom)) ? ' selected' : '') ?>>Other</option>
                                                </select>
                                                <input type="text" id="date_of_completion-{{ $raquestion->id }}" name="date_of_completion-{{ $raquestion->id }}" title="Completion Date" value="<?php echo $raanswers[$raquestion->id]->date_of_completion ?>" class="form-control plans completion" placeholder="Completion" />
                                                <input type="text" id="actioned_by-{{ $raquestion->id }}" name="actioned_by-{{ $raquestion->id }}" title="Actioned by" value="<?php echo $raanswers[$raquestion->id]->actioned_by ?>" class="form-control plans" placeholder="Actioned by" />
                                            </td>
                                            <td class="quest-col5">
                                                <div id="pictureparent_{{ $raquestion->id }}" class="imgdiv">
                                                    <div id="pictureleft_{{ $raquestion->id }}" class="imgleft imgques">
                                                        <input type="file" id="picture-file-{{ $raquestion->id }}" name="picture-file-{{ $raquestion->id }}" class="picture-upload" value="" />
                                                        <button type="button" id="import-picture-{{ $raquestion->id }}" name="import-picture-{{ $raquestion->id }}" onclick="importPicture('{{ $raquestion->id }}')" class="btn btn-info btn-ques">Load Picture</button>                                
                                                        <button type="button" id="clean-picture-{{ $raquestion->id }}" name="clean-picture" onclick="cleanPicture('{{ $raquestion->id }}')" class="btn btn-info btn-ques">Clean Picture</button>
                                                    </div>
                                                    <div id="pictureright_{{ $raquestion->id }}" class="imgright pictques">

                                                    </div>
                                                    <input type="hidden" id="picture-{{ $raquestion->id }}" name="picture-{{ $raquestion->id }}" value="<?php echo (!empty($raanswers[$raquestion->id]->picture) ? ('/fra' . $raanswers[$raquestion->id]->picture) : '') ?>" />
                                                </div>
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
            
            <div id="tab-pictures" class="tab-pane<?php if ($k == 1) { echo ' active'; }?>">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-light">
                        <div class="panel-heading">
                            <h4>Others pictures</h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="others" class="table table-bordered table-condensed no-margin">
                                    <tbody>
                                        <tr id="tr_others_0" name="tr_others_0" class="pictures_tr">
                                            <td class="others-col1">
                                                <div id="pictureparent_others-0" class="imgdiv">
                                                    <div id="pictureleft_others_0" class="imgleft imgques">
                                                        <input type="file" id="picture-file-others-0" name="picture-file-others-0" class="picture-upload" value="" />
                                                        <button type="button" id="import-picture-others-0" name="import-picture-others-0" onclick="importPicture('others-0')" class="btn btn-info btn-ques">Load Picture</button>                                
                                                        <button type="button" id="clean-picture-others-0" name="clean-picture" onclick="cleanPicture('others-0')" class="btn btn-info btn-ques">Clean Picture</button>
                                                    </div>
                                                    <div id="pictureright_others-0" class="imgright pictques">

                                                    </div>
                                                    <input type="hidden" id="picture-others-0" name="picture-others-0" value="" />
                                                </div>
                                            </td>
                                            <td class="others-col2">
                                                <textarea id="caption-others-0" name="caption-others-0" class="form-control textarea-comments" placeholder="Caption" title="Caption"></textarea>
                                            </td>
                                            <td class="others-col3">
                                                <select id="section-others-0" name="section-others-0" class="form-control" size="1">
                                                    <option value="">--Choose--</option>
                                                @foreach ($rasections as $k => $rasection)
                                                    <option value="{{ $rasection->id }}">{{ $rasection->id }}. {{ $rasection->name }}</option>
                                                @endforeach
                                                </select>
                                            </td>
                                            <td class="others-col4">
                                                <button type="button" id="add-picture-others-0" name="add-picture-others-0" onclick="addOtherPicture()" class="btn btn-info btn-ques">Add Picture</button>
                                            </td>
                                        </tr>
                                        @foreach ($raothers as $raother)
                                        <tr id="tr_others_{{ $raother->id }}" name="tr_others_{{ $raother->id }}" class="pictures_tr">
                                            <td class="others-col1">
                                                <div id="pictureright_others-{{ $raother->id }}" class="imgright pictques">
                                                    <script type="text/javascript">
                                                        jQuery(document).ready(function() {
                                                            adaptImage(escape("/fra{{ $raother->picture }}"),'#pictureright_others-{{ $raother->id }}',198,198);
                                                        });
                                                    </script>
                                                </div>
                                            </td>
                                            <td class="others-col2">
                                                {{ $raother->caption }}
                                            </td>
                                            <td class="others-col3">
                                                {{ $rasections[$raother->rasection_id]->id }}. {{ $rasections[$raother->rasection_id]->name }}
                                            </td>
                                            <td class="others-col4">
                                                <button type="button" id="remove-picture-others-{{ $raother->id }}" name="remove-picture-others" class="btn btn-info btn-ques remove-others">Remove Picture</button>
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
        
        <div class="form-group" style="clear:left;float:left;margin-left:15px;">
            <button type="button" id="save-fra-after-questions" name="save-fra" onclick="submitNewFraForm()" class="btn btn-info btn-lg">Save</button>
            <button type="button" id="print-fra-after-questions" name="print-fra" onclick="printFraPdf()" class="btn btn-info btn-lg fra-print">Print</button>
        </div>
            
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-body">
                    <div id="executive_summary_parent" class="form-group">
                        <label class="control-label" for="executive_summary"><b>Executive Summary</b></label>
                        <textarea class="form-control" id="executive_summary" name="executive_summary" title="Executive Summary" placeholder="Executive Summary"><?php echo $fra['executive_summary'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="fire_loss_experience"><b>Fire Loss Experience</b></label>
                        <select id="fire_loss_experience" name="fire_loss_experience" class="form-control" size="1">
                            <option value="None">None</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="relevant_fire_safety_legislation"><b>Relevant Fire Safety Legislation</b></label>
                        <textarea class="form-control" id="relevant_fire_safety_legislation" name="relevant_fire_safety_legislation" title="Relevant Fire Safety Legislation" placeholder="Relevant Fire Safety Legislation"><?php echo $fra['relevant_fire_safety_legislation'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="hazard_from_fire"><b>Hazard from Fire</b></label>
                        <select id="hazard_from_fire" name="hazard_from_fire" class="form-control" size="1">
                            <option value="">-- Choose --</option>
                            <option value="1"<?php echo (('1' == $fra['hazard_from_fire']) ? ' selected' : '') ?>>Low Risk</option>
                            <option value="2"<?php echo (('2' == $fra['hazard_from_fire']) ? ' selected' : '') ?>>Medium Risk</option>
                            <option value="3"<?php echo (('3' == $fra['hazard_from_fire']) ? ' selected' : '') ?>>High Risk</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="life_safety"><b>Life Safety Harmness</b></label>
                        <select id="life_safety" name="life_safety" class="form-control" size="1">
                            <option value="">-- Choose --</option>
                            <option value="1"<?php echo (('1' == $fra['life_safety']) ? ' selected' : '') ?>>Slight Harm</option>
                            <option value="2"<?php echo (('2' == $fra['life_safety']) ? ' selected' : '') ?>>Moderate Harm</option>
                            <option value="3"<?php echo (('3' == $fra['life_safety']) ? ' selected' : '') ?>>Extreme Harm</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="general_fire_risk"><b>General Fire Risk</b></label>
                        <select id="general_fire_risk" name="general_fire_risk" class="form-control" size="1">
                            <option value="">-- Choose --</option>
                            <option value="1"<?php echo (('1' == $fra['general_fire_risk']) ? ' selected' : '') ?>>Trivial</option>
                            <option value="2"<?php echo (('2' == $fra['general_fire_risk']) ? ' selected' : '') ?>>Tolerable</option>
                            <option value="3"<?php echo (('3' == $fra['general_fire_risk']) ? ' selected' : '') ?>>Moderate</option>
                            <option value="4"<?php echo (('4' == $fra['general_fire_risk']) ? ' selected' : '') ?>>Substantial</option>
                            <option value="5"<?php echo (('5' == $fra['general_fire_risk']) ? ' selected' : '') ?>>Intolerable</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-body">
                    <div class="form-group">
                        <div class="checkbox">
                            <label><input type="checkbox" id="completed" name="completed" value="1" <?php echo ((1 == $completed) ? 'checked ' : '') ?>/> Completed</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="clear:left;float:left;margin-left:15px;">
            <button type="button" id="save-fra-end" name="save-fra" onclick="submitNewFraForm()" class="btn btn-info btn-lg">Save</button>
            <button type="button" id="print-fra-end" name="print-fra" onclick="printFraPdf()" class="btn btn-info btn-lg fra-print">Print</button>
        </div>        
    </form>
</div>
@endsection
