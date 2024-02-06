;(function($) {
	"use strict";  
	
	//* Form js
	function verificationForm(){
		//jQuery time
		var current_fs, next_fs, previous_fs; //fieldsets
		var left, opacity, scale; //fieldset properties which we will animate
		var animating; //flag to prevent quick multi-click glitches
		
		$(document).on( 'click', ".grid-homepages > li", function () { 			
			$(this).addClass( 'active' );
			$(this).siblings().removeClass( 'active' );
		});
		var ajaxurl = install_theme.ajax_url;
		var url = install_theme.current_url;
		$(document).on( 'click', ".next", function () { 			
			var check = false;
			var current_fs = $(this).parent();
			var next_fs = $(this).parent().next();
			var $this = $(this);				
			if( $(this).hasClass( 'purchase' ) && !$(this).hasClass( 'checked' ) ){				
				var purchase = $(this).parent().find( '#sw_purchase_code' ).val();	
				current_fs.addClass( 'loading' );
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: ajaxurl,
					headers: { 'api-key':Math.random() },
					data: {
						action: 'verify_purchase_code',
						purchase: purchase
					},
					success: function(data){
						current_fs.removeClass( 'loading' ); 
						if( data.check == 1 ){							
							$this.addClass( 'checked' );
							$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
							next_fs.addClass( 'active' );
							current_fs.removeClass( 'active' );								
							$this.removeClass( 'purchase' );
							window.history.pushState("string", "Title", url + '&step=1' );
							// location.reload();
						}else{
							alert( data.message );
						}
					}
				});
			}
			
			if( $(this).hasClass( 'layout' ) ){
				var layout = $(this).parent().find( '.active' ).data( 'layout' );
				if( layout == undefined ){
					alert( install_theme.ajax_url );
				}else{
					current_fs.addClass( 'loading' );
					$.ajax({
						type: 'POST',
						dataType: 'json',
						url: ajaxurl,
						headers: { 'api-key':Math.random() },
						data: {
							action: 'choose_layout_import',
							layout: layout
						},
						success: function(data){
							current_fs.removeClass( 'loading' );
							if( data.check == 1 ){
								$this.addClass( 'checked' );
								$this.removeClass( 'layout' );
								// $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
								// next_fs.addClass( 'active' );
								// current_fs.hide();		
								window.history.pushState("string", "Title", url + '&step=2' );								
								window.location.reload();
							}
						}
					});
				}
			}			
			
			if( $(this).hasClass( 'import' ) ){				
				var layout_active = $(this).data( 'active' );
				var target_layout = next_fs.find( '.ocdi__gl-item' );
				$( target_layout ).each( function(){
					if( $(this).data( 'title' ) == layout_active ){
						$(this).addClass( 'active' );
					}
				});
				window.history.pushState("string", "Title", url + '&step=3' );
			}
			
			if( $(this).hasClass( 'finish' ) ){				
				window.history.pushState("string", "Title", install_theme.admin_url );
				location.reload();
			}
			
			if( $(this).hasClass( 'checked' ) ){
				//activate next step on progressbar using the index of next_fs
				$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
				//show the next fieldset
				next_fs.addClass( 'active' );
				//hide the current fieldset with style
				current_fs.removeClass( 'active' );			
			}
		});

		$(".previous").click(function () { 

			current_fs = $(this).parent();
			previous_fs = $(this).parent().prev();
			
			if( $(this).hasClass( 'purchase' ) ){
				window.history.pushState("string", "Title", url );
			}
			if( $(this).hasClass( 'layout' ) ){
				previous_fs.find( '.next' ).addClass( 'layout' );
				window.history.pushState("string", "Title", url + '&step=1' );
			}
			if( $(this).hasClass( 'import' ) ){
				window.history.pushState("string", "Title", url + '&step=2' );
			}

			//de-activate current step on progressbar
			$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

			//show the previous fieldset
			previous_fs.addClass( 'active' );
			//hide the current fieldset with style
			current_fs.removeClass( 'active' );	
			
		});

		$(".submit").click(function () {
			return false;
		})
	}; 	
	verificationForm ();
})(jQuery); 