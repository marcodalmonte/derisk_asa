jQuery(document).ready(function() {
    jQuery('#save-surveyor').on('click',function() {
        var name = jQuery('#surveyor-name').val();
        var surname = jQuery('#surveyor-surname').val();
        var email = jQuery('#surveyor-email').val();
        
        jQuery.ajax({
            type: "POST",
            url: '/save-surveyor',
            data: { 
                'name': name,
                'surname': surname,
                'email': email
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('Please, check the email address, it cannot be empty');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.delete-surveyor').on('click',function() {
        var surveyor_id = jQuery(this).attr('rel').replace('surveyor-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/delete-surveyor',
            data: { 
                'idsurveyor': surveyor_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The surveyor ID is empty, nothing to delete');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.enable-surveyor').on('click',function() {
        var surveyor_id = jQuery(this).attr('rel').replace('surveyor-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/enable-surveyor',
            data: { 
                'idsurveyor': surveyor_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The surveyor ID is empty, nothing to enable');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('#save-surveytype').on('click',function() {
        var surveytype = jQuery('#surveytype').val();
        
        jQuery.ajax({
            type: "POST",
            url: '/save-surveytype',
            data: { 
                'surveytype': surveytype
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('Please, check the name of the survey type, it cannot be empty');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.delete-surveytype').on('click',function() {
        var surveytype_id = jQuery(this).attr('rel').replace('surveytype-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/delete-surveytype',
            data: { 
                'idtype': surveytype_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The survey type ID is empty, nothing to delete');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.enable-surveytype').on('click',function() {
        var surveytype_id = jQuery(this).attr('rel').replace('surveytype-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/enable-surveytype',
            data: { 
                'idtype': surveytype_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The survey type ID is empty, nothing to enable');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('#save-lab').on('click',function() {
        var company = jQuery('#lab-company').val();
        var building = jQuery('#lab-building').val();
        var address = jQuery('#lab-building').val();
        var town = jQuery('#lab-town').val();
        var postcode = jQuery('#lab-postcode').val(); 
        
        jQuery.ajax({
            type: "POST",
            url: '/save-lab',
            data: { 
                'company': company,
                'building': building,
                'address': address,
                'town': town,
                'postcode': postcode
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('Please, check the name of the lab, it cannot be empty');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
        
    });
    
    jQuery('.delete-lab').on('click',function() {
        var lab_id = jQuery(this).attr('rel').replace('lab-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/delete-lab',
            data: { 
                'idlab': lab_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The lab ID is empty, nothing to delete');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.enable-lab').on('click',function() {
        var lab_id = jQuery(this).attr('rel').replace('lab-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/enable-lab',
            data: { 
                'idlab': lab_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The lab ID is empty, nothing to enable');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('#save-room').on('click',function() {
        var name = jQuery('#room-name').val();
        
        jQuery.ajax({
            type: "POST",
            url: '/save-room',
            data: { 
                'name': name
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('Please, check the name of the room, it cannot be empty');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.delete-room').on('click',function() {
        var room_id = jQuery(this).attr('rel').replace('room-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/delete-room',
            data: { 
                'idroom': room_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The room ID is empty, nothing to delete');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.enable-room').on('click',function() {
        var room_id = jQuery(this).attr('rel').replace('room-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/enable-room',
            data: { 
                'idroom': room_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The room ID is empty, nothing to enable');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('#save-floor').on('click',function() {
        var code = jQuery('#floor-code').val();
        var name = jQuery('#floor-name').val();
        var menu_order = jQuery('#floor-menu-order').val();
        
        jQuery.ajax({
            type: "POST",
            url: '/save-floor',
            data: { 
                'code': code,
                'name': name,
                'menu': menu_order
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('Please, check the code of the floor, it cannot be empty');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.delete-floor').on('click',function() {
        var floor_id = jQuery(this).attr('rel').replace('floor-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/delete-floor',
            data: { 
                'idfloor': floor_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The floor ID is empty, nothing to delete');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.enable-floor').on('click',function() {
        var floor_id = jQuery(this).attr('rel').replace('floor-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/enable-floor',
            data: { 
                'idfloor': floor_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The floor ID is empty, nothing to enable');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('#save-product').on('click',function() {
        var name = jQuery('#product-name').val();
        var score = jQuery('#product-score').val();
        
        jQuery.ajax({
            type: "POST",
            url: '/save-product',
            data: { 
                'name': name,
                'score': score
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('Please, check the name of the product, it cannot be empty');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.delete-product').on('click',function() {
        var product_id = jQuery(this).attr('rel').replace('product-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/delete-product',
            data: { 
                'idproduct': product_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The product ID is empty, nothing to delete');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.enable-product').on('click',function() {
        var product_id = jQuery(this).attr('rel').replace('product-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/enable-product',
            data: { 
                'idproduct': product_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The product ID is empty, nothing to enable');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('#save-extent').on('click',function() {
        var code = jQuery('#extent-code').val();
        var name = jQuery('#extent-name').val();
        var score = jQuery('#extent-score').val();
        
        jQuery.ajax({
            type: "POST",
            url: '/save-extent',
            data: { 
                'code': code,
                'name': name,
                'score': score
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('Please, check the code of the extent, it cannot be empty');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.delete-extent').on('click',function() {
        var extent_id = jQuery(this).attr('rel').replace('extent-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/delete-extent',
            data: { 
                'idextent': extent_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The extent ID is empty, nothing to delete');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.enable-extent').on('click',function() {
        var extent_id = jQuery(this).attr('rel').replace('extent-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/enable-extent',
            data: { 
                'idextent': extent_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The extent ID is empty, nothing to enable');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('#save-treatment').on('click',function() {
        var code = jQuery('#treatment-code').val();
        var description = jQuery('#treatment-description').val();
        var score = jQuery('#treatment-score').val();
        
        jQuery.ajax({
            type: "POST",
            url: '/save-surface-treatment',
            data: { 
                'code': code,
                'description': description,
                'score': score
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('Please, check the code of the surface treatment, it cannot be empty');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.delete-treatment').on('click',function() {
        var treatment_id = jQuery(this).attr('rel').replace('treatment-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/delete-surface-treatment',
            data: { 
                'idtreatment': treatment_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The surface treatment ID is empty, nothing to delete');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
    
    jQuery('.enable-treatment').on('click',function() {
        var treatment_id = jQuery(this).attr('rel').replace('treatment-','');
        
        jQuery.ajax({
            type: "POST",
            url: '/enable-surface-treatment',
            data: { 
                'idtreatment': treatment_id
            },
            async: false,
            dataType: "json",
            error: function (jqXHR,textStatus,errorThrown) {
            },
            success: function (data, textStatus, jqXHR) {
                if (data['saved'] == '0') {
                    jQuery('#message').addClass('error');
                    jQuery('#message').text('The surface treatment ID is empty, nothing to enable');
                    jQuery('#confirmation-message').show();
                    
                    return false;
                }
                
                location.reload(true);
            }
        });
    });
});
