function submitNewUserForm()
{
    var validator = jQuery('#add-user').data('bootstrapValidator');
    validator.validate();
    
    if (validator.isValid()) {
        var name = jQuery('#name').val();
        var surname = jQuery('#surname').val();
        var email = jQuery('#email').val();
        var password = jQuery('#password').val();
        var confpassword = jQuery('#password2').val();
        var usertype = jQuery('#usertype').val();
        var qualification = jQuery('#qualification').val();
        var assessor = (jQuery('#fassessor').is(':checked') ? 1 : 0);
        var reviewer = (jQuery('#reviewer').is(':checked') ? 1 : 0);
        var udisabled = (jQuery('#udisabled').is(':checked') ? 1 : 0);

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/saveUser',
            data: { 
                'name': name,
                'surname': surname,
                'email': email,
                'password': password,
                'confpassword': confpassword,
                'usertype': usertype,
                'qualification': qualification,
                'assessor': assessor,
                'reviewer': reviewer,
                'udisabled': udisabled
            },
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                jQuery('#message').text('User saved successfully!');
                jQuery('#confirmation-message').show();
            }
        });
    }
}

function changePassword()
{
    var validator = jQuery('#change-password').data('bootstrapValidator');
    validator.validate();
    
    if (validator.isValid()) {
        var email = jQuery('#email').val();
        var password = jQuery('#password').val();
        var confpassword = jQuery('#password2').val();

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/savePassword',
            data: { 
                'email': email,
                'password': password,
                'confpassword': confpassword
            },
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                jQuery('#message').text('Password changed successfully!');
                jQuery('#confirmation-message').show();
                jQuery('#password').val();
                jQuery('#password2').val();
            }
        });
    }
}

function submitNewClientForm()
{
    var validator = jQuery('#add-client').data('bootstrapValidator');
    validator.validate();
    
    if (validator.isValid()) {
        var name = jQuery('#name').val();
        var derisk_number = jQuery('#derisk_number').val();
        var companyname = jQuery('#companyname').val();
        var contact = jQuery('#contact').val();
        var address1 = jQuery('#address1').val();
        var address2 = jQuery('#address2').val();
        var city = jQuery('#city').val();
        var postcode = jQuery('#postcode').val();

        var input_phones = jQuery('.phones');

        var phone_vals = new Array();

        for (var i = 0; i < input_phones.length; i++) {
            phone_vals[i] = jQuery(input_phones[i]).val();
        }

        var phones = phone_vals.join(';');

        var input_emails = jQuery('.emails');

        var email_vals = new Array();

        for (var i = 0; i < input_emails.length; i++) {
            email_vals[i] = jQuery(input_emails[i]).val();
        }

        var emails = email_vals.join(';');

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/saveClient',
            data: { 
                'name': name,
                'derisk_number': derisk_number,
                'companyname': companyname,
                'contact': contact,
                'address1': address1,
                'address2': address2,
                'city': city,
                'postcode': postcode,
                'phones': phones,
                'emails': emails
            },
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                jQuery('#message').text('Client saved successfully!');
                jQuery('#confirmation-message').show();
            }
        });
    }
}

function submitNewSurveyForm()
{
    var validator = jQuery('#add-survey').data('bootstrapValidator');
    validator.validate();
    
    if (validator.isValid()) {
        var dataToPass = { };
        
        dataToPass.jobnumber = jQuery('#job_number').val();
        dataToPass.reinspectionof = jQuery('#reinspectionof').val();
        dataToPass.ukas_number = jQuery('#ukas_number').val();
        dataToPass.surveytype_id = jQuery('#surveytype_id').val();
        dataToPass.client_id = jQuery('#client_id').val();
        dataToPass.surveydate = jQuery('#surveydate').val();
        dataToPass.surveyors = jQuery('#surveyors').val();
        dataToPass.agreed_excluded_areas = jQuery('#agreed_excluded_areas').val();
        dataToPass.deviations_from_standard_methods = jQuery('#deviations_from_standard_methods').val();
        dataToPass.siteaddress = jQuery('#siteaddress').val();
        dataToPass.sitedescription = jQuery('#sitedescription').val();
        dataToPass.scope = jQuery('#scope').val();
        dataToPass.lab_id = jQuery('#lab_id').val();
        dataToPass.issued_to = jQuery('#issued_to').val();
        dataToPass.urgency = jQuery('#urgency').val();
        
        dataToPass.othersdates = [];
        var k = 0;
        
        jQuery.each(jQuery('input.othersdates'),function(index, value) {
            var elemid = jQuery(this).attr('id');
            
            if (elemid !== "other0") {
                dataToPass.othersdates[k] = invertDate(jQuery(this).val());
        
                k++;
            }
        });

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/saveJob',
            data: dataToPass,
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                jQuery('#message').text('Survey saved successfully!');
                jQuery('#confirmation-message').show();
            }
        });
    }
}

function exportSurveyInfo()
{
    var jobnumber = jQuery('#job_number').val();
    var surveytype_id = jQuery('#surveytype_id').val();
    var client_id = jQuery('#client_id').val();
    var siteaddress = jQuery('#siteaddress').val();
    var sitedescription = jQuery('#sitedescription').val();
    var lab = jQuery('#lab_id').val();
    var urgency = jQuery('#urgency').val();

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

    jQuery.ajax({
        type: "POST",
        url: '/exportJobInfo',
        data: { 
            'job_number': jobnumber,
            'surveytype_id': surveytype_id,
            'client_id': client_id,
            'siteaddress': siteaddress,
            'sitedescription': sitedescription,
            'lab_id': lab,
            'urgency': urgency
        },
        async: false,
        dataType: "html",
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            jQuery('#message').html('CSV exported successfully! See <a href="/csv/' + data + '" target="_blank">here</a>');
            jQuery('#confirmation-message').show();
        }
    });
}

var loadFile = function(event,id,inspection_number) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('picture_container_' + id);
        output.innerHTML = '<img src="' + reader.result + '" alt="' + event.target.files[0].name + '" title="' + inspection_number + '" id="photo_' + id + '" class="inspections_picture" />';

        if ('new' == inspection_number) {
            var floor = id.replace('new_','');
            var ukas = document.getElementById('ukas_number_' + floor).value;
            var insp = document.getElementById('inspectionNumber_' + id).value;
            document.getElementById('picture_' + id).value = '/tablet/' + ukas + '/pictures/' + ukas + '_' + insp.replace('-','') + '.jpg';
        }
    };
    
    reader.readAsDataURL(event.target.files[0]);
};

function updateInspections(floor)
{
    var curid = '';
    
    jQuery.each(jQuery('tr.insp-floor-' + floor),function(index, value) {
        curid = '';
        
        if (jQuery(this).attr('id') !== undefined) {
            curid = jQuery(this).attr('id').replace("insp_","");
        }
        
        if (!curid.includes("new-")) {
            updateInspection(curid,floor,'0');
        }
    });
    
    jQuery('#message').text('Inspections saved successfully!');
    jQuery('#confirmation-message').show();
}

function updateInspection(inspection_id,floor,printMessage)
{
    if (inspection_id.includes("new-")) {
        return;
    }
    
    var validator = jQuery('#add-inspection-' + floor).data('bootstrapValidator');
    validator.validate();
    
    if (validator.isValid()) {
        var inspection_number = jQuery('#inspectionNumber_' + inspection_id).val();
        var referenced = jQuery('#referenced_' + inspection_id).val();
        var ukas = jQuery('#ukas_number_' + floor).val();
        var photo = jQuery('#photo_' + inspection_id).attr('alt');
        if ((photo === undefined) || (photo.indexOf('.') === -1)) {
            if (jQuery('#photo_' + inspection_id).attr('src') !== undefined) {
                photo = jQuery('#photo_' + inspection_id).attr('src').replace('/tablet/' + ukas + '/pictures/','');
            } else {
                photo = '';
            }
        }

        var building = jQuery('#building_' + inspection_id).val();
        var room = jQuery('#room_' + inspection_id).val();
        var room_name = jQuery('#room_name_' + inspection_id).val();
        var product = jQuery('#product_' + inspection_id).val();
        var quantity = jQuery('#quantity_' + inspection_id).val();
        var extent = jQuery('#extent_' + inspection_id).val();
        var treatment = jQuery('#treatment_' + inspection_id).val();
        var accessible = jQuery('#accessible_' + inspection_id).val();
        var accessibility = jQuery('#accessibility_' + inspection_id).val();
        var presumed = jQuery('#presumed_' + inspection_id).val();
        var results = jQuery('#results_' + inspection_id).val();
        var comments = jQuery('#comments_' + inspection_id).val();
        var material_location = jQuery('#material_location_' + inspection_id).val();
        var recommendations = jQuery('#recommendations_' + inspection_id).val();
        var recommendationsNotes = jQuery('#recommendationsNotes_' + inspection_id).val();
        var job_number = jQuery('#job_number_' + floor).val();

        var file_data = '';
        
        if (jQuery("#upload_" + inspection_id).prop("files") !== undefined) {
            file_data = jQuery("#upload_" + inspection_id).prop("files")[0];
        }; 

        var form_data = new FormData();    
        form_data.append("inspection_id",inspection_id);
        form_data.append("picture", file_data);
        form_data.append("inspection_number", inspection_number);
        form_data.append("referenced", referenced);
        form_data.append("photo", photo);
        form_data.append("building", building);
        form_data.append("floor", floor);
        form_data.append("room_id", room);
        form_data.append("room_name", room_name);
        form_data.append("product_id", product);
        form_data.append("quantity", quantity);
        form_data.append("extent_of_damage", extent);
        form_data.append("treatment", treatment);
        form_data.append("accessible", accessible);
        form_data.append("accessibility", accessibility);
        form_data.append("presumed", presumed);
        form_data.append("results", results);
        form_data.append("comments", comments);
        form_data.append("material_location", material_location);
        form_data.append("recommendations", recommendations);
        form_data.append("recommendationsNotes", recommendationsNotes);
        form_data.append("job_number", job_number);

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/saveInspection',
            data: form_data,
            async: false,
            dataType: "html",
            processData: false,
            contentType: false,
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (printMessage === '1') {
                    jQuery('#message').text('Inspection saved successfully!');
                    jQuery('#confirmation-message').show();
                }
            }
        });
    }
}

function saveNewInspection(floor)
{
    var validator = jQuery('#add-inspection-' + floor).data('bootstrapValidator');
    validator.validate();
    
    if (validator.isValid()) {
        var inspection_number = jQuery('#inspectionNumber_new_' + floor).val();
        var referenced = jQuery('#referenced_new_' + floor).val();
        var ukas = jQuery('#ukas_number_' + floor).val();
        var photo = jQuery('#photo_new_' + floor).attr('alt');
        if ((photo === undefined) || (photo.indexOf('.') === -1)) {
            if (jQuery('#photo_new_' + floor).attr('src') !== undefined) {
                photo = jQuery('#photo_new_' + floor).attr('src').replace('/tablet/' + ukas + '/pictures/','');
            } else {
                photo = '';
            }
        }

        var inspection_id = 'new';
        var building = jQuery('#building_new_' + floor).val();
        var room = jQuery('#room_new_' + floor).val();
        var room_name = jQuery('#room_name_new_' + floor).val();
        var product = jQuery('#product_new_' + floor).val();
        var quantity = jQuery('#quantity_new_' + floor).val();
        var extent = 1 + parseInt(jQuery('#extent_new_' + floor).val());
        var treatment = jQuery('#treatment_new_' + floor).val();
        var accessible = jQuery('#accessible_new_' + floor).val();
        var accessibility = jQuery('#accessibility_new_' + floor).val();
        var presumed = jQuery('#presumed_new_' + floor).val();
        var results = jQuery('#results_new_' + floor).val();
        var comments = jQuery('#comments_new_' + floor).val();
        var material_location = jQuery('#material_location_new_' + floor).val();
        var recommendations = jQuery('#recommendations_new_' + floor).val();
        var recommendationsNotes = jQuery('#recommendationsNotes_new_' + floor).val();
        var job_number = jQuery('#job_number_' + floor).val();

        var file_data = jQuery("#upload_new_" + floor).prop("files")[0]; 

        var form_data = new FormData();    
        
        form_data.append("picture", file_data);
        form_data.append("inspection_id", inspection_id);
        form_data.append("inspection_number", inspection_number);
        form_data.append("referenced", referenced);
        form_data.append("photo", photo);
        form_data.append("building", building);
        form_data.append("floor", floor);
        form_data.append("room_id", room);
        form_data.append("room_name", room_name);
        form_data.append("product_id", product);
        form_data.append("quantity", quantity);
        form_data.append("extent_of_damage", extent);
        form_data.append("treatment", treatment);
        form_data.append("accessible", accessible);
        form_data.append("accessibility", accessibility);
        form_data.append("presumed", presumed);
        form_data.append("results", results);
        form_data.append("comments", comments);
        form_data.append("material_location", material_location);
        form_data.append("recommendations", recommendations);
        form_data.append("recommendationsNotes", recommendationsNotes);
        form_data.append("job_number", job_number);

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/saveInspection',
            data: form_data,
            async: false,
            dataType: "html",
            processData: false,
            contentType: false,
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                location.reload(true);
            }
        });
    }
}

function deleteInspection(inspection_id,floor)
{
    jQuery(function() {
        jQuery("#dialog-confirm-" + floor ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Yes": function() {
                    jQuery(this).dialog("close");

                    jQuery.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    jQuery.ajax({
                        type: "POST",
                        url: '/deleteInspection',
                        data: { 
                            'id': inspection_id
                        },
                        async: false,
                        dataType: "html",
                        error: function (jqXHR,textStatus,errorThrown) {
                        },
                        success: function (data, textStatus, jqXHR) {                        
                            location.reload(true);
                        }
                    });
                },
                "No": function() {
                    jQuery(this).dialog("close");
                }
            }
        });    
    });
}

function importCSV()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var file_data = jQuery("#csv-inspections").prop("files")[0];   
    var form_data = new FormData();                  
    form_data.append("filename", file_data);
    form_data.append("jobnumber", jQuery('#job_number').val());
    
    jQuery.ajax({
        type: 'post',
        dataType: 'script',
        url: '/importCSV',
        data: form_data,
        processData: false,
        contentType: false,
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            parent.jQuery.fancybox.close();
            parent.location.reload(true);
        }
    });
}

function exportInspections(floor)
{
    var jobnumber = jQuery('#ukas_number_' + floor).val();

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

    jQuery.ajax({
        type: "POST",
        url: '/exportJobInspections',
        data: { 
            'job_number': jobnumber,
            'floor': floor
        },
        async: false,
        dataType: "html",
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            jQuery('#message').html('CSV exported successfully! See <a href="/csv/' + data + '" target="_blank">here</a>');
            jQuery('#confirmation-message').show();
        }
    });
}

function saveFile()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var file_data = jQuery("#upload_file").prop("files")[0];   
    var comments = jQuery('#new-file-comments').val();
    
    var form_data = new FormData();                  
    form_data.append("filename", file_data);
    form_data.append("comments", comments);
    form_data.append("jobnumber", jQuery('#job_number_file').val());
    
    jQuery.ajax({
        type: 'post',
        dataType: 'json',
        url: '/uploadJobNumberFile',
        data: form_data,
        processData: false,
        contentType: false,
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            window.location.reload(true);
        }
    });
}

function saveReport()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var report_data = jQuery("#upload_report").prop("files")[0];   
    var comments = jQuery('#new-report-comments').val();
    
    var form_data = new FormData();                  
    form_data.append("reportname", report_data);
    form_data.append("comments", comments);
    form_data.append("jobnumber", jQuery('#job_number_report').val());
    
    jQuery.ajax({
        type: 'post',
        dataType: 'json',
        url: '/uploadJobNumberReport',
        data: form_data,
        processData: false,
        contentType: false,
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {     
            window.location.reload(true);
        }
    });
}

function importPreparedBy()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var projref = jQuery('#project_ref').val();  
    
    if (projref != "") {
        var file_data = jQuery("#prepared-file").prop("files")[0];   
        var form_data = new FormData();                  
        form_data.append("filename", file_data);
        form_data.append("project_ref",projref);

        jQuery.ajax({
            type: 'post',
            dataType: 'script',
            url: '/importRemovalPicture',
            data: form_data,
            processData: false,
            contentType: false,
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                var url = '/removals' + data.replace(/"/g,'');

                jQuery('#prepared_by_signature').val(url.replace('/removals/','/'));
                jQuery('#prepright').remove();
                jQuery('#prepparent').append('<div id="prepright" class="imgright"></div>');
                adaptImage(url,'#prepright',198,198);
            }
        });
    }
}

function cleanPreparedBy()
{
    jQuery('#prepared_by_signature').val("");
    jQuery('#prepared-file').val("");
    jQuery('#prepright').html("");
}

function importApprovedBy()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var projref = jQuery('#project_ref').val();
    
    if (projref != "") {    
        var file_data = jQuery("#approved-file").prop("files")[0];   
        var form_data = new FormData();                  
        form_data.append("filename", file_data);
        form_data.append("project_ref",projref);

        jQuery.ajax({
            type: 'post',
            dataType: 'script',
            url: '/importRemovalPicture',
            data: form_data,
            processData: false,
            contentType: false,
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                var url = '/removals' + data.replace(/"/g,'');

                jQuery('#approved_by_signature').val(url.replace('/removals/','/'));
                jQuery('#approvedright').remove();
                jQuery('#approvedparent').append('<div id="approvedright" class="imgright"></div>');
                adaptImage(url,'#approvedright',198,198);
            }
        });
    }
}

function cleanApprovedBy()
{
    jQuery('#approved_by_signature').val("");
    jQuery('#approved-file').val("");
    jQuery('#approvedright').html("");
}

function importSitePicture()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var projref = jQuery('#project_ref').val();
    
    if (projref != "") {    
        var file_data = jQuery("#site-file").prop("files")[0];   
        var form_data = new FormData();                  
        form_data.append("filename", file_data);
        form_data.append("project_ref",projref);

        jQuery.ajax({
            type: 'post',
            dataType: 'script',
            url: '/importRemovalPicture',
            data: form_data,
            processData: false,
            contentType: false,
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                var url = '/removals' + data.replace(/"/g,'');

                jQuery('#site_picture').val(url.replace('/removals/','/'));
                jQuery('#siteright').remove();
                jQuery('#siteparent').append('<div id="siteright" class="imgright"></div>');
                adaptImage(url,'#siteright',198,198);
            }
        });
    }
}

function cleanSitePicture()
{
    jQuery('#site_picture').val("");
    jQuery('#site-file').val("");
    jQuery('#siteright').html("");
}

function importMapPicture()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var projref = jQuery('#project_ref').val();
    
    if (projref != "") {    
        var file_data = jQuery("#map-file").prop("files")[0];   
        var form_data = new FormData();                  
        form_data.append("filename", file_data);
        form_data.append("project_ref",projref);

        jQuery.ajax({
            type: 'post',
            dataType: 'script',
            url: '/importRemovalPicture',
            data: form_data,
            processData: false,
            contentType: false,
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                var url = '/removals' + data.replace(/"/g,'');

                jQuery('#map_picture').val(url.replace('/removals/','/'));
                jQuery('#mapright').remove();
                jQuery('#mapparent').append('<div id="mapright" class="imgright"></div>');
                adaptImage(url,'#mapright',198,198);
            }
        });
    }
}

function cleanMapPicture()
{
    jQuery('#map_picture').val("");
    jQuery('#map-file').val("");
    jQuery('#mapright').html("");
}

function importFloorPlansFile()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var projref = jQuery('#project_ref').val();
    
    if (projref != "") {    
        var file_data = jQuery("#floor-file").prop("files")[0];   
        var form_data = new FormData();                  
        form_data.append("filename", file_data);
        form_data.append("project_ref",projref);

        jQuery.ajax({
            type: 'post',
            dataType: 'script',
            url: '/importRemovalPicture',
            data: form_data,
            processData: false,
            contentType: false,
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                var url = '/removals' + data.replace(/"/g,'');
                
                jQuery('#floor_plans').val(url.replace('/removals/','/'));
                jQuery('#floorright').html('<span class="file-loaded">Loaded</span>');
            }
        });
    }
}

function cleanFloorPlansFile()
{
    jQuery('#floor_plans').val("");
    jQuery('#floor-file').val("");
    jQuery('#floorright').html('<span class="file-not-loaded">Not Loaded</span>');
}

function importRoutesAccessFile()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var projref = jQuery('#project_ref').val();
    
    if (projref != "") {    
        var file_data = jQuery("#access-file").prop("files")[0];   
        var form_data = new FormData();                  
        form_data.append("filename", file_data);
        form_data.append("project_ref",projref);

        jQuery.ajax({
            type: 'post',
            dataType: 'script',
            url: '/importRemovalPicture',
            data: form_data,
            processData: false,
            contentType: false,
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                var url = '/removals' + data.replace(/"/g,'');
                
                jQuery('#access_routes').val(url.replace('/removals/','/'));                
                jQuery('#accessright').html('<span class="file-loaded">Loaded</span>');
            }
        });
    }
}

function cleanRoutesAccessFile()
{
    jQuery('#access_routes').val("");
    jQuery('#access-file').val("");
    jQuery('#accessright').html('<span class="file-not-loaded">Not Loaded</span>');
}

function importBulkAnalysisFile()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var projref = jQuery('#project_ref').val();
    
    if (projref != "") {    
        var file_data = jQuery("#bulk-file").prop("files")[0];   
        var form_data = new FormData();                  
        form_data.append("filename", file_data);
        form_data.append("project_ref",projref);

        jQuery.ajax({
            type: 'post',
            dataType: 'script',
            url: '/importRemovalPicture',
            data: form_data,
            processData: false,
            contentType: false,
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                var url = '/removals' + data.replace(/"/g,'');
                
                jQuery('#bulk_analysis_certificate').val(url.replace('/removals/','/')); 
                jQuery('#bulkright').html('<span class="file-loaded">Loaded</span>');
            }
        });
    }
}

function cleanBulkAnalysisFile()
{
    jQuery('#bulk_analysis_certificate').val("");
    jQuery('#bulk-file').val("");
    jQuery('#bulkright').html('<span class="file-not-loaded">Not Loaded</span>');
}

function submitNewRemovalForm()
{
    var prelimsText = jQuery('#preliminaries').parent('.form-group').find('.Editor-editor').html();
    jQuery('#preliminaries').val(prelimsText);
    
    var generalReqs = jQuery('#general_requirements').parent('.form-group').find('.Editor-editor').html();
    jQuery('#general_requirements').val(generalReqs);
    
    var tenderSubmission = jQuery('#tender_submission').parent('.form-group').find('.Editor-editor').html();
    jQuery('#tender_submission').val(tenderSubmission);
    
    var validator = jQuery('#add-removal').data('bootstrapValidator');
    validator.validate();
    
    if (validator.isValid()) {
        var removal_id = jQuery('#id').val();
        var surveys = jQuery('#surveys').val();
        var area = jQuery('#area').val();
        var address = jQuery('#address').val();
        var prepared_for = jQuery('#prepared_for').val();
        var project_ref = jQuery('#project_ref').val();
        var prepared_by = jQuery('#prepared_by').val();
        var prepared_by_signature = jQuery('#prepared_by_signature').val();
        var preparation_date = jQuery('#preparation_date').val();
        var approved_by = jQuery('#approved_by').val();
        var approved_by_signature = jQuery('#approved_by_signature').val();
        var approval_date = jQuery('#approval_date').val();
        var preliminaries = jQuery('#preliminaries').val();
        var site_picture = jQuery('#site_picture').val();
        var map_picture = jQuery('#map_picture').val();
        var floor_plans = jQuery('#floor_plans').val();
        var access_routes = jQuery('#access_routes').val();
        var bulk_analysis_certificate = jQuery('#bulk_analysis_certificate').val();
        var general_requirements = jQuery('#general_requirements').val();
        var tender_submission = jQuery('#tender_submission').val();
        var new_revision = (jQuery('#new_revision').is(':checked') ? 'yes' : 'no');
        var revision_comments = jQuery('#revision_comments').val();
        
        var dataToPass = { };

        dataToPass.id = removal_id;
        dataToPass.surveys = surveys;
        dataToPass.area = area;
        dataToPass.address = address;
        dataToPass.prepared_for = prepared_for;
        dataToPass.project_ref = project_ref;
        dataToPass.prepared_by = prepared_by;
        dataToPass.prepared_by_signature = prepared_by_signature;
        dataToPass.preparation_date = preparation_date;
        dataToPass.approved_by = approved_by;
        dataToPass.approved_by_signature = approved_by_signature;
        dataToPass.approval_date = approval_date;
        dataToPass.preliminaries = preliminaries;
        dataToPass.site_picture = site_picture;
        dataToPass.map_picture = map_picture;
        dataToPass.floor_plans = floor_plans;
        dataToPass.access_routes = access_routes;
        dataToPass.bulk_analysis_certificate = bulk_analysis_certificate;
        dataToPass.general_requirements = general_requirements;
        dataToPass.tender_submission = tender_submission;
        dataToPass.revision_comments = revision_comments;
        dataToPass.new_revision = new_revision;

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/saveSpec',
            data: dataToPass,
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (removal_id != "") {
                    jQuery('#message').text('Removal saved successfully!');
                    jQuery('#confirmation-message').show();
                } else {
                    window.location.href = '/specs/' + data;
                }
            }
        });
    }
}

function saveAreaTitle(area_id)
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var dataToPass = { };

    dataToPass.area_id = area_id;
    dataToPass.name = jQuery('#remarea_' + area_id + '_name').val();

    jQuery.ajax({
        type: "POST",
        url: '/saveRemovalAreaTitle',
        data: dataToPass,
        async: false,
        dataType: "html",
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            jQuery('#saving_message_' + area_id).text('Saved!');
            jQuery('#saving_message_' + area_id).show();
            jQuery('#mytab-' + area_id).html('<b>' + dataToPass.name + '</b>');
        }
    });
}

function saveAreaText(area_id)
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var areaText = jQuery('#remarea_' + area_id + '_text').parent('.form-group').find('.Editor-editor').html();
    jQuery('#remarea_' + area_id + '_text').val(areaText);
    
    var dataToPass = { };

    dataToPass.area_id = area_id;
    dataToPass.text = jQuery('#remarea_' + area_id + '_text').val();

    jQuery.ajax({
        type: "POST",
        url: '/saveRemovalAreaText',
        data: dataToPass,
        async: false,
        dataType: "html",
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            jQuery('#saving_message_' + area_id + '_text').text('Saved!');
            jQuery('#saving_message_' + area_id + '_text').show();
            jQuery('#saving_message_' + area_id + '_text').css('display','block');
        }
    });
}

function saveRemovalInspection(removal_inspection_id)
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var dataToPass = { };

    dataToPass.removal_inspection_id = removal_inspection_id;
    dataToPass.room = jQuery('#room_' + removal_inspection_id).val();
    dataToPass.extent = jQuery('#quantity_' + removal_inspection_id).val();
    dataToPass.product = jQuery('#product_' + removal_inspection_id).val();
    dataToPass.surface_treatment = jQuery('#surface_treatment_' + removal_inspection_id).val();
    dataToPass.result = jQuery('#result_' + removal_inspection_id).val();
    dataToPass.damage = jQuery('#damage_' + removal_inspection_id).val();
    dataToPass.comment = jQuery('#comments_' + removal_inspection_id).val();
    dataToPass.recommendation = jQuery('#recommendations_' + removal_inspection_id).val();
    
    jQuery.ajax({
        type: "POST",
        url: '/saveRemovalInspection',
        data: dataToPass,
        async: false,
        dataType: "html",
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            jQuery('#saving_inspection_message_' + removal_inspection_id).text('Saved!');
            jQuery('#saving_inspection_message_' + removal_inspection_id).show();
            jQuery('#saving_inspection_message_' + removal_inspection_id).css('display','block');
        }
    });
}

function printSurveyReport(job_number)
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

    jQuery.ajax({
        type: "POST",
        url: '/printReport',
        data: { 
            'job_number': job_number
        },
        async: false,
        dataType: "html",
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            jQuery('#message').html('Report printed successfully! See <a href="/reports/' + data + '" target="_blank">here</a>');
            jQuery('#confirmation-message').show();
        }
    });
}

function printRemovalPdf(removal_id)
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

    jQuery.ajax({
        type: "POST",
        url: '/printRemovalPdf',
        data: { 
            'removal_id': removal_id
        },
        async: false,
        dataType: "html",
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            jQuery('#message').html('Report printed successfully! See <a href="/reports/' + data + '" target="_blank">here</a>');
            jQuery('#confirmation-message').show();
        }
    });
}

function deleteSurveyReportRevision(issue_id)
{
    jQuery(function() {
        jQuery("#dialog-confirm" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Yes": function() {
                    jQuery(this).dialog("close");

                    jQuery.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    jQuery.ajax({
                        type: "POST",
                        url: '/deleteSurveyReportRevision',
                        data: { 
                            'id': issue_id
                        },
                        async: false,
                        dataType: "html",
                        error: function (jqXHR,textStatus,errorThrown) {
                        },
                        success: function (data, textStatus, jqXHR) {                        
                            location.reload(true);
                        }
                    });
                },
                "No": function() {
                    jQuery(this).dialog("close");
                }
            }
        });    
    });
}

function submitNewReportIssueForm()
{
    var date_completed = jQuery('#date_completed').val();
    var date_checked = jQuery('#date_checked').val();
    var date_issued = jQuery('#date_issued').val();
    var date_authorised = jQuery('#date_authorised').val();
    var quality_check = jQuery('#quality_check').val();
    var revision = jQuery('#revision').val();
    var survey_id = jQuery('#survey_id').val();

    var input_authors = jQuery('.authors');

    var author_vals = new Array();

    for (var i = 0; i < input_authors.length; i++) {
        author_vals[i] = jQuery(input_authors[i]).val();
    }

    var authors = author_vals.join('|');

    var input_surveyors = jQuery('.surveyors');

    var surveyor_vals = new Array();

    for (var i = 0; i < input_surveyors.length; i++) {
        surveyor_vals[i] = jQuery(input_surveyors[i]).val();
    }

    var surveyors = surveyor_vals.join('|');
    
    var input_issuedtos = jQuery('.issuedtos');

    var issuedto_vals = new Array();

    for (var i = 0; i < input_issuedtos.length; i++) {
        issuedto_vals[i] = jQuery(input_issuedtos[i]).val();
    }

    var issuedtos = issuedto_vals.join('|');

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

    jQuery.ajax({
        type: "POST",
        url: '/saveSurveyReportRevision',
        data: { 
            'survey_id': survey_id,
            'revision': revision,
            'date_completed': date_completed,
            'date_checked': date_checked,
            'date_authorised': date_authorised,
            'date_issued': date_issued,
            'quality_check': quality_check,
            'authors': authors,
            'surveyors': surveyors,
            'issued_tos': issuedtos
        },
        async: false,
        dataType: "html",
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            jQuery('#message').text('Issue saved successfully!');
            jQuery('#confirmation-message').show();
        }
    });
}

var printCalled = 0;

jQuery(document).ready(function() {    
    jQuery('#responsiveTableAsbestos').DataTable({
        responsive: true,
        'iDisplayLength': 5,
        'searching': true,
        'paging': true,
        'pageLength': 20,
        'bLengthChange': false,
        "fnDrawCallback": function () {
            jQuery('.new_entity').fancybox({
                maxWidth	: 1024,
                maxHeight	: 768,
                fitToView	: false,
                width		: '1024',
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none',
                afterClose      : function () {
                    parent.jQuery.fancybox.close();
                    parent.location.reload(true);
                }
            });
            
            jQuery('.see_inspections').fancybox({
                maxWidth	: 1600,
                maxHeight	: 900,
                fitToView	: false,
                width		: '1600',
                height          : '900',
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none',
                afterClose      : function () {
                    parent.jQuery.fancybox.close();
                    parent.location.reload(true);
                }
            });
            
            jQuery('.print').on('click',function() {
                if (printCalled === 0) {
                    printCalled = 1;
                    
                    var job_number = jQuery(this).attr('rel');
                    
                    printSurveyReport(job_number);
                }
            });            
        }
    });
    
    jQuery('#responsiveTableFile').DataTable({
        'responsive': true,
        'lengthChange': false,
        'iDisplayLength': 15
    });
    
    jQuery('#responsiveTableReport').DataTable({
        'responsive': true,
        'lengthChange': false,
        'iDisplayLength': 15,
        'ordering': false
    });
        
    jQuery('#issuesTable').DataTable({
        'responsive': true,
        'lengthChange': false,
        'iDisplayLength': 15
    });
    
    jQuery('#responsiveTableRemovals').DataTable({
        responsive: true,
        'iDisplayLength': 5,
        'searching': true,
        'paging': true,
        'pageLength': 20,
        'bLengthChange': false
    });
    
    jQuery('.new_entity').fancybox({
        maxWidth	: 1024,
        maxHeight	: 768,
        fitToView	: false,
        width		: '1024',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none',
        afterClose      : function () {
            parent.jQuery.fancybox.close();
            parent.location.reload(true);
        }
    });
    
    jQuery('.see_inspections').fancybox({
        maxWidth	: 1600,
        maxHeight	: 900,
        fitToView	: false,
        width		: '1600',
        height          : '900',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none',
        afterClose      : function () {
            parent.jQuery.fancybox.close();
            parent.location.reload(true);
        }
    });
    
    jQuery(function() {
        setTimeout(function() {
            jQuery("#confirmation-message").hide('blind', {}, 500)
        }, 50000);
        
        setTimeout(function() {
            jQuery(".saving_message").hide('blind', {}, 500)
        }, 10000);
        
        setTimeout(function() {
            jQuery(".saving_message_text").hide('blind', {}, 500)
        }, 10000);
        
        setTimeout(function() {
            jQuery(".saving_inspection_message").hide('blind', {}, 500)
        }, 10000);
    });
    
    jQuery('#add-user').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The name is required'
                    }
                }
            },
            surname: {
                validators: {
                    notEmpty: {
                        message: 'The surname is required'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email is required, it will be the username'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    }
                }
            }
        }
    });
    
    jQuery('#change-password').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    }
                }
            },
            password2: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    }
                }
            }
        }
    });
    
    jQuery('#add-client').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The name is required'
                    }
                }
            },
            companyname: {
                validators: {
                    notEmpty: {
                        message: 'The company name is required'
                    }
                }
            }
        }
    });
    
    jQuery('#add-survey').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            ukas_number: {
                validators: {
                    notEmpty: {
                        message: 'The UKAS number is required'
                    }
                }
            },
            client_id: {
                validators: {
                    notEmpty: {
                        message: 'The client is required'
                    }
                }
            },
            surveydate: {
                validators: {
                    notEmpty: {
                        message: 'The survey date is required'
                    }
                }
            },
            surveytype_id: {
                validators: {
                    notEmpty: {
                        message: 'The survey type is required'
                    }
                }
            },
            issued_to: {
                validators: {
                    notEmpty: {
                        message: 'You have to notify who the survey is issued to'
                    }
                }
            }
        }
    });
    
    jQuery('#add-removal').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            survey_id: {
                validators: {
                    notEmpty: {
                        message: 'You have to choose the Survey!'
                    }
                }
            },
            area: {
                validators: {
                    notEmpty: {
                        message: 'The area is required for the first page of the report'
                    }
                }
            },
            prepared_for: {
                validators: {
                    notEmpty: {
                        message: 'You have to specify who the report is for'
                    }
                }
            },
            project_ref: {
                validators: {
                    notEmpty: {
                        message: 'The Project Ref is required for the first page of the report'
                    }
                }
            },
            prepared_by: {
                validators: {
                    notEmpty: {
                        message: 'Please, add the name of the person that prepared the report'
                    }
                }
            },
            preparation_date: {
                validators: {
                    notEmpty: {
                        message: 'Please, set the date when the report has been prepared'
                    }
                }
            },
            approved_by: {
                validators: {
                    notEmpty: {
                        message: 'Please, add the name of the person that approved the report'
                    }
                }
            },
            tender_submission: {
                validators: {
                    notEmpty: {
                        message: 'The tender submission is required'
                    }
                }
            }
        }
    });
    
    jQuery('.delete-user').on('click',function() {
        var email = jQuery(this).attr('rel');
    
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/deleteUser',
            data: { 
                'email': email
            },
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                location.reload(true);
            }
        });
    });
    
    jQuery('.delete-client').on('click',function() {
        var name = jQuery(this).attr('rel');
    
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/deleteClient',
            data: { 
                'name': name
            },
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                location.reload(true);
            }
        });
    });
    
    jQuery('.phones_add').on('click',function() {
        var phone = jQuery('#phone0').val();
        
        if (phone != '') {
            var children = jQuery('#phones_set').children();
            
            var new_id = 1;
            
            if (children.length > 0) {
                var last = jQuery(children).last();
                new_id = 1 + parseInt(jQuery(last).attr('id').replace('phone',''));
            }
            
            var new_div = '<div id="div_phone' + new_id + '" class="phone_line row gutter"><input type="text" class="form-control phones" id="phone' + new_id + '" name="phones[]" value="' + phone + '" /><button type="button" rel="phone' + new_id + '" class="btn btn-link phones_del"><i class="icon-circle-with-minus"></i>Delete</button></div>';
            
            jQuery('#phones_set').append(new_div);
            jQuery('#phone0').val('');
        }
    });
    
    jQuery('.print').on('click',function() {
        if (printCalled === 0) {
            printCalled = 1;

            var job_number = jQuery(this).attr('rel');
                    
            printSurveyReport(job_number);
        }
    });
    
    jQuery(document).on('click','.phones_del',function() {
        jQuery('#div_' + jQuery(this).attr('rel')).remove();
    });
    
    jQuery('.emails_add').on('click',function() {
        var email = jQuery('#email0').val();
        
        if (email != '') {
            var children = jQuery('#emails_set').children();
            
            var new_id = 1;
            
            if (children.length > 0) {
                var last = jQuery(children).last();
                new_id = 1 + parseInt(jQuery(last).attr('id').replace('email',''));
            }
            
            var new_div = '<div id="div_email' + new_id + '" class="email_line row gutter"><input type="text" class="form-control emails" id="email' + new_id + '" name="emails[]" value="' + email + '" /><button type="button" rel="email' + new_id + '" class="btn btn-link emails_del"><i class="icon-circle-with-minus"></i>Delete</button></div>';
            
            jQuery('#emails_set').append(new_div);
            jQuery('#email0').val('');
        }
    });
    
    jQuery(document).on('click','.emails_del',function() {
        jQuery('#div_' + jQuery(this).attr('rel')).remove();
    });
    
    jQuery('.otherdate_add').on('click',function() {
        var otherdate = jQuery('#other0').val();
        
        if (otherdate != '') {
            var children = jQuery('#othersdates_set').children();
            
            var new_id = 1;
            
            if (children.length > 0) {
                var last = jQuery(children).last();
                new_id = 1 + parseInt(jQuery(last).attr('id').replace('otherdate',''));
            }
            
            var new_div = '<div id="div_otherdate' + new_id + '" class="otherdate_line row gutter"><input type="text" class="form-control othersdates othersadded" id="otherdate' + new_id + '" name="othersdates[]" value="' + otherdate + '" /><button type="button" rel="otherdate' + new_id + '" class="btn btn-link othersdates_del"><i class="icon-circle-with-minus"></i>Delete</button></div>';
            
            jQuery('#othersdates_set').append(new_div);
            jQuery('#other0').val('');
            
            jQuery('input#otherdate' + new_id).datepicker({
                onSelect: function(selectedDate) {

                }
            });
        }
    });
    
    jQuery(document).on('click','.othersdates_del',function() {
        jQuery('#div_' + jQuery(this).attr('rel')).remove();
    });
    
    jQuery.datepicker.setDefaults({
        showOn: "button",
        buttonImage: "/js/jquery-ui/images/calendar.png",
        buttonText: "Choose the date",
        buttonImageOnly: true,
        dateFormat: "dd/mm/yy"                    
    });

    jQuery('input#surveydate').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });
    
    jQuery('input.othersdates').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });
    
    jQuery('input#preparation_date').datepicker({
        onSelect: function(selectedDate) {
            var validator = jQuery('#add-removal').data('bootstrapValidator');
            validator.validate();
        }
    });
    
    jQuery('input#approval_date').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });
    
    jQuery('input#date_completed').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });
    
    jQuery('input#date_checked').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });
    
    jQuery('input#date_authorised').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });
    
    jQuery('input#date_issued').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });

    jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "en-GB" ] );
    
    jQuery('.remove-file').on('click',function() {
        var fileClickedElement = jQuery(this);
        
        var fileid = fileClickedElement.attr('rel');
    
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/removeFile',
            data: { 
                'id': fileid
            },
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                var fileTable = jQuery('#responsiveTableFile').DataTable();
                
                fileTable.row(fileClickedElement.parents('tr')).remove().draw();
            }
        }); 
    });
    
    jQuery('.remove-report').on('click',function() {
        var reportClickedElement = jQuery(this);

        var reportid = reportClickedElement.attr('rel');

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/removeReport',
            data: { 
                'id': reportid
            },
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                var reportTable = jQuery('#responsiveTableReport').DataTable();

                reportTable.row(reportClickedElement.parents('tr')).remove().draw();
            }
        });
    });
    
    jQuery('#surveys').on('change',function() {
        var chosen = jQuery(this).val();
        
        var addresses = new Array();
        
        var k = 0;
        
        if (chosen.length > 0) {
            for (var n = 0; n < chosen.length; n++) {  
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });

                jQuery.ajax({
                    type: "POST",
                    url: '/getAddress',
                    data: { 
                        'survey_id': chosen[n]
                    },
                    async: false,
                    dataType: "html",
                    error: function (jqXHR,textStatus,errorThrown) {
                    },
                    success: function (data, textStatus, jqXHR) {                    
                        addresses[k] = data;
                        k++;
                    }
                });
            };
        }
        
        jQuery('#address').val(addresses.join("\n"));
    });
    
    jQuery(document).on('click','.authors_del',function() {
        jQuery('#div_' + jQuery(this).attr('rel')).remove();
    });
    
    jQuery('.authors_add').on('click',function() {
        var author = jQuery('#author0').val();
        
        if (author != '') {
            var children = jQuery('#authors_set').children();
            
            var new_id = 1;
            
            if (children.length > 0) {
                var last = jQuery(children).last();
                new_id = 1 + parseInt(jQuery(last).attr('id').replace('author',''));
            }
            
            var new_div = '<div id="div_author' + new_id + '" class="author_line row gutter"><input type="text" class="form-control authors" id="author' + new_id + '" name="authors[]" value="' + author + '" /><button type="button" rel="author' + new_id + '" class="btn btn-link authors_del"><i class="icon-circle-with-minus"></i>Delete</button></div>';
            
            jQuery('#authors_set').append(new_div);
            jQuery('#author0').val('');
        }
    });
    
    jQuery(document).on('click','.surveyors_del',function() {
        jQuery('#div_' + jQuery(this).attr('rel')).remove();
    });
    
    jQuery('.surveyors_add').on('click',function() {
        var surveyor = jQuery('#surveyor0').val();
        
        if (surveyor != '') {
            var children = jQuery('#surveyors_set').children();
            
            var new_id = 1;
            
            if (children.length > 0) {
                var last = jQuery(children).last();
                new_id = 1 + parseInt(jQuery(last).attr('id').replace('surveyor',''));
            }
            
            var new_div = '<div id="div_surveyor' + new_id + '" class="surveyor_line row gutter"><input type="text" class="form-control surveyors" id="surveyor' + new_id + '" name="surveyors[]" value="' + surveyor + '" /><button type="button" rel="surveyor' + new_id + '" class="btn btn-link surveyors_del"><i class="icon-circle-with-minus"></i>Delete</button></div>';
            
            jQuery('#surveyors_set').append(new_div);
            jQuery('#surveyor0').val('');
        }
    });
    
    jQuery(document).on('click','.issuedtos_del',function() {
        jQuery('#div_' + jQuery(this).attr('rel')).remove();
    });
    
    jQuery('.issuedtos_add').on('click',function() {
        var issued_to = jQuery('#issuedto0').val();
        
        if (issued_to != '') {
            var children = jQuery('#issuedtos_set').children();
            
            var new_id = 1;
            
            if (children.length > 0) {
                var last = jQuery(children).last();
                new_id = 1 + parseInt(jQuery(last).attr('id').replace('issuedto',''));
            }
            
            var new_div = '<div id="div_issuedto' + new_id + '" class="issuedto_line row gutter"><input type="text" class="form-control issuedtos" id="issuedto' + new_id + '" name="issuedtos[]" value="' + issued_to + '" /><button type="button" rel="issuedto' + new_id + '" class="btn btn-link issuedtos_del"><i class="icon-circle-with-minus"></i>Delete</button></div>';
            
            jQuery('#issuedtos_set').append(new_div);
            jQuery('#issuedto0').val('');
        }
    });
    
    jQuery(function() {
        jQuery("#tabfloors").tabs({
            beforeLoad: function( event, ui ) {
                ui.jqXHR.fail(function() {
                    ui.panel.html(
                        "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                        "If this wouldn't be a demo." 
                    );
                });
            }
        });
    });
    
    jQuery('.floorref').on('click',function() {
        var curone = jQuery(this);
        
        jQuery('.floorref').parent('li').removeClass('active');
        
        curone.parent('li').addClass('active');
    });
});
