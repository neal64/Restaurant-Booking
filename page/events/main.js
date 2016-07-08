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