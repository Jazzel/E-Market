(function($) {
	"use strict";
	var button = $('.pagination-ajax > button');
	var page = 2;
	var loading = false;
	var maxpage = button.data( 'maxpage' );
	var loadmore_style  = button.data( 'loadmore_style' );
	var scrollHandling = {
	    allow: true,
	    reallow: function() {
	        scrollHandling.allow = true;
	    },
	    delay: 400 /* milliseconds) adjust to the highest acceptable value */
	};
	
	function containsSpecialChars(str) {
	  const specialChars = /[?]/;
	  return specialChars.test(str);
	}
	
	function get_url( $page ){
		var current_url = window.location.href;
		var next_url = '';
		if( containsSpecialChars( current_url ) ){
			next_url = current_url + '&paged=' + $page;
		}else{
			next_url = current_url + '?paged=' + $page;
		}
		return next_url;
	}
	
	console.log( containsSpecialChars( window.location.href ) );
	
	function _product_loadmore_ajax(){
		loading = true;
		button.addClass( 'loading' );	
		var url = get_url( page );
		// $.ajax({method:"GET", url: url, success: function( data ) {
			
		// });
		$.ajax({method:"GET", url: url, success: function( data ) {
			 var $data = $('<div>'+data+'</div>');
			var target = $data.find( 'ul.products-loop' );
			$('ul.products-loop').append( target.html() );
			page = page +1;				
			loading = false;
			button.removeClass( 'loading' );
			if( maxpage < page ){
				button.addClass( 'loaded' );
			}
		}});
	}
	if( loadmore_style == 1 ){
		$(window).scroll(function(){
			if( ! loading && scrollHandling.allow ) {
				scrollHandling.allow = false;
				setTimeout(scrollHandling.reallow, scrollHandling.delay);
				var offset = $(button).offset().top - $(window).scrollTop();	
				if( maxpage < page ){
					button.addClass( 'loaded' );
				}
				if( 1000 > offset && maxpage >= page ) {
					_product_loadmore_ajax();
				}
			}
		});
	}else{		
		button.on( 'click', function(e){
			if( maxpage >= page ){			
				_product_loadmore_ajax();
			}
			e.preventDefault();
		});
	}
}(jQuery));