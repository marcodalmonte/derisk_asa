function importSignature()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var shopid = jQuery('#shop_id').val();  
    
    var file_data = jQuery("#signature-file").prop("files")[0];   
    var form_data = new FormData();                  
    form_data.append("filename", file_data);
    form_data.append("shop_id",shopid);
    
    jQuery.ajax({
        type: 'post',
        dataType: 'script',
        url: '/importSignature',
        data: form_data,
        processData: false,
        contentType: false,
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            var url = '/fra' + data.replace(/"/g,'');
            
            jQuery('#signature').val(url.replace('/fra/','/'));
            jQuery('#signright').remove();
            jQuery('#signparent').append('<div id="signright" class="imgright"></div>');
            adaptImage(url,'#signright',198,198);
        }
    });
}

function cleanSignature()
{
    jQuery('#signature').val("");
    jQuery('#signature-file').val("");
    jQuery('#signright').html("");
}

function importReviewSignature()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var shopid = jQuery('#shop_id').val(); 
    
    var file_data = jQuery("#review-signature-file").prop("files")[0];   
    var form_data = new FormData();                  
    form_data.append("filename", file_data);
    form_data.append("shop_id",shopid);
    
    jQuery.ajax({
        type: 'post',
        dataType: 'script',
        url: '/importReviewSignature',
        data: form_data,
        processData: false,
        contentType: false,
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            var url = '/fra' + data.replace(/"/g,'');
            
            jQuery('#review_signature').val(url.replace('/fra/','/'));
            jQuery('#review-signright').remove();
            jQuery('#review-signparent').append('<div id="review-signright" class="imgright"></div>');
            adaptImage(url,'#review-signright',198,198);
        }
    });
}

function cleanReviewSignature()
{
    jQuery('#review_signature').val("");
    jQuery('#review-signature-file').val("");
    jQuery('#review-signright').html("");
}

function importMainPicture()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var shopid = jQuery('#shop_id').val();
    
    var file_data = jQuery("#mainpicture-file").prop("files")[0];   
    var form_data = new FormData();                  
    form_data.append("filename", file_data);
    form_data.append("shop_id",shopid);
    
    jQuery.ajax({
        'type': 'post',
        'dataType': 'script',
        'url': '/importMainPicture',
        'data': form_data,
        'processData': false,
        'contentType': false,
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            var url = '/fra' + data.replace(/"/g,'');           
            
            jQuery('#main_picture').val(url.replace('/fra/','/'));
            jQuery('#mpright').remove();
            jQuery('#mpparent').append('<div id="mpright" class="imgright"></div>');
            adaptImage(url,'#mpright',198,198);
        }
    });
}

function cleanMainPicture()
{
    jQuery('#main_picture').val("");
    jQuery('#mainpicture-file').val("");
    jQuery('#mpright').html("");
}

function importPicture(picture_id)
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var shopid = jQuery('#shop_id').val();
    
    var file_data = jQuery("#picture-file-" + picture_id).prop("files")[0];
    var form_data = new FormData();                  
    form_data.append("filename", file_data);
    form_data.append("shop_id",shopid);
    
    jQuery.ajax({
        'type': 'post',
        'dataType': 'script',
        'url': '/importPicture',
        'data': form_data,
        'processData': false,
        'contentType': false,
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            var url = '/fra' + data.replace(/"/g,'');       
            
            jQuery('#picture-' + picture_id).val(url.replace('/fra/','/'));
            jQuery('#pictureright_' + picture_id).remove();
            jQuery('#pictureparent_' + picture_id).append('<div id="pictureright_' + picture_id + '" class="imgright"></div>');
            adaptImage(url,'#pictureright_' + picture_id,198,198);
        }
    });
}

function cleanPicture(picture_id)
{
    jQuery('#picture-' + picture_id).val("");
    jQuery('#picture-file-' + picture_id).val("");
    jQuery('#pictureright_' + picture_id).html("");
}

function submitNewShopForm()
{
    var validator = jQuery('#add-shop').data('bootstrapValidator');
    validator.validate();
    
    if (validator.isValid()) {
        var client_id = jQuery('#client_id').val();
        var name = jQuery('#name').val();
        var address1 = jQuery('#address1').val();
        var address2 = jQuery('#address2').val();
        var town = jQuery('#town').val();
        var postcode = jQuery('#postcode').val();
        var code = jQuery('#code').val();

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/saveShop',
            data: {
                'client_id': client_id,
                'name': name,
                'address1': address1,
                'address2': address2,
                'town': town,
                'postcode': postcode,
                'code': code
            },
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                jQuery('#message').text('Site Location saved successfully!');
                jQuery('#confirmation-message').show();
            }
        });
    }
}

function submitNewFraForm() 
{
    var validator = jQuery('#add-fra').data('bootstrapValidator');
    validator.validate();
    
    if (validator.isValid()) {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        // Hidden fields
        var selshop = jQuery('#shop_id').val();
        var new_revision = (jQuery('#new_revision').is(':checked') ? 1 : 0);
        var completed = (jQuery('#completed').is(':checked') ? 1 : 0);
        var country_context = jQuery('#country_context').val();
        var user_email = jQuery('#user_email').val();

        // Others fields for the report
        var signature = jQuery('#signature').val();
        var risk_level_rate = jQuery('#risk_level_rate').val();
        var main_picture = jQuery('#main_picture').val();
        var responsible_person = jQuery('#responsible_person').val();
        var assessor = jQuery('#assessor').val();
        var person_to_meet = jQuery('#person_to_meet').val();
        var use_of_building = jQuery('#use_of_building').val();
        var number_of_floors = jQuery('#number_of_floors').val();
        var construction_type = jQuery('#construction_type').val();
        var max_number_occupants = jQuery('#max_number_occupants').val();
        var number_employees = jQuery('#number_employees').val();
        var disabled_occupants = jQuery('#disabled_occupants').val();
        var remote_occupants = jQuery('#remote_occupants').val();
        var hours_operation = jQuery('#hours_operation').val();
        var next_date_recommended = jQuery('#next_date_recommended').val();

        var executive_summary = jQuery('#executive_summary').val();
        var fire_loss_experience = jQuery('#fire_loss_experience').val();
        var relevant_fire_safety_legislation = jQuery('#relevant_fire_safety_legislation').val();
        var revision = jQuery('#revision').val();
        var revision_comments = jQuery('#revision_comments').val();  

        var hazard_from_fire = jQuery('#hazard_from_fire').val();
        var life_safety = jQuery('#life_safety').val();
        var general_fire_risk = jQuery('#general_fire_risk').val();

        var survey_date = jQuery('#survey_date').val();
        var review_date = jQuery('#review_date').val();
        var review_by = jQuery('#review_by').val();
        var review_signature = jQuery('#review_signature').val();

        // Fields for the answers
        var curid = "";
        var curname = "";
        var answer = "";
        var comment = "";
        var recommendation = "";
        var picture = "";
        var priority_code = "0";
        var action_by_whom = "";
        var actioned_by = "";
        var date_of_completion = "";
        var info = 0;

        var dataToPass = { };

        dataToPass.shop_id = selshop;
        dataToPass.new_revision = new_revision;
        dataToPass.completed = completed;
        dataToPass.country_context = country_context;
        dataToPass.user_email = user_email;

        dataToPass.signature = signature;
        dataToPass.risk_level_rate = risk_level_rate;
        dataToPass.main_picture = main_picture;
        dataToPass.responsible_person = responsible_person;
        dataToPass.assessor = assessor;
        dataToPass.person_to_meet = person_to_meet;
        dataToPass.use_of_building = use_of_building;
        dataToPass.number_of_floors = number_of_floors;
        dataToPass.construction_type = construction_type;
        dataToPass.max_number_occupants = max_number_occupants;
        dataToPass.number_employees = number_employees;
        dataToPass.disabled_occupants = disabled_occupants;
        dataToPass.remote_occupants = remote_occupants;
        dataToPass.hours_operation = hours_operation;
        dataToPass.next_date_recommended = next_date_recommended;
        dataToPass.executive_summary = executive_summary;
        dataToPass.fire_loss_experience = fire_loss_experience;
        dataToPass.relevant_fire_safety_legislation = relevant_fire_safety_legislation;
        dataToPass.revision = revision;
        dataToPass.revision_comments = revision_comments;
        dataToPass.hazard_from_fire = hazard_from_fire;
        dataToPass.life_safety = life_safety;
        dataToPass.general_fire_risk = general_fire_risk;
        dataToPass.survey_date = survey_date;
        dataToPass.review_date = review_date;
        dataToPass.review_by = review_by;
        dataToPass.review_signature = review_signature;

        dataToPass.answers = [];

        jQuery.each(jQuery('tr.answers'),function(index, value) {
            curid = jQuery(this).attr('id').replace("tr_","");
            curname = jQuery(this).attr('name').replace("tr_","");

            answer = jQuery(this).find('input[name="answer_' + curname + '"]:checked').val();
            comment = jQuery(this).find('textarea[name="comments_' + curname + '"]').val();
            recommendation = jQuery(this).find('textarea[name="recommendation_' + curname + '"]').val();
            picture = jQuery(this).find('input[name="picture-' + curid + '"]').val();
            priority_code = jQuery(this).find('select[name="priority-' + curid + '"]').val();
            action_by_whom = jQuery(this).find('#action_by_whom-' + curid).val();
            actioned_by = jQuery(this).find('#actioned_by-' + curid).val();
            date_of_completion = jQuery(this).find('#date_of_completion-' + curid).val();
            if (jQuery('#answer_' + curid + '_info').is(':checked')) {
                info = 1;
            } else {
                info = 0;
            }

            dataToPass.answers[index] = [ curid, answer, comment, recommendation, picture, priority_code, action_by_whom, actioned_by, date_of_completion, info ];
        });

        jQuery.ajax({
            type: "POST",
            url: '/saveFra',
            data: dataToPass,
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                //location.reload(true);
            }
        });
    }
}

function deleteRevision(revision_id)
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
                        url: '/deleteRevision',
                        data: { 
                            'id': revision_id
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

function checkAnswer(question_id,answer)
{
    var isToCheck = 1;
    
    jQuery('#answer_' + question_id + '_yes').parent('label').removeClass('wrong-answer');
    jQuery('#answer_' + question_id + '_no').parent('label').removeClass('wrong-answer');
    jQuery('#answer_' + question_id + '_na').parent('label').removeClass('wrong-answer');
    jQuery('#answer_' + question_id + '_notknown').parent('label').removeClass('wrong-answer');
    
    if (jQuery('#answer_' + question_id + '_info').is(':checked')) {
        isToCheck = 0;
    }
    
    if (isToCheck === 1) {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type: "POST",
            url: '/checkAnswer',
            data: {
                'question_id': question_id,
                'answer': answer
            },
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if ('wrong' === data) {
                    jQuery('#answer_' + question_id + '_' + answer).parent('label').addClass('wrong-answer');
                }
            }
        });
    }
}

function printFraPdf()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

    // Hidden fields
    var selshop = jQuery('#shop_id').val();
    var revision = jQuery('#revision').val();

    jQuery.ajax({
        type: "POST",
        url: '/printFraPdf',
        data: {
            'shop_id': selshop,
            'revision': revision
        },
        async: false,
        dataType: "html",
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            jQuery('#message').html('Assessment printed successfully! See <a href="/fra' + data + '" target="_blank">here</a>');
            jQuery('#confirmation-message').show();
        }
    });
}

function addOtherPicture()
{
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Hidden fields
    var selshop = jQuery('#shop_id').val();
    var revision = jQuery('#revision').val();
    var path = jQuery('#picture-others-0').val();
    var caption = jQuery('#caption-others-0').val();
    var section = jQuery('#section-others-0').val();
    
    jQuery.ajax({
        type: "POST",
        url: '/addOtherPicture',
        data: {
            'shop_id': selshop,
            'revision': revision,
            'path': path,
            'caption': caption,
            'section': section
        },
        async: false,
        dataType: "html",
        error: function (jqXHR,textStatus,errorThrown) {
        },
        success: function (data, textStatus, jqXHR) {
            var html_to_add = '<tr id="tr_others_' + data + '" name="tr_others_' + data + '" class="pictures_tr">';
            html_to_add = html_to_add + '<td class="others-col1">';
            html_to_add = html_to_add + '<div id="pictureright_others-' + data + '" class="imgright pictques">';
            html_to_add = html_to_add + '<script type="text/javascript">';
            html_to_add = html_to_add + 'jQuery(document).ready(function() {';
            html_to_add = html_to_add + 'adaptImage("/fra' + path + '","#pictureright_others-' + data + '",198,198)';
            html_to_add = html_to_add + '});';
            html_to_add = html_to_add + '</script>';
            html_to_add = html_to_add + '</div>';
            html_to_add = html_to_add + '</td>';
            html_to_add = html_to_add + '<td class="others-col2">';
            html_to_add = html_to_add + caption;
            html_to_add = html_to_add + '</td>';
            html_to_add = html_to_add + '<td class="others-col3">';
            html_to_add = html_to_add + jQuery('#section-others-0 > option:selected').html();
            html_to_add = html_to_add + '</td>';
            html_to_add = html_to_add + '<td class="others-col4">';
            html_to_add = html_to_add + '<button type="button" id="remove-picture-others-' + data + '" name="remove-picture-others" class="btn btn-info btn-ques remove-others">Remove Picture</button>';
            html_to_add = html_to_add + '</td>';
            html_to_add = html_to_add + '</tr>';
            
            jQuery('#others').find('tbody').append(html_to_add);
            
            jQuery('#picture-file-others-0').val("");
            jQuery('#picture-others-0').val("");
            jQuery('#pictureright_others-0').html("");
            jQuery('#caption-others-0').val("");
            jQuery('#section-others-0').val("");
            
            jQuery('#remove-picture-others-' + data).on('click',function() {
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                var elem = jQuery(this);
                
                jQuery.ajax({
                    type: "POST",
                    url: '/removeOtherPicture',
                    data: {
                        'id': data
                    },
                    async: false,
                    dataType: "html",
                    error: function (jqXHR,textStatus,errorThrown) {
                    },
                    success: function (data, textStatus, jqXHR) {
                        elem.parents('tr').remove();
                    }
                });
            });
        }
    });
}

function updateGeneralRisk(hazard,life_safety)
{
    var general_risk = '1';
    var general_risk_word = 'trivial';
        
    if ((hazard == '1') && (life_safety == '1')) {
        general_risk = '1';
        general_risk_word = 'trivial';
    } else if ((hazard == '1') && (life_safety == '2')) {
        general_risk = '2';
        general_risk_word = 'tolerable';
    } else if ((hazard == '1') && (life_safety == '3')) {
        general_risk = '3';
        general_risk_word = 'moderate';
    } else if ((hazard == '2') && (life_safety == '1')) {
        general_risk = '2';
        general_risk_word = 'tolerable';
    } else if ((hazard == '2') && (life_safety == '2')) {
        general_risk = '3';
        general_risk_word = 'moderate';
    } else if ((hazard == '2') && (life_safety == '3')) {
        general_risk = '4';
        general_risk_word = 'substantial';
    } else if ((hazard == '3') && (life_safety == '1')) {
        general_risk = '3';
        general_risk_word = 'moderate';
    } else if ((hazard == '3') && (life_safety == '2')) {
        general_risk = '4';
        general_risk_word = 'substantial';
    } else if ((hazard == '3') && (life_safety == '3')) {
        general_risk = '5';
        general_risk_word = 'intolerable';
    }
    
    jQuery('#general_fire_risk').val(general_risk);
    jQuery('#risk_level_rate').val(general_risk_word);
}

function submitSettingsForm()
{
    var validator = jQuery('#add-settings').data('bootstrapValidator');
    validator.validate();
    
    if (validator.isValid()) {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        
        var sender_name = jQuery('#sender_name').val();
        var sender_email = jQuery('#sender_email').val();
        var receiver_name = jQuery('#receiver_name').val();
        var receiver_email = jQuery('#receiver_email').val();
        var email_subject = jQuery('#email_subject').val();
        var email_text = jQuery('#email_text').val();
        
        var dataToPass = { };

        dataToPass.sender_name = sender_name;
        dataToPass.sender_email = sender_email;
        dataToPass.receiver_name = receiver_name;
        dataToPass.receiver_email = receiver_email;
        dataToPass.email_subject = email_subject;
        dataToPass.email_text = email_text;
        
        jQuery.ajax({
            type: "POST",
            url: '/saveSettings',
            data: dataToPass,
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                jQuery('#message').text('Settings saved successfully!');
                jQuery('#confirmation-message').show();
            }
        });
    }
}

var law_uk = 'Regulatory Reform (Fire Safety) Order 2005';
var law_scotland = 'Fire (Scotland) Act 2005&#10;Fire Safety (Scotland) Regulations 2006';

jQuery(document).ready(function() {
    jQuery('#responsiveTableFra').DataTable({
        responsive: true,
        'iDisplayLength': 5,
        'searching': true,
        'paging': true,
        'pageLength': 10,
        'bLengthChange': false
    });
    
    jQuery('#add-shop').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            client_id: {
                validators: {
                    notEmpty: {
                        message: 'The client is required'
                    }
                }
            },
            name: {
                validators: {
                    notEmpty: {
                        message: 'The site name is required'
                    }
                }
            }
        }
    });
    
    jQuery('#add-fra').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            country_context: {
                selector: '#country_context',
                validators: {
                    notEmpty: {
                        message: 'You have to notify in which country you are working'
                    }
                }
            },
            risk_level_rate: {
                selector: '#risk_level_rate',
                validators: {
                    notEmpty: {
                        message: 'The Risk Level Rate is required'
                    }
                }
            },
            responsible_person: {
                selector: '#responsible_person',
                validators: {
                    notEmpty: {
                        message: 'The name of the responsible person is required'
                    }
                }
            },
            assessor: {
                selector: '#assessor',
                validators: {
                    notEmpty: {
                        message: 'The name of the assessor is required'
                    }
                }
            },
            person_to_meet: {
                selector: '#person_to_meet',
                validators: {
                    notEmpty: {
                        message: 'The name of the person to meet is required'
                    }
                }
            },
            use_of_building: {
                selector: '#use_of_building',
                validators: {
                    notEmpty: {
                        message: 'Please, specify the use of the building'
                    }
                }
            },
            number_of_floors: {
                selector: '#number_of_floors',
                validators: {
                    notEmpty: {
                        message: 'Please, specify the number of floors'
                    }
                }
            },
            construction_type: {
                selector: '#construction_type',
                validators: {
                    notEmpty: {
                        message: 'Please, specify the construction type'
                    }
                }
            },
            number_employees: {
                selector: '#number_employees',
                validators: {
                    notEmpty: {
                        message: 'Please, specify the number of employees'
                    }
                }
            },
            disabled_occupants: {
                selector: '#disabled_occupants',
                validators: {
                    notEmpty: {
                        message: 'Please, specify the number of disable occupants'
                    }
                }
            },
            remote_occupants: {
                selector: '#remote_occupants',
                validators: {
                    notEmpty: {
                        message: 'Please, specify the number of remote occupants'
                    }
                }
            },
            hours_operation: {
                selector: '#hours_operation',
                validators: {
                    notEmpty: {
                        message: 'Please, specify the hours of operation'
                    }
                }
            },
            survey_date: {
                selector: '#survey_date',
                validators: {
                    notEmpty: {
                        message: 'Please, record the survey date'
                    }
                }
            }
        }
    });
    
    jQuery('#add-settings').bootstrapValidator({
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            sender_name: {
                selector: '#sender_name',
                validators: {
                    notEmpty: {
                        message: 'You have to specify the name of the sender'
                    }
                }
            },
            sender_email: {
                selector: '#sender_email',
                validators: {
                    notEmpty: {
                        message: 'You have to specify the email of the sender'
                    }
                }
            },
            receiver_name: {
                selector: '#receiver_name',
                validators: {
                    notEmpty: {
                        message: 'You have to specify the name of the receiver'
                    }
                }
            },
            receiver_email: {
                selector: '#receiver_email',
                validators: {
                    notEmpty: {
                        message: 'You have to specify the email of the receiver'
                    }
                }
            },
            email_subject: {
                selector: '#email_subject',
                validators: {
                    notEmpty: {
                        message: 'You have to specify the subject of the email'
                    }
                }
            }
        }
    });
    
    jQuery('#settings-link').fancybox({
        maxWidth	: 1024,
        maxHeight	: 768,
        fitToView	: false,
        width		: '1024',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
    });
    
    jQuery('.select-priority').on('change',function() {
        var priority = jQuery(this).val();
        
        var background = '#FFFFFF';
        
        switch (priority) {
            case '1':
                background = '#FF0000';
                break;
            case '2':
                background = '#FFFF00';
                break;
            case '3':
                background = '#92D050';
                break;
            case '4':
                background = '#00B0F0';
                break;
            case '5':
                background = '#CCC0D9';
                break;
            default:
                break;
        }
        
        jQuery(this).siblings('.div-priority').css('background-color',background);
    });
    
    jQuery('input#next_date_recommended').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });
    
    jQuery('input#survey_date').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });
    
    jQuery('input#review_date').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });
    
    jQuery('input#survey_date').on('change',function() {
        var validator = jQuery('#add-fra').data('bootstrapValidator');
        validator.validate();
    });

    jQuery('input#review_date').on('change',function() {
        var validator = jQuery('#add-fra').data('bootstrapValidator');
        validator.validate();
    });

    jQuery('input#next_date_recommended').on('change',function() {
        var validator = jQuery('#add-fra').data('bootstrapValidator');
        validator.validate();
    });
    
    jQuery('input.completion').datepicker({
        onSelect: function(selectedDate) {
            
        }
    });
    
    jQuery.datepicker.setDefaults({
        showOn: "button",
        buttonImage: "/js/jquery-ui/images/calendar.png",
        buttonText: "Choose the date",
        buttonImageOnly: true,
        dateFormat: "dd/mm/yy"                    
    });

    jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "en-GB" ] );
    
    jQuery('.delete-shop').on('click', function() {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        
        var shop_id = jQuery(this).attr('rel');
        
        jQuery.ajax({
            type: "POST",
            url: '/deleteShop',
            data: { 
                'shop_id': shop_id
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
    
    jQuery('select.ready_comments').on('change',function() {
        var text_sibling = jQuery(this).siblings('textarea.ready_comments');
        var text = jQuery(this).find("option:selected").text();
        
        text_sibling.text(text);
    });
    
    jQuery('select.ready_recommendations').on('change',function() {
        var text_sibling = jQuery(this).siblings('textarea.ready_recommendations');
        var text = jQuery(this).find("option:selected").text();
        
        text_sibling.text(text);
    });
    
    jQuery('.remove-others').on('click',function() {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        
        var curid = jQuery(this).attr('id').replace('remove-picture-others-','');
        
        var elem = jQuery(this);
        
        jQuery.ajax({
            type: "POST",
            url: '/removeOtherPicture',
            data: {
                'id': curid
            },
            async: false,
            dataType: "html",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                elem.parents('tr').remove();
            }
        });
    });
    
    jQuery('#revision_time').on('change',function() {
        var sdate = jQuery('#survey_date').val();
        
        var tot_years = parseInt(jQuery(this).val());
         
        if (sdate.length > 0) {
            var pieces = sdate.split("/");
            
            var third = tot_years + parseInt(pieces[2]);
            
            jQuery('#next_date_recommended').val(pieces[0] + "/" + pieces[1] + "/" + third);
        }
    });
    
    jQuery('#country_context').on('change',function() {
        var country = jQuery(this).val();
        var law = law_uk;
        
        if (country === 'scotland') {
            law = law_scotland;
        }
        
        jQuery('#relevant_fire_safety_legislation').html(law);
    });
    
    jQuery('#hazard_from_fire').on('change', function() {
        var hazard = jQuery(this).val();
        var life_safety = jQuery('#life_safety').val();
        
        updateGeneralRisk(hazard,life_safety);
    });
    
    jQuery('#life_safety').on('change', function() {
        var hazard = jQuery('#hazard_from_fire').val();
        var life_safety = jQuery(this).val();
                
        updateGeneralRisk(hazard,life_safety);
    });
    
    jQuery('.nocolor').on('change',function() {
        var id = jQuery(this).attr('id').replace('answer_','').replace('_info','');
        
        if (jQuery('#answer_' + id + '_yes').is(':checked')) {
            checkAnswer(id,'yes');
        }
        
        if (jQuery('#answer_' + id + '_no').is(':checked')) {
            checkAnswer(id,'no');
        }
        
        if (jQuery('#answer_' + id + '_na').is(':checked')) {
            checkAnswer(id,'na');
        }
        
        if (jQuery('#answer_' + id + '_notknown').is(':checked')) {
            checkAnswer(id,'notknown');
        }
    });
});
