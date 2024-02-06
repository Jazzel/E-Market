(function($){
	$.fn.megamenu = function(options) {
		options = jQuery.extend({
			  wrap:'.nav-mega',
			  speed: 0,
			  rtl: false,
			  mm_timeout: 0
		  }, options);
		var menuwrap = $(this);
		buildmenu(menuwrap);
		/* Build menu */
		function buildmenu(mwrap){
			mwrap.find('.emarket-mega > li').each(function(){
				var menucontent 		= $(this).find(".dropdown-menu");
				var menuitemlink 		= $(this).find(".item-link:first");
				var menucontentinner 	= $(this).find(".nav-level1");
				var mshow_timer = 0;
				var mhide_timer = 0;
				var li = $(this);
				var islevel1 = (li.hasClass('level1')) ?true:false;
				var havechild = (li.hasClass('dropdown')) ?true:false;
				if( !havechild ){
					return;
				}
				var menu_event = $( 'body' ).hasClass( 'menu-click' );
				if( menu_event ){
					li.on( 'click', function(){
						 positionSubMenu(li, islevel1);	
						$(this).find( '>ul').toggleClass( 'visible' );
					});
					$(document).mouseup(function (e){
							var container = li.find( '>ul');
							if (!container.is(e.target) && container.has(e.target).length === 0) {
									container.removeClass( 'visible' );
							}
					});
					li.find( '> a[data-toogle="dropdown"]' ).on( 'click', function(e){
						e.preventDefault();			
					});
					
				}else{							
					li.mouseenter(function(el){
						li.find( '>ul').addClass( 'visible' );
						if(havechild){
							positionSubMenu(li, islevel1);						
						}
					}).mouseleave(function(el){ 
						li.find( '>ul').removeClass( 'visible' );				
					});
				}
			});
		}		
		
		function positionSubMenu(el, islevel1){
			menucontent 		= $(el).find(".dropdown-menu");
			menuitemlink 		= $(el).find(".item-link");
			menuitemlink_offset = menuitemlink.offset();
	    	menucontentinner 	= $(el).find(".nav-level1");
			mega_full			= ( $(el).hasClass( 'megamenu-full' ) ) ? true : false;
	    	wrap_O				= ( options.rtl == false ) ? menuwrap.offset().left : ( $(window).width() - (menuwrap.offset().left + menuwrap.outerWidth()) );
	    	wrap_W				= menuwrap.outerWidth();
			menu_parent 		= 
	    	menuitemli_O		= ( options.rtl == false ) ? $(el).offset().left : ( $(window).width() - ($(el).offset().left + $(el).outerWidth()) );
	    	menuitemli_W		= $(el).outerWidth();
	    	menuitemlink_H		= menuitemlink.outerHeight();
	    	menuitemlink_W		= menuitemlink.outerWidth();
	    	menuitemlink_O		= ( options.rtl == false ) ? menuitemlink_offset.left : ( $(window).width() - (menuitemlink_offset.left + menuitemlink.outerWidth()) );
	    	menucontent_W		= menucontent.outerWidth();
			
			var wrap_RE = wrap_O + wrap_W;
			var menucontent_RE = menuitemlink_O + menucontent_W;
			var check = ( $(el).hasClass( 'shop-emarket' ) || $(el).hasClass( 'blog-emarket' ) || $(el).hasClass( 'promotions-emarket' ) || $(el).hasClass( 'special-emarket' ) ) ? true : false;
			if( mega_full || check ){
				
				var left_offset = menuitemlink_O - wrap_O;
				if( options.rtl == false ){
					menucontent.css({
						'left': '-' + left_offset + 'px',
						'width': wrap_W + 'px'
					}); 
				}else{
					menucontent.css({
						'left': 'auto',
						'right': -left_offset + 'px',
						'width': wrap_W + 'px'
					});
				}
			}else{
				if( menucontent_RE >= wrap_RE ) { 
					var left_offset = wrap_RE - menucontent_RE + menuitemlink_O - menuitemli_O;
					if( left_offset > wrap_O ){
						left_offset = wrap_O - menuitemlink_O;
					}
					if( options.rtl == false ){
						if( left_offset > wrap_O ){
							menucontent.css({
								'left':left_offset + 'px',
								'width':  wrap_W + 'px'
							}); 
						}else{
							menucontent.css({
								'left':left_offset + 'px'
							}); 
						}
					}else{						
						if( left_offset > wrap_O ){
							menucontent.css({
								'left': 'auto',
								'right': left_offset + 'px',
								'width':  wrap_W + 'px'
							});
						}else{
							menucontent.css({
								'left': 'auto',
								'right': left_offset + 'px'
							});
						}
					}
				}
			}
		}
	};
			
	$(document).on( 'ready', function(){
		var rtl = $('body').hasClass('rtl');
		$('.header-mid').megamenu({ 
			wrap:'.nav-mega',
			justify: 'left',
			rtl: rtl
		});
		$('.header-bottom').megamenu({ 
			wrap:'.nav-mega',
			justify: 'left',
			rtl: rtl
		});
	});		
})(jQuery);