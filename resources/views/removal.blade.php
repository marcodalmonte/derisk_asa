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

if (empty($generalRequirements) and empty($removal['id'])) {
    $generalRequirements = '<span style="font-weight: bold;">No claim for extras will be admitted for any error or omission arising from the asbestos removal contractor’s failure to satisfy themselves on any of the following matters.</span><br><br>General requirements from the asbestos removal contractor;<br><br>The successful contractor must supply detailed copies of the following prior to commencement of the works:<br><ul><li>Asbestos licence</li><li>Relevant insurance documents</li><li>Detailed plan of work/method statement</li><li>HSE notification form ASB5</li><li>Training records for all operatives working with asbestos on site (including copies of SMSTS certification for all supervisory staff)</li><li>RPE records and face fit certificates for all operatives working with asbestos on site</li><li>Medical records for operatives working with asbestos</li><li>All other documentation in accordance with the Control of Asbestos Regulations 2012.</li><li>Risk assessments </li><li>COSHH assessments </li><li>Standard company procedures</li><li>A clearance certificate for the decontamination unit on site</li><li>Inspection/test records for all equipment to be used on site</li></ul><br>These records must be on site during the works, kept in a controlled and tidy site file.&nbsp; The site supervisor shall be responsible for this file and produce documents on request.<br><br><span style="font-weight: bold;">Health and Safety</span><br><br>The Client and his appointed professional team recognise that health and safety is of paramount importance.&nbsp; Under no circumstances is the Health of the Employer’s Employees, the school occupants, the public or visitors to the site to be compromised.<br><br>The asbestos removal contractor shall comply within the current Health and Safety Legislative requirements and guidance notes for the duration of the work.<br><br>All work procedures and activities, including roles, responsibilities and communications regarding the works are to be included within the asbestos removal contractor site specific plan of work/method statement.<br><br>The requirements stipulated within the tender documentation, which are relevant to the proposed works, shall be incorporated within the asbestos removal contractor site specific plan of work/method statements.<br><br>Existing procedures may exist throughout the site.&nbsp; Details of any existing procedures appertaining to the proposed works shall be made clear to the asbestos removal contractor prior to works commencing, this particularly relevant in relation to the management of risk associated with fire and evacuation of the building.<br><br>Where the asbestos removal contractor becomes aware of significant hazards which have not been adequately dealt with by the design process he shall inform the contract administration immediately.<br><br>A competent person shall undertake the specific risk assessment for working in the confined space and only competent persons will be authorised to access and work in the confined space. The contractor must ensure that this is rigorously managed.<br><br>All work equipment shall be provided, used and maintained in a proper manner.<br><br>The contractor shall be deemed to have visited the site to satisfy himself as to the nature and extent of the works, local conditions and any other conditions affecting the completion of the works prior to the submission of the competitive quotation and he is to allow for any extra costs he may consider involved by reason of the position of the site, difficulty of access, or from any other causes. <br><br>The asbestos removal contractor shall include provision of all necessary working and access equipment in full compliance with all Health and Safety legislation to enable safe access to complete works.<br><br>If proven to be responsible for any damage to the fabric or fixtures within the building, the cost of repairs/replacements will be recoverable from the asbestos removal contractor.<br><br><span style="font-weight: bold;">Standard of Work/Decontamination/Cleaning<br></span><br>The asbestos removal contractor will include for the removal of all non-asbestos materials within the works area, which cannot be satisfactorily cleaned, as hazardous waste.&nbsp; The disposal of these items shall also be included.&nbsp; This shall include all materials used to build the enclosure and/or segregate the work area and all other materials and equipment used at any stage of the removal works.<br><br>Once authorisation has been given by the contract administrator the asbestos removal contractor shall seal all vents and openings prior to commencement of the works.&nbsp; All ventilation trunking, and any man made mineral fibre insulation must be removed and disposed of as contaminated waste. <br><br>The asbestos removal contractor tender price shall be deemed to have included for the complete removal of all the specified asbestos material, dust, fibres and decorative paintwork along with any bonding materials.<br><br>The asbestos removal contractor shall be deemed to have included for all costs involved with the full and complete removal of all ACMs detailed herein including any associated work to ensure successful and complete execution of the Certificate of Reoccupation, as appropriate.<br><br>The asbestos removal contractor shall allow for the decontamination of all surfaces of plant and equipment. It will be the client’s responsibility on this contract to isolate the plant and equipment to ensure safe and suitable completion of these works.<br><br>The asbestos removal contractor shall allow for the decontamination of all surfaces including decontamination of all cables, cable trays, support brackets and similar.<br><br>The asbestos removal contractor shall ensure the working area is clear of visible asbestos debris at the end of each shift.<br><br><br><span style="font-weight: bold;">Re-Cleaning of the Enclosure</span><br><br>If in the opinion of the client appointed analyst the criteria detailed in this Specification has not been met and therefore leads to further decontamination/cleaning, the asbestos removal contractor shall carry out such cleaning until satisfactory standards have been met and approved.&nbsp; Any additional cost of such re-cleaning shall be borne by the asbestos removal contractor.<br><br>If after stage 3 of the 4 stage clearance procedure, the air sample results are in excess of 0.010 fibres/millilitre and the asbestos removal contractor shall re-clean the working area.&nbsp; Any subsequent extra costs incurred by the asbestos removal contractor or the client appointed analyst as a result of such necessary cleaning, shall be borne by the asbestos removal contractor.&nbsp; <br><br>The asbestos removal contractor shall continue re-cleaning until further air sample measurements indicate airborne fibre concentrations below 0.01&nbsp;fibres/millilitre in 80% of the tests and below 0.015 fibres/millilitre for the remaining 20% of the tests.<br><br><span style="font-weight: bold;">Respiratory Protective Equipment (RPE)</span><br><br>The asbestos removal contractor shall provide RPE for all operatives engaged with asbestos in accordance with Asbestos: The Licensed contractors guide (HSG 247). It is the asbestos removal contractor’s responsibility to ensure that all RPE is adequately maintained, cleaned and that records are kept for all equipment.<br><br>A current face fit certificate must be provided for each specific type of respirator used by individual operatives.&nbsp; Operatives should be clean shaven at all times.<br><br><span style="font-weight: bold;">Protective Clothing/Equipment</span><br><br>The asbestos removal contractor shall provide protective clothing for all persons who are liable to contamination. Coveralls shall be a minimum standard of Type 5 Cat 6.<br><br>The asbestos removal contractor shall allow for the provision of any necessary construction site conditions equipment e.g. hard hat, boots, goggles, high-vis jackets, gloves etc.<br><br><span style="font-weight: bold;">Transit Procedures</span><br><br>The asbestos removal contractor shall satisfy the contract administrator that they have initiated a safe procedure for transiting between the airlocks and the decontamination unit, which does not endanger the health, safety and welfare of:<br><br>His own personnel, Occupants and others users and visitors to the building (this to include the protection of all carpeted areas)<br><br>Clear distinction must be possible between protective clothing used for the purpose of asbestos abatement and transiting, this through the use of different coloured overalls.<br><br>In addition to the asbestos removal contractor’s own inspections, tests, both visual and analytical will carried out on the transit route by the client appointed analyst to make sure it has not become contaminated with asbestos fibres.&nbsp; If this is found to be the case then the asbestos removal contractor shall clean this area at the asbestos removal contractor’s own expense.<br><br><span style="font-weight: bold;">Hygiene Facility</span><br><br>The asbestos removal contractor shall provide on-site, a hygiene unit for the use of all persons who must enter the works or transit zone and/or are engaged in asbestos disturbance works.&nbsp; The hygiene facilities shall be of a design in accordance with Asbestos: The licensed contractors guide (HSG 247).<br><br>The hygiene facilities shall be maintained on site throughout the whole works and be of the appropriate size to provide the necessary showers, washing and storage facilities to meet the requirements of the works.<br><br>The position of the hygiene unit is to be agreed and confirmed prior to the submission of the asbestos removal contractor tender.&nbsp; All suitable services shall be ascertained, provided and connected by the asbestos removal contractor at his own expense, unless agreed with the contract administrator prior to works.<br><br>The hygiene facility shall have hot and cold water supplies and filtered waste water outlets connected to a suitable point of drainage.<br><br>The hygiene facility shall be locked at all times when not in use.<br><br>The hygiene waste filter shall be replaced as necessary and the used filter disposed of as asbestos contaminated waste.<br><br>The hygiene facility shall be kept clean on a daily basis and air tested weekly whilst on site.&nbsp; The hygiene facility shall not be allowed back on site without a clearance certificate from the previous site.<br><br><span style="font-weight: bold;">Asbestos techniques/Dust Suppression</span><br><br>The asbestos removal contractor shall include for all necessary dust suppressant equipment/techniques so that asbestos fibre levels are kept to a minimum.&nbsp; Fibre release within work areas should be reduced to the lowest practical level.<br><br>The asbestos removal contractor shall include for any necessary suppression of asbestos using injection, spraying, wrap and cut, shadow vacuuming and glove bag methods, where required and in strict accordance with Asbestos: The Licensed Contractors Guide (HSG 247).&nbsp; Dry stripping will not be permitted under any circumstances.&nbsp; The use of PVA or any other sealants shall not be applied until authorised by the client appointed analyst.<br><br><span style="font-weight: bold;">Asbestos Waste Removal, Storage, Transporting and Disposal</span><br><br>The asbestos removal contractor shall allow for the removal, storage, transportation and disposal of all asbestos material removed as part of this contract.&nbsp; <br><br>All bagged, sealed asbestos waste shall be thoroughly vacuumed and cleaned, placed in a second bag, sealed again with appropriate industry tape and clearly marked “Asbestos Waste”.&nbsp; The first bag shall be red, the second shall be clear and both bags must be UN-approved standard.<br><br>The bags shall then be transferred to a suitable lockable skip/container or vehicle for disposal.&nbsp; It is the asbestos removal contractor’s responsibility to ensure the waste storage facility has suitable locks.<br><br>The waste storage facility shall be located as near to the working area as far as reasonably practical.&nbsp; The asbestos removal contractor shall consider any sensitive areas or personnel on site, when planning or carrying out the movement of the waste material from the working area to the storage facility.<br><br>Bagged waste shall be carefully transferred by vehicles provided by the asbestos removal contractor or the nominated sub-contractor for transport to an authorised waste tipping site.&nbsp; <br><br>The transfer of asbestos waste from site to point of disposal should be in accordance with the consignment note procedure laid down in the ‘Hazardous Waste (England and Wales) Regulations 2005’, completed copies of these waste consignment notes shall be presented to the contract administrator on completion of the works.<br><br>The asbestos removal contractor shall provide a copy of the ‘waste’ carrier’s registration certificate prior to any waste being removed from site.&nbsp; All asbestos shall be disposed of at a landfill site licensed to receive asbestos waste and the asbestos removal contractor shall include for all charges connected therewith in his tender for transport to the site.<br><br>The asbestos removal contractor shall provide a copy of a valid certificate of Registration for the carrier of waste and the landfill site waste disposal license prior to any waste being removed from site.<br><br>The asbestos removal contractor shall be responsible for ensuring that the waste consignment Notes and disposal certificates are completed.&nbsp; No final account payment or interim shall be passed without receipt of the completed waste consignment notes. <br><br>All relevant paperwork, including waste consignment notes and tip receipt notes shall be forwarded to Derisk within two weeks of completion of the removal works.&nbsp; Failure to provide this documentation may lead to delay of payment.<br><br><span style="font-weight: bold;">Site Services/Requirements</span><br><br>The asbestos removal contractor is to supply all necessary temporary lighting for safe execution of the works, including access and 4 stage clearance testing.&nbsp; This lighting shall remain on site until all works are completed with all costs borne by the asbestos removal contractor for its provision.<br><br>The asbestos removal contractor shall allow for protecting all existing services within the building, which may be affected during the work or set up.<br><br><span style="font-weight: bold;">Provision of site welfare<br></span><br>The asbestos removal contractor shall ensure that all welfare and safety measures required under or by virtue of the provisions of any enactment or regulations and amendments or the working rules of any industry are strictly complied with.&nbsp; No claim for extras will be admitted for any error or omission arising from the asbestos removal contractor’s failure to satisfy themselves on these matters. <br><br>The asbestos removal contractor shall include for the provision of all necessary welfare facilities, unless specifically instructed otherwise by the contract administrator during the tender process.&nbsp; Extra costs for these items shall not be permitted.<br><br><br><br><span style="font-weight: bold;">Working Hours</span><br><br>The works are required to be continuous and will incorporate both weekday and weekend working. Access to site will be arranged by the client. The standard shift will be 0800 – 1700.<br><br>The Contractor will have to make due allowance for restrictions for noisy works during removal or demolition works.<br>';
}

$generalRequirements = str_replace("\n","<br/>",$generalRequirements);
$generalRequirements = str_replace("<br/>","<br>",$generalRequirements);
$generalRequirements = str_replace('"',"'",$generalRequirements);

$tenderSubmission = $removal['tender_submission'];

if (empty($tenderSubmission) and empty($removal['id'])) {
    $tenderSubmission = 'As part of the tender submission and quotation for these works you are to return a signed copy of this document supported with the following information in an electronic format:<br><br><ul><li>Copy of Company’s asbestos license</li><li>Copy of Company’s insurance schedule</li><li>Company’s organisation and proposed project organisation </li><li>Copies of any trade body or other recognised professional memberships</li><li>Program of works</li></ul><br>The tender will be reviewed and the company’s may then be asked to attend a post tender review meeting, at this meeting you will be required to explain your methodology and table any tender clarifications.<br><br>The tender submission will be evaluated utilising the following scoring criterion:<br><br><span style="font-weight: bold;">Health and Safety &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 50%<br>Commercial &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 30%<br>Quality of submission&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 20%</span><br><br>The successful contractor will be notified via telephone and email with instruction to prepare and submit the necessary notifications to HSE, on completion of the notification a copy of the ASB5 and accompanying plan of work will be sent to the contract administrator for review and authorisation.';
}

$tenderSubmission = str_replace("\n","<br/>",$tenderSubmission);
$tenderSubmission = str_replace("<br/>","<br>",$tenderSubmission);
$tenderSubmission = str_replace('"',"'",$tenderSubmission);

?>
        
            jQuery("#preliminaries").Editor();
            jQuery("#preliminaries").Editor("setText","<?php echo $prelims ?>");
            
            jQuery("#general_requirements").Editor();
            jQuery("#general_requirements").Editor("setText","<?php echo $generalRequirements ?>");
            
            jQuery("#tender_submission").Editor();
            jQuery("#tender_submission").Editor("setText","<?php echo $tenderSubmission ?>");
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
                            <label class="control-label" for="general_requirements"><b>Asbestos Removal Contractor - General Requirements</b></label>
                            <textarea class="txtEditor" id="general_requirements" name="general_requirements" title="Asbestos Removal Contractor - General Requirements" placeholder="Asbestos Removal Contractor - General Requirements"><?php echo $removal['general_requirements'] ?></textarea>
                        </div>   
                        <div class="form-group">
                            <label class="control-label" for="tender_submission"><b>Analysis of Tender Submission</b></label>
                            <textarea class="txtEditor" id="tender_submission" name="tender_submission" title="Analysis of Tender Submission" placeholder="Analysis of Tender Submission"><?php echo $removal['tender_submission'] ?></textarea>
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