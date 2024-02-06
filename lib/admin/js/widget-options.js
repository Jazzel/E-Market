/**
 * Widget Options
 */

(function($){
	$(document).on('change', '.advanced-opt .toggle > input', function(e){
		e.preventDefault();
		if ( $(this).is(':checked') ){
			$(this).parents('.advanced-opt').addClass('on');
		} else {
			$(this).parents('.advanced-opt').removeClass('on');
		}
	});
	
	$(document).on('change', '.advance-menu .edit-menu-item-which_user', function(e){
		e.preventDefault();
		var parent = $(this).parents('.menu-config-content');
		if ($(this).val() == 'logined') {
			$('.field-user_role', parent).css('display','block');
		} else {
			$('.field-user_role', parent).css('display','none');
		}
	});
	
	$(document).on('change', '.advance-menu .edit-menu-item-advanced', function(e){
		//e.preventDefault();
		var parent = $(this).parents('.menu-config-content');
		if ($(this).val() == 'no') {
			$('.field-advanced_content', parent).css('display','none');
			$('.field-page_select', parent).css('display','none');
		}
		else if ($(this).val() == 'apc') {
			$('.field-advanced_content', parent).css('display','block');
			$('.field-page_select', parent).css('display','none');
		} else {
			$('.field-page_select', parent).css('display','block');
			$('.field-advanced_content', parent).css('display','none');
		}
	});
	
	$(document).ready(function(){
		$( '.advance-menu .edit-menu-item-which_user' ).each(function(){
			var parent = $(this).parents('.menu-config-content');
			if ($(this).val() == 'logined') {
				$('.field-user_role', parent).css('display','block');
			} else {
				$('.field-user_role', parent).css('display','none');
			}
		});
		
		$( '.advance-menu .edit-menu-item-advanced' ).each(function(){
			var parent = $(this).parents('.menu-config-content');
			if ($(this).val() == 'no') {
				$('.field-advanced_content', parent).css('display','none');
				$('.field-page_select', parent).css('display','none');
			}
			else if ($(this).val() == 'apc') {
				$('.field-advanced_content', parent).css('display','block');
				$('.field-page_select', parent).css('display','none');
			} else {
				$('.field-page_select', parent).css('display','block');
				$('.field-advanced_content', parent).css('display','none');
			}
		});
	});	
	
})(jQuery);