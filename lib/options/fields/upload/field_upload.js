(function($) {
	
	var file_frame,
	formfield = '';
	preview = '';
	$( document ).on( 'click', '.sw-opts-upload, .sw-menu-upload', function( event ) {
		var formfield = $(this).attr('rel-id');
		var preview = $(this).prev('img');
		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.downloadable_file = wp.media({
			title: "Choose an image",
			button: {
				text: 'Use image'
			},
			multiple: false
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			var attachment = file_frame.state().get( 'selection' ).first().toJSON();
			$('#' + formfield).val( attachment.sizes.full.url );
			console.log( preview );
			$(preview).attr( 'src', attachment.sizes.thumbnail.url );
			$('#' + formfield).next().fadeIn('slow');
			$('#' + formfield).next().next().fadeOut('slow');
			$('#' + formfield).next().next().next().fadeIn('slow');
		});

		// Finally, open the modal.
		file_frame.open();
	});
	$(document).ready(function(){
		$( document ).on( 'click', '.sw-opts-upload-remove, .sw-menu-upload-remove', function(){
			$relid = $(this).attr('rel-id');
			$('#'+$relid).val('');
			$(this).prev().fadeIn('fast');
			$(this).prev().prev().fadeOut('fast', function(){jQuery(this).attr("src", sw_upload.url);});
			$(this).fadeOut('slow');
		});
		 
		
	});
	function MenuClick(){
		$('.menu-advance-href').on('click', function(){
			$(this).parent().find('.menu-config-content').slideToggle();
		});
	}
	$(document).ready(function(){
		MenuClick();
	});
}(jQuery));