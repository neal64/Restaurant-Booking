/*
CUSTOM JAVASCRIPT
*/
//Copy a value from one input to another one

jQuery(function () {
    //comment
    var jQuerysrc1 = jQuery('#order_comment1'),
        jQuerydst1 = jQuery('#order_comment2');
    jQuerysrc1.keyup(function () {
        jQuerydst1.val(jQuerysrc1.val());
    });
});


jQuery(function () {
    //phone
    var jQuerysrc2 = jQuery('#order_phone1'),
        jQuerydst2 = jQuery('#order_phone2');
    jQuerysrc2.keyup(function () {
        jQuerydst2.val(jQuerysrc2.val());
    });
});

jQuery(function () {
    //phone
    var jQuerysrc3 = jQuery('#usr_email1'),
        jQuerydst3 = jQuery('#usr_email2');
    jQuerysrc3.keyup(function () {
        jQuerydst3.val(jQuerysrc3.val());
    });
});


//BOOK A TABLE PAGE




jQuery(window).load(function() { // makes sure the whole site is loaded
  jQuery('#status').fadeOut(); // will first fade out the loading animation
  jQuery('#phpRestaurantPreloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
  jQuery('body').delay(350).css({'overflow':'visible'});
});


//CUSTOM JS FOR CATERING ORDERING SYSTEM
jQuery(document).ready(function() {

  //Action catering order system form to paypal/pay on delivery
  jQuery('.select_payment_method').change(function(){
    if(jQuery(this).val() == 'via_paypal'){
      jQuery("#update-users-informations").attr("action", "paypal.php");
      jQuery(".via_paypal").fadeIn('slow');
      jQuery(".via_delivery").fadeOut('slow');
    }else if(jQuery(this).val() == 'via_delivery'){
      jQuery("#update-users-informations").attr("action", "");
      jQuery(".via_delivery").fadeIn('slow');
      jQuery(".via_paypal").fadeOut('slow');
    }
  });

  jQuery( ".catering-order-details" ).click(function() {
    // Calculeaza pretul total cand butonul este apasat
    // Muta pretul rezultat in input
    var sum = 0;
    jQuery(".cart_items_goes_up input[name='menu_item_price_per_slice[]']").each( function() {
         sum += +this.value;
    });
    jQuery('input.order_sum').val( sum );

    var minus = jQuery('input.order_sum').val();
    jQuery( ".plus-minus-qty" ).keyup(function() {
      jQuery(".plus-minus-qty").each( function() {
        var productprice =  jQuery(this).parent().parent().find(".menu_item_price_per_slice").val();
        var multiplywith =  jQuery(this).val();
        var totalprice =  jQuery(this).parent().parent().find('input.temporary_price_foreach_product').val( productprice * multiplywith );

        var sumtemporary = 0;
        jQuery('.goeshere input.temporary_price_foreach_product').each(function(){
            sumtemporary += +this.value;
            jQuery('input.order_sum').val( sumtemporary );
            //console.log(sumtemporary);
        });
      });
    });


    jQuery(".remove-product-from-cart").click( function() {
      jQuery(this).parent().remove();
      
      jQuery("#update-user-informations").hover( function() {
        jQuery(".plus-minus-qty").each( function() {
          var productprice =  jQuery(this).parent().parent().find(".menu_item_price_per_slice").val();
          var multiplywith =  jQuery(this).val();
          var totalprice =  jQuery(this).parent().parent().find('input.temporary_price_foreach_product').val( productprice * multiplywith );

          var sumtemporary = 0;
          jQuery('.goeshere input.temporary_price_foreach_product').each(function(){
              sumtemporary += +this.value;
              jQuery('input.order_sum').val( sumtemporary );
              //console.log(sumtemporary);
          });
        });
      });
    });

    // Concateneaza numele mancarurilor adaugate in cos
    // Muta lista intreaga in textarea
    var total = '';
    jQuery('.cart_items_goes_up .single_menu_item.item_name').each(function(){
        total += jQuery(this).val();
    });
    jQuery('textarea.list_of_purchased_items').html(total);
  });
});






jQuery(document).ready(function() {
  //Site main slider
jQuery(".restaurant-main-slider").owlCarousel({
  navigation      : true, // Show next and prev buttons
  pagination      : true,
  slideSpeed      : 1500,
  paginationSpeed : 1500,
  navigationText  : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
  singleItem      : true,
  autoPlay        : false
});

  //Site main slider
jQuery(".testimonials-slider").owlCarousel({
  navigation      : true, // Show next and prev buttons
  pagination      : true,
  slideSpeed      : 1500,
  paginationSpeed : 1500,
  navigationText  : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
  singleItem      : true,
  autoPlay        : false
});

  //Site homepage gallery slider block
  jQuery(".gallery-slider").owlCarousel({
    autoPlay: 2000, //Set AutoPlay to 3 seconds
    items : 4,
    itemsDesktop : [1199,3],
    itemsDesktopSmall : [979,3]
  });

  // Site homepage events slider block
  jQuery(".events-slider").owlCarousel({
    autoPlay: 8000, //Set AutoPlay to 3 seconds
    items : 3,
    itemsDesktop : [1199,3],
    itemsDesktopSmall : [979,3]
  });

/*--------------------------------------------------
MODAL JS ####################################
--------------------------------------------------*/
var ModalEffects = (function() {
  function init() {
    var overlay = document.querySelector( '.md-overlay' );
    [].slice.call( document.querySelectorAll( '.md-trigger' ) ).forEach( function( el, i ) {
      var modal = document.querySelector( '#' + el.getAttribute( 'data-modal' ) ),
        close = modal.querySelector( '.md-close' );
      function removeModal( hasPerspective ) {
        classie.remove( modal, 'md-show' );
        if( hasPerspective ) {
          classie.remove( document.documentElement, 'md-perspective' );
        }
      }
      function removeModalHandler() {
        removeModal( classie.has( el, 'md-setperspective' ) ); 
      }
      el.addEventListener( 'click', function( ev ) {
        classie.add( modal, 'md-show' );
        overlay.removeEventListener( 'click', removeModalHandler );
        overlay.addEventListener( 'click', removeModalHandler );

        if( classie.has( el, 'md-setperspective' ) ) {
          setTimeout( function() {
            classie.add( document.documentElement, 'md-perspective' );
          }, 25 );
        }
      });
      close.addEventListener( 'click', function( ev ) {
        ev.stopPropagation();
        removeModalHandler();
      });

    } );
  }
  init();
})();

});


window.onload=body_load;
function body_load() {
/*--------------------------------------------------
VALIDATION for SEND mail Property page sidebar. ####################################
--------------------------------------------------*/
jQuery(".contact_us_now").validate({
  rules: {
    contact_name:{
      required: true,
      minlength: 2,
      maxlength: 50
    },
    contact_email:{
      required: true,
      email: true,
      minlength: 2,
      maxlength: 50
    },
    contact_message:{
      required: true,
      minlength: 2,
      maxlength: 300
    }

  },
  messages: {
    contact_name:{
      required: "Acest camp este obiligatoriu.",
      minlength: "Introduceti minim 2 caractere.",
      maxlength: "Ati depasit limita de 50 de caractere admisa."
    },
    contact_email:{
      required: "Acest camp este obiligatoriu.",
      email: "Email invalid.",
      minlength: "Introduceti minim 2 caractere.",
      maxlength: "Ati depasit limita de 50 de caractere admisa."
    },
    contact_message:{
      required: "Acest camp este obiligatoriu.",
      minlength: "Introduceti minim 2 caractere.",
      maxlength: "Ati depasit limita de 300 de caractere admisa."
    }
  }
});


//TOGGLE CLASS REGISTER NEW USER ###################################################
jQuery('.contact_us_now').ajaxForm(function() {
    jQuery(".hidden-contact-message").delay(400).fadeIn( "slow" );
    jQuery(".hidden-contact-message").delay(3000).fadeOut("slow");
});




}

window.onload=body_load;
function body_load() {

 jQuery(".send_catering_order").validate({
  rules: {
    order_address:{
      required: true,
      minlength: 2,
      maxlength: 50
    },
    order_user_email:{
      required: true,
      email: true,
      minlength: 2,
      maxlength: 50
    },
    order_user_nice_name:{
      required: true,
      minlength: 2,
      maxlength: 300
    }

  },
  messages: {
    order_address:{
      required: "Acest camp este obiligatoriu.",
      minlength: "Introduceti minim 2 caractere.",
      maxlength: "Maxim 50 de caractere admisa."
    },
    order_user_email:{
      required: "Acest camp este obiligatoriu.",
      email: "Email invalid.",
      minlength: "Introduceti minim 2 caractere.",
      maxlength: "Maxim 50 de caractere admisa."
    },
    order_user_nice_name:{
      required: "Acest camp este obiligatoriu.",
      minlength: "Introduceti minim 2 caractere.",
      maxlength: "Maxim 300 de caractere admisa."
    }
  }
}); 


 // AJAX send_catering_order
  jQuery('.send_catering_order').ajaxForm(function() {
      jQuery(".hidden-contact-message").delay(400).fadeIn( "slow" );
      jQuery(".hidden-contact-message").delay(3000).fadeOut("slow");
  });
}



 // Events [From v1.2 Update]
jQuery(document).ready(function(jQuery){
  var jQuerytimeline_block = jQuery('.phpr-timeline-block');

  //hide timeline blocks which are outside the viewport
  jQuerytimeline_block.each(function(){
    if(jQuery(this).offset().top > jQuery(window).scrollTop()+jQuery(window).height()*0.75) {
      jQuery(this).find('.phpr-timeline-img, .phpr-timeline-content').addClass('is-hidden');
    }
  });

  //on scolling, show/animate timeline blocks when enter the viewport
  jQuery(window).on('scroll', function(){
    jQuerytimeline_block.each(function(){
      if( jQuery(this).offset().top <= jQuery(window).scrollTop()+jQuery(window).height()*0.75 && jQuery(this).find('.phpr-timeline-img').hasClass('is-hidden') ) {
        jQuery(this).find('.phpr-timeline-img, .phpr-timeline-content').removeClass('is-hidden').addClass('bounce-in');
      }
    });
  });
});