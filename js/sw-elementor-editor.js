(function($) {
	"use strict";
	$(window).on('elementor/frontend/init', function () {
		console.log( elementorFrontend.getEditorListeners ); 
     if( elementorFrontend.isEditMode() ) { 

     } 
	});
}(jQuery));