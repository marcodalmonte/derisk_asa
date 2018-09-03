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

if (!empty($removal['prepared_by_signature'])) {
    
?>
            picture_url = '/removals<?php echo $removal['prepared_by_signature'] ?>';            

            jQuery('#prepared_by_signature').val(picture_url);
            adaptImage(picture_url,'#prepright',198,198);
        
<?php
    
}  else {
    
?>
            jQuery('#prepright').html('<span class="file-not-loaded">Not Loaded</span>');
        
<?php    
    
}

if (!empty($removal['approved_by_signature'])) {
    
?>
            picture_url = '/removals<?php echo $removal['approved_by_signature'] ?>';            

            jQuery('#approved_by_signature').val(picture_url);
            adaptImage(picture_url,'#approvedright',198,198);

<?php
    
} else {
    
?>
            jQuery('#approvedright').html('<span class="file-not-loaded">Not Loaded</span>');
        
<?php    
    
}

if (!empty($removal['site_picture'])) {
    
?>
            picture_url = '/removals<?php echo $removal['site_picture'] ?>';            

            jQuery('#site_picture').val(picture_url);
            adaptImage(picture_url,'#siteright',198,198);
        
<?php
    
} else {
    
?>
            jQuery('#siteright').html('<span class="file-not-loaded">Not Loaded</span>');
        
<?php    
    
}

if (!empty($removal['map_picture'])) {
    
?>
            picture_url = '/removals<?php echo $removal['map_picture'] ?>';            

            jQuery('#map_picture').val(picture_url);
            adaptImage(picture_url,'#mapright',198,198);
        
<?php
    
} else {
    
?>
            jQuery('#mapright').html('<span class="file-not-loaded">Not Loaded</span>');
        
<?php    
    
}

if (!empty($removal['floor_plans'])) {
    
?>
            jQuery('#floorright').html('<span class="file-loaded">Loaded</span>');
        
<?php
    
} else {
    
?>
            jQuery('#floorright').html('<span class="file-not-loaded">Not Loaded</span>');
        
<?php    
    
}

if (!empty($removal['access_routes'])) {
    
?>
            jQuery('#accessright').html('<span class="file-loaded">Loaded</span>');
        
<?php
    
} else {
    
?>
            jQuery('#accessright').html('<span class="file-not-loaded">Not Loaded</span>');
        
<?php    
    
}

if (!empty($removal['bulk_analysis_certificate'])) {
    
?>
            jQuery('#bulkright').html('<span class="file-loaded">Loaded</span>');
        
<?php
    
} else {
    
?>
            jQuery('#bulkright').html('<span class="file-not-loaded">Not Loaded</span>');
        
<?php    
    
}

$prelims = $removal['preliminaries'];

if (empty($prelims) and empty($removal['id'])) {
    $prelims = '<b>Requirements of quotation</b><br><br>Contractors must attend a pre-arranged accompanied site visit with Derisk (UK) Ltd to assess the works prior to submitting costs and programme.<br>This specification is for the removal of asbestos containing materials identified in the following areas at …………………………………………………………..by a Licensed Asbestos Removal Contractor (LARC) to allow ………………………. work to be undertaken.<br>The information within this specification is based on the following HSG264 surveys undertaken by Derisk (UK) Ltd<br><ul><li>…………………………………</li></ul><br>Extracts from the surveys are included within this specification but the full reports are available for review and inspection upon request.<br>Photographic evidence, dimensions and observations from the survey are provided as part of this specification for remediation works. All dimensions should be taken as a guide, it is the responsibility of the contractor to ensure to make their own estimates for tendering purposes.<br>The Contractor is not required to include any costs for air testing as this will be procured independently.<br><br><span style="font-weight: bold;">General Conditions/Requirements</span><br><br>The property is a …………………………………………………………………………. The works must be designed to protect any other persons from the works including maintaining a secure segregated working area to incorporate the enclosure and, where deemed necessary, a secure site compound/area to incorporate the hygiene unit and waste receptacle/local waste storage area.<br>Working hours are 0800 – 1700 hrs Monday – Sunday. The LARC shall devise a program that is appropriate and suitable for the location of the site to allow access into the site and also for the delivery of materials, plant or equipment and for the safe and appropriate disposal of waste from the site. All costs associated with transport, parking and any necessary local permits are deemed included within their tender bid. The LARC is responsible for assessing the requirements for parking and or any local permits.<br>The site is ……………………………but consideration must be given to waste runs or where waste can be safely stored until it can be taken from . Any temporary waste storage areas are deemed included within the tender submitted and this shall include any making good where temporary waste storage areas are built inside the main building.<br>The Contractor must ensure there is suitable provision for alerting operatives inside the enclosure and also for operatives to raise an alarm that may originate from inside the enclosure. No hot works will be allowed inside any work area without permission of the Client Safety Team. The proposed system for fire precautions will be agreed with the Client prior to commencement of the project works.<br><span style="color:#365F91;font-weight: bold;"><br>Site set up (preliminary notes)</span><br><br>The floor plans attached are from the surveys to provide reference to the observations, sample analysis and the room references. The licensed contractor will need to create their own plans to define the site set-up. These can be provided to the successful contractor.<br>All facilities required by the LARCs work will need to be contained within the building. The client has …………………………space outside of the building. <br>The Contractors shall ensure they have visited site and familiarised themselves with the local site conditions and specification requirements and local site rules.<br>Power and water are/are not available on site and to be agreed with the client for use, the Contractors shall ensure adequate services are available when the Plan of Work is prepared.<br>Lighting remains live within the building although the contractor shall ensure provision of all adequate task lighting to safely carry out their specified works.<br>The contractor shall remain responsible for organising the works in the most efficient timescale and shall be deemed responsible for progression of the program and in reducing all delays.<br>Welfare is available in the ………………………………. the Contractor remains responsible for ensuring that these facilities remain safe, clean and tidy. However, the contractor shall ensure adequate welfare is available once the enclosure is built. The Contractor shall be responsible to the client in the event that these facilities require cleaning as a result of use by the Contractor.<br>The Contractor shall ensure they provide all adequate welfare facilities for the number of operatives to be utilised during the planned works. All costs associated with the delivery, provision and maintenance of welfare facilities is deemed included within the final tendered price submitted by the Contractor. These shall remain the responsibility and liability of the Contractor at all times.<br><div>The contractor shall remain responsible for organising the works in the most efficient timescale and shall be deemed responsible for ensuring the agreed programme is met. No additional costs can be submitted if the programme is delayed unless the delay is through no fault of the contractor and agreed with …………………………… and Derisk (UK) Ltd prior to the event. Retrospective variations will not be considered.';
}

$prelims = str_replace("\n","<br/>",$prelims);
$prelims = str_replace("<br/>","<br>",$prelims);
$prelims = str_replace('"',"'",$prelims);

$generalRequirements = $removal['general_requirements'];
$generalRequirements = str_replace("\n","<br/>",$generalRequirements);
$generalRequirements = str_replace("<br/>","<br>",$generalRequirements);
$generalRequirements = str_replace('"',"'",$generalRequirements);

?>
        
            jQuery("#preliminaries").Editor();
            jQuery("#preliminaries").Editor("setText","<?php echo $prelims ?>");
            
            jQuery("#general_requirements").Editor();
            jQuery("#general_requirements").Editor("setText","<?php echo $generalRequirements ?>");
        });
    </script>
    
    <div class="row gutter">
        <form id="add-removal"
            enctype="multipart/form-data" 
            method="post" 
            action="{{ URL::to('/saveSpec') }}"
            data-bv-message="This value is not valid"
            data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
            data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
            data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

            {{ csrf_field() }}

            <div class="form-group" style="clear:left;float:left;margin-left:15px;">
                <button type="button" id="save-removal-start" name="save-removal" onclick="submitNewRemovalForm()" class="btn btn-info btn-lg">Save</button>
                <button type="button" id="print-removal-start" name="print-removal" onclick="printRemovalPdf(<?php echo (!empty($removal['id']) ? $removal['id'] : '"new"') ?>)" class="btn btn-info btn-lg removal-print">Print</button>
            </div>

            <div class="clearfix"></div>
            
            <input type="hidden" id="id" name="id" value="<?php echo (!empty($removal['id']) ? $removal['id'] : 'new') ?>" />
            <input type="hidden" id="prepared_by_signature" name="prepared_by_signature" value="<?php echo (!empty($removal['prepared_by_signature']) ? ('/removals' . $removal['prepared_by_signature']) : '') ?>" />
            <input type="hidden" id="approved_by_signature" name="approved_by_signature" value="<?php echo (!empty($removal['approved_by_signature']) ? ('/removals' . $removal['approved_by_signature']) : '') ?>" />
            <input type="hidden" id="site_picture" name="site_picture" value="<?php echo (!empty($removal['site_picture']) ? ('/removals' . $removal['site_picture']) : '') ?>" />
            <input type="hidden" id="map_picture" name="map_picture" value="<?php echo (!empty($removal['map_picture']) ? ('/removals' . $removal['map_picture']) : '') ?>" />
            <input type="hidden" id="floor_plans" name="floor_plans" value="<?php echo (!empty($removal['floor_plans']) ? ('/removals' . $removal['floor_plans']) : '') ?>" />
            <input type="hidden" id="access_routes" name="access_routes" value="<?php echo (!empty($removal['access_routes']) ? ('/removals' . $removal['access_routes']) : '') ?>" />
            <input type="hidden" id="bulk_analysis_certificate" name="bulk_analysis_certificate" value="<?php echo (!empty($removal['bulk_analysis_certificate']) ? ('/removals' . $removal['bulk_analysis_certificate']) : '') ?>" />
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" id="new_revision" name="new_revision" value="1"<?php echo (empty($removal['id']) ? ' checked' : '') ?> />New Issue?</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="revision_comments"><b>Issue Comments</b></label>
                            <textarea class="form-control" id="revision_comments" name="revision_comments" title="Issue Comments" placeholder="Issue Comments">{{ $removal['comments'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-light">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label" for="surveys"><b>Surveys*</b></label>
                            <select id="surveys" name="surveys" class="form-control" size="1" required data-bv-notempty-message="You have to choose the Surveys!" multiple="">
                                <option value=""<?php echo (empty($removal['surveys']) ? ' selected' : '') ?>>-- Choose --</option>
                            @foreach ($surveys as $choice) 
                                <option value="{{ $choice['id'] }}"<?php echo (in_array($choice['id'],$removal['surveys']) ? ' selected' : '') ?>>{{ $choice['ukasnumber']}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="project_ref"><b>Project Ref*</b></label>
                            <input type="text" class="form-control" id="project_ref" name="project_ref" title="Project Ref" placeholder="Project Ref" value="{{ $removal['project_ref'] }}" 
                                   required data-bv-notempty-message="The Project Ref is required for the first page of the report" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="area"><b>Area*</b></label>
                            <input type="text" class="form-control" id="area" name="area" title="Area" placeholder="Area" value="{{ $removal['area'] }}" 
                                   required data-bv-notempty-message="The area is required for the first page of the report" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="address"><b>Address*</b></label>
                            <textarea class="form-control" id="address" name="address" title="Address" placeholder="Address" required data-bv-notempty-message="The address is required">{{ $removal['address'] }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="prepared_for"><b>Prepared for*</b></label>
                            <input type="text" class="form-control" id="prepared_for" name="prepared_for" title="Prepared for" placeholder="Prepared for" value="{{ $removal['prepared_for'] }}" 
                                   required data-bv-notempty-message="You have to specify who the report is for" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="prepared_by"><b>Prepared by*</b></label>
                            <input type="text" class="form-control" id="prepared_by" name="prepared_by" title="Prepared by" placeholder="Prepared by" value="{{ $removal['prepared_by'] }}" 
                                   required data-bv-notempty-message="Please, add the name of the person that prepared the report" />
                        </div>
                        <div id="prepparent" class="imgdiv">
                            <div id="prepleft" class="imgleft">
                                <input type="file" id="prepared-file" name="prepared-file" value="" />
                                <button type="button" id="import-prepared" name="import-prepared" onclick="importPreparedBy()" class="btn btn-info">Load Signature</button>
                                <button type="button" id="clean-prepared" name="clean-prepared" onclick="cleanPreparedBy()" class="btn btn-info">Clean Signature</button>
                            </div>
                            <div id="prepright" class="imgright">

                            </div>
                        </div>
                        <div id="preparation_date_parent" class="form-group">
                            <label class="control-label" for="preparation_date"><b>Preparation Date*</b></label>
                            <div id="preparation_date_div">
                                <input type="text" class="form-control" id="preparation_date" name="preparation_date" title="Preparation Date" placeholder="Preparation Date" value="{{ $removal['preparation_date'] }}" required data-bv-notempty-message="Please, set the date when the report has been prepared" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="approved_by"><b>Approved by*</b></label>
                            <input type="text" class="form-control" id="approved_by" name="approved_by" title="Approved by" placeholder="Approved by" value="{{ $removal['approved_by'] }}" 
                                   required data-bv-notempty-message="Please, add the name of the person that approved the report" />
                        </div>
                        <div id="approvedparent" class="imgdiv">
                            <div id="approvedleft" class="imgleft">
                                <input type="file" id="approved-file" name="approved-file" value="" />
                                <button type="button" id="import-approved" name="import-approved" onclick="importApprovedBy()" class="btn btn-info">Load Signature</button>
                                <button type="button" id="clean-approved" name="clean-approved" onclick="cleanApprovedBy()" class="btn btn-info">Clean Signature</button>
                            </div>
                            <div id="approvedright" class="imgright">

                            </div>
                        </div>
                        <div id="approval_parent" class="form-group">
                            <label class="control-label" for="approval_date"><b>Approval Date</b></label>
                            <div id="approval_date_div">
                                <input type="text" class="form-control" id="approval_date" name="approval_date" title="Approval Date" placeholder="Approval Date" value="{{ $removal['approval_date'] }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="preliminaries"><b>Preliminaries</b></label>
                            <textarea class="txtEditor" id="preliminaries" name="preliminaries" title="Preliminaries" placeholder="Preliminaries"><?php $removal['preliminaries'] ?></textarea>
                        </div>
                        <div id="siteparent" class="imgdiv">
                            <div id="siteleft" class="imgleft">
                                <input type="file" id="site-file" name="site-file" value="" />
                                <button type="button" id="import-site" name="import-site" onclick="importSitePicture()" class="btn btn-info">Load Site Picture</button>
                                <button type="button" id="clean-site" name="clean-site" onclick="cleanSitePicture()" class="btn btn-info">Clean Site Picture</button>
                            </div>
                            <div id="siteright" class="imgright">

                            </div>
                        </div>
                        <div id="mapparent" class="imgdiv">
                            <div id="mapleft" class="imgleft">
                                <input type="file" id="map-file" name="map-file" value="" />
                                <button type="button" id="import-map" name="import-map" onclick="importMapPicture()" class="btn btn-info">Load Map Picture</button>
                                <button type="button" id="clean-map" name="clean-map" onclick="cleanMapPicture()" class="btn btn-info">Clean Map Picture</button>
                            </div>
                            <div id="mapright" class="imgright">

                            </div>
                        </div>
                        <div id="floorparent" class="imgdiv">
                            <div id="floorleft" class="imgleft">
                                <input type="file" id="floor-file" name="floor-file" value="" />
                                <button type="button" id="import-floor" name="import-floor" onclick="importFloorPlansFile()" class="btn btn-info">Load Floor Plans</button>
                                <button type="button" id="clean-floor" name="clean-floor" onclick="cleanFloorPlansFile()" class="btn btn-info">Clean Floor Plans</button>
                            </div>
                            <div id="floorright" class="imgright">

                            </div>
                        </div>
                        <div id="accessparent" class="imgdiv">
                            <div id="accessleft" class="imgleft">
                                <input type="file" id="access-file" name="access-file" value="" />
                                <button type="button" id="import-access" name="import-access" onclick="importRoutesAccessFile()" class="btn btn-info">Load Routes Access Plans</button>
                                <button type="button" id="clean-access" name="clean-access" onclick="cleanRoutesAccessFile()" class="btn btn-info">Clean Routes Access Plans</button>
                            </div>
                            <div id="accessright" class="imgright">

                            </div>
                        </div>
                        <div id="bulkparent" class="imgdiv">
                            <div id="bulkleft" class="imgleft">
                                <input type="file" id="bulk-file" name="bulk-file" value="" />
                                <button type="button" id="import-bulk" name="import-bulk" onclick="importBulkAnalysisFile()" class="btn btn-info">Load Bulk Analysis Certificate</button>
                                <button type="button" id="clean-bulk" name="clean-bulk" onclick="cleanBulkAnalysisFile()" class="btn btn-info">Clean Bulk Analysis Certificate</button>
                            </div>
                            <div id="bulkright" class="imgright">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="general_requirements"><b>General Requirements</b></label>
                            <textarea class="txtEditor" id="general_requirements" name="general_requirements" title="General Requirements" placeholder="General Requirements"><?php echo $removal['general_requirements'] ?></textarea>
                        </div>                      
                    </div>
                </div>
            </div>
            
            <div class="form-group" style="clear:left;float:left;margin-left:15px;">
                <button type="button" id="save-removal-middle" name="save-removal" onclick="submitNewRemovalForm()" class="btn btn-info btn-lg">Save</button>
                <button type="button" id="print-removal-middle" name="print-removal" onclick="printRemovalPdf(<?php echo (!empty($removal['id']) ? $removal['id'] : '"new"') ?>)" class="btn btn-info btn-lg removal-print">Print</button>
            </div>

            @if (!empty($areas))
            
            <div class="clearfix"></div>
            
            <ul class="nav nav-tabs">
                @foreach ($areas as $k => $remarea)
                <li<?php if ($k == 0) { echo ' class="active"'; } ?>>
                    <a id="mytab-{{ $remarea->id }}" href="#tab-{{ $remarea->id }}" data-toggle="tab">
                        <b><?php echo $remarea->building . ' - ' . $remarea->name ?></b>
                    </a>
                </li>
                @endforeach
            </ul>
            
            <div class="tab-content">
                @foreach ($areas as $k => $remarea)
                <div id="tab-{{ $remarea->id }}" class="tab-pane<?php if ($k == 0) { echo ' active'; } ?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-light">
                            <div class="panel-heading">
                                <input type="text" id="remarea_<?php echo $remarea->id ?>_name" name="remarea_<?php echo $remarea->id ?>_name" class="form-control areas_title" value="<?php echo $remarea->building . ' - ' . $remarea->name ?>" />
                                <button type="button" id="save-area-<?php echo $remarea->id ?>" name="save-area" onclick="saveAreaTitle(<?php echo $remarea->id ?>)" class="btn btn-info">Save title</button>
                                <span id="saving_message_<?php echo $remarea->id ?>" class="saving_message"></span>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <textarea class="form-control textarea_text" id="remarea_<?php echo $remarea->id ?>_text" name="remarea_<?php echo $remarea->id ?>_text" title="Area Text" placeholder="Area Text"><?php echo $remarea->text ?></textarea>
                                    <button type="button" id="save-area-<?php echo $remarea->id ?>-text" name="save-area-text" onclick="saveAreaText(<?php echo $remarea->id ?>)" class="btn btn-info save-area-text">Save text</button>
                                    <span id="saving_message_<?php echo $remarea->id ?>_text" class="saving_message_text"></span>
                                </div>
                                
                                <script type="text/javascript">
                                    jQuery(document).ready(function() {
                                        jQuery("#remarea_<?php echo $remarea->id ?>_text").Editor();
                                        jQuery("#remarea_<?php echo $remarea->id ?>_text").Editor("setText","<?php echo $remarea->text ?>");
                                    });
                                </script>
                                
                                <div class="clearfix"></div>
                                
                                <div class="table-responsive">
                                    <table id="areas_inspections_table_{{ $remarea->id }}" class="table table-bordered table-condensed no-margin areas_inspections_table">
                                        <tbody>
                                            <tr>
                                                <th>Inspection No</th>
                                                <th>Room</th>
                                                <th>Extent</th>
                                                <th>Product</th>
                                                <th>Surface Treatment</th>
                                                <th>Results</th>
                                                <th>Extent of Damage</th>
                                                <th>Comments<br/>Recommendations</th>
                                                <th>Actions</th>
                                            </tr>
                                            @foreach ($remarea->inspections as $n => $curinspection)
                                            <tr>
                                                <td class="areas_inspno"><?php echo $curinspection->inspection_no ?></td>
                                                <td><input type="text" id="room_<?php echo $curinspection->id ?>" name="room_<?php echo $curinspection->id ?>" class="form-control areainsp" value="<?php echo $curinspection->room ?>" /></td>
                                                <td><input type="text" id="quantity_<?php echo $curinspection->id ?>" name="quantity_<?php echo $curinspection->id ?>" class="form-control areainsp input_extent" value="<?php echo $curinspection->quantity ?>" /></td>
                                                <td><input type="text" id="product_<?php echo $curinspection->id ?>" name="product_<?php echo $curinspection->id ?>" class="form-control areainsp" value="<?php echo $curinspection->product ?>" /></td>
                                                <td><input type="text" id="surface_treatment_<?php echo $curinspection->id ?>" name="surface_treatment_<?php echo $curinspection->id ?>" class="form-control areainsp" value="<?php echo $curinspection->surface_treatment ?>" /></td>
                                                <td><input type="text" id="result_<?php echo $curinspection->id ?>" name="result_<?php echo $curinspection->id ?>" class="form-control areainsp" value="<?php echo $curinspection->result ?>" /></td>
                                                <td>
                                                    <select id="damage_<?php echo $curinspection->id ?>" name="damage_<?php echo $curinspection->id ?>" class="form-control areainsp" size="1">
                                                        <option value="">-- Choose --</option>
                                                        <option value="Low"<?php if ("Low" == $curinspection->extent_of_damage) { echo " selected"; } ?>>Low</option>
                                                        <option value="Medium"<?php if ("Medium" == $curinspection->extent_of_damage) { echo " selected"; } ?>>Medium</option>
                                                        <option value="High"<?php if ("High" == $curinspection->extent_of_damage) { echo " selected"; } ?>>High</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea id="comments_{{ $curinspection->id }}" name="comments_{{ $curinspection->id }}" class="form-control">{{ $curinspection->comment }}</textarea>
                                                    <textarea id="recommendations_{{ $curinspection->id }}" name="recommendations_{{ $curinspection->id }}" class="form-control">{{ $curinspection->recommendation }}</textarea>
                                                </td>
                                                <td>
                                                    <button type="button" id="save-inspection-<?php echo $curinspection->id ?>" name="save-inspection" onclick="saveRemovalInspection(<?php echo $curinspection->id ?>)" class="btn btn-info">Save</button>
                                                    <span id="saving_inspection_message_<?php echo $curinspection->id ?>" class="saving_inspection_message"></span>
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
            
            <div class="clearfix"></div>

            <div class="form-group" style="clear:left;float:left;margin-left:15px;">
                <button type="button" id="save-removal-end" name="save-removal" onclick="submitNewRemovalForm()" class="btn btn-info btn-lg">Save</button>
                <button type="button" id="print-removal-end" name="print-removal" onclick="printRemovalPdf(<?php echo (!empty($removal['id']) ? $removal['id'] : '"new"') ?>)" class="btn btn-info btn-lg removal-print">Print</button>
            </div>
            
            @endif
        </form>
    </div>
@endsection