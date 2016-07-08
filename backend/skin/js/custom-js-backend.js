jQuery( document ).ready(function() {
  //AJAX FORM SUBMIT jQuery ##########################################################
  jQuery('#backend-register-new-menu-item').ajaxForm(function() { 
      jQuery("#sendmailsuccess").delay(400).fadeIn( "slow" );
      jQuery("#sendmailsuccess").delay(1000).fadeOut("slow");
  });
  jQuery('#update-user-informations').ajaxForm(function() { 
      jQuery(".p-update-user-informations").delay(400).fadeIn( "slow" );
      jQuery(".p-update-user-informations").delay(1000).fadeOut("slow");
  });
  jQuery('.update-menuitem-informations').ajaxForm(function() { 
      jQuery(".p-update-user-informations").delay(400).fadeIn( "slow" );
      jQuery(".p-update-user-informations").delay(1000).fadeOut("slow");
  });

  //Copy selected value to input - add new food ##########################################################
  jQuery('.copy-to-category').bind('change click keyup', function() {
    jQuery('.add-category').val(jQuery(this).val());
  });

  //Color picker
  if ( jQuery( ".color_picker" ).length ) {
    jQuery('.color_picker').colorpicker();
  }

  //date picker
  if ( jQuery( "#datetimepicker1" ).length ) {
    jQuery(function() {
      jQuery('#datetimepicker1').datetimepicker({
        language: 'pt-BR'
      });
    });
  }


  jQuery(function() {
      jQuery('#side-menu').metisMenu();
  }); 


  var setHeight = jQuery("body").height();
  jQuery(".v2-sidebar-menu .navbar-static-top").height(setHeight);


  // SHOW-HIDE MENUS and MENUS CATEGORIES
  jQuery('.table-hover:first').fadeIn();
  jQuery('.select_menu_type ').change(function(){
      var jQuerySelect = jQuery(this).val();
      jQuery('tbody.hidden-tbody').hide();
      jQuery('.'+jQuerySelect).fadeIn();
  });

  // SHOW-HIDE USERS and USERS ROLES
  jQuery('#select_user_role ').change(function(){
      var jQuerySelect = jQuery(this).val();
      jQuery('tbody.hidden-tbody').hide();
      jQuery('.'+jQuerySelect).fadeIn();
  });

  // SHOW-HIDE USERS and ORDERS
  jQuery('#select_order_type').change(function(){
    if(jQuery('#select_order_type').val() == 'catering') {
      jQuery('.catering_orders').fadeIn( 'slow' );
      jQuery('.bookatable_orders').fadeOut( 'slow' );
    } else if(jQuery('#select_order_type').val() == 'bookatable') {
      jQuery('.bookatable_orders').fadeIn( 'slow' );
      jQuery('.catering_orders').fadeOut( 'slow' );
    }
  });

  //REMOVE USERS AJAX
  jQuery('.remove_db_item').ajaxForm(function() {
  });
  jQuery('.remove-db-item-btn').click(function() { 
    jQuery(this).parent().parent().parent().hide('slow');
  });


  //TOGGLE CLASS REGISTER NEW USER ###################################################
  jQuery( ".onclick-register-form" ).click(function() {
      jQuery( ".toggle-register-form" ).toggle( "slow" );
  });


  /*--------------------------------------------------
  VALIDATION for SEND mail Property page sidebar. ####################################
  --------------------------------------------------*/
  jQuery("#backend-register-new-menu-item").validate({
    rules: {
      menu_item_name:{
        required: true,
        minlength: 2,
        maxlength: 30
      },
      menu_item_details:{
        required: true,
        minlength: 2,
        maxlength: 300
      },
      menu_item_price_per_slice:{
        required: true,
        maxlength: 10,
        digits: true
      }

    },
    messages: {
      menu_item_name:{
        required: "Item name field is required.",
        minlength: "Item name field must have at least 2 chars.",
        maxlength: "Item name field must have max. 30 chars."
      },
      menu_item_details:{
        required: "Item details field is required.",
        minlength: "Item details field must have at least 2 chars.",
        maxlength: "Item details field must have max. 300 chars."
      },
      menu_item_price_per_slice:{
        required: "Item price field is required.",
        maxlength: "Item price field must have max. 10 digits."
      }
    }
  });
});