(function($) {
	"use strict";
	
	/* 
	** Add Click On Ipad 
	*/
	$(window).resize(function(){
		var $width = $(this).width();
		if( $width < 1199 ){
			$( '.primary-menu .nav .dropdown-toggle'  ).each(function(){
				$(this).attr('data-toggle', 'dropdown');
			});
		}
	});
	if( $("div").hasClass(".jet-smart-filters-pagination") ){
		$(".box-shop  .woocommerce-pagination").css("display", "none");
	}
	$(document).ready(function(){
		
	
		$('.guide_size_block .guide_size').on('click', function () {
			$('.guide_size_block .block-up').addClass('open');
			$('body').addClass('open');
		});
	   
		$(document).mouseup(function (e) {
			var popup = $(".guide_size_block .block-up");
			if (!$('.guide_size_block .guide_size').is(e.target) && !popup.is(e.target) && popup.has(e.target).length == 0) {
				popup.removeClass('open');
				$('body').removeClass('open');
			}
		});
	});
	

	/*
	** Blog Masonry
	*/
	$(window).load(function() {
		$('body').find('.blog-content-grid').isotope({ 
			layoutMode : 'masonry'
		});	
		setTimeout(function(){
			$('.loader1').fadeToggle();

		}, 500);		
	});


	/*
	** Search on click
	*/
	$('.search-cate .search-tog').on('click', function(){
		$('.search-cate .emarket_top').slideToggle();
		$('.fa').toggleClass('open');
	});
	
	$('.header-right .menu-confirmation .text-confirmation').on('click', function(){
		$('.header-right .menu-confirmation').toggleClass("open");
	});
	
	$( ".header-mid-46 .support-home46" )
	  .mouseenter(function() {
		$('.header46-support').addClass("open");
	  })
	  .mouseleave(function() {
		$('.header46-support').removeClass("open");
	  });
	
	$( ".header46-support" )
	  .mouseenter(function() {
		$(this).addClass("open");
	  })
	  .mouseleave(function() {
		$(this).removeClass("open");
	  });
	  
	$( ".icon-globe" )
	  .mouseenter(function() {
		$('.box-currency-languge').addClass("open");
	  })
	  .mouseleave(function() {
		$('.box-currency-languge').removeClass("open");
	  });
	  
	$( ".box-currency-languge" )
	  .mouseenter(function() {
		$('.box-currency-languge').addClass("open");
	  })
	  .mouseleave(function() {
		$('.box-currency-languge').removeClass("open");
	 });
	  
	// VIDEO 1
	$('.book_video[data-video]').one('click', function() {

	var $this = $(this);
	var width = $this.attr("width");
	var height = $this.attr("height");

	$this.html('<iframe height="'+ height +'" frameborder="0" src="https://www.youtube.com/embed/' + $this.data("video") + '?autoplay=1"></iframe>');
	});
	
	// VIDEO 2
	$('.book_video2[data-video]').each(function(){
		var $this = $(this);
		var width = $this.attr("width");
		if( $(window).width() > 1170 ){
			var height = $this.attr("height");
		}else{
			var height = ($(window).width())/2.05;
		}
		$this.css("height", height);
		$('.book_video2[data-video]').on('click', function(){
			$this.html('<iframe height="'+ height +'" frameborder="0" src="https://www.youtube.com/embed/' + $this.data("video") + '?autoplay=1"></iframe>');
		});
	});
	
	// Accordion Shop Filter
	$('.sw-filter-accordion .sw-filter-above-shop .widget').each(function(i) {
		$(this).attr('id', 'widget_filter'+(i+1));
	});
	$('.sw-filter-accordion .sw-filter-above-shop .widget').each(function(i){
		var id = $(this).attr("id");
		$('#' + id + ' .widget-inner .block-title-widget h2').on('click', function(e){
			if( $('#' + id).hasClass("open") ){
				$('#' + id).removeClass("open");
			}else{
				$('.sw-filter-accordion .sw-filter-above-shop .widget').removeClass('open');
				$('#' + id).toggleClass("open");
			}
			e.preventDefault();
        });
	});
	$('.elementor-widget-jet-smart-filters-checkboxes').each(function(i) {
		var $id = $(this).data("id");
		$(this).attr('id', 'widget_filter_'+$id);
	});
	$('.elementor-widget-jet-smart-filters-checkboxes').each(function(e){
			var target = $(this).attr("id");
			
			$('#' + target + " .jet-filter-label").click(function(){
			  $('#' + target + " .jet-checkboxes-list").slideToggle("slow");
			  $('#' + target).toggleClass("active");
			  $(this).toggleClass("active");
			});
	});
	
	$(".jet-smart-filters-color-image .jet-filter-label").click(function(){
	  $(".jet-smart-filters-color-image .jet-color-image-list").slideToggle("slow");
	  $(this).toggleClass("active");
	});
	
	$(".jet-smart-filters-range .jet-filter-label").click(function(){
	  $(".elementor-widget-jet-smart-filters-range").toggleClass("active");
	  $(this).toggleClass("active");
	});
	
	$('.page-filter-drawer .products-nav .sw-filter-button').on('click', function(){
		$('.page-filter-drawer .sidebar-row').toggleClass("active");
	});
	
	// click filter
	$(document).on( 'click', '.box-shop .products-nav .sw-filter-button', function(){
		$(".box-content-archive ").toggleClass( 'active_filters' );
		$(".sw-filter-button ").toggleClass("closex");
	});
	
	$( ".custom-box-home55" )
	  .mouseenter(function() {
		$('.box-language-currency').addClass('active');
		$(this).addClass('active');
	  })
	  .mouseleave(function() {
		$('.box-language-currency').removeClass('active');
		$(this).removeClass('active');
	  });
	  
	$( ".box-language-currency" )
	  .mouseenter(function() {
		$(this).addClass('active');
		$( ".custom-box-home55" ).addClass('active');
	  })
	  .mouseleave(function() {
		$(this).removeClass('active');
		$( ".custom-box-home55" ).removeClass('active');
	  });
	  
	/*
	** js single product
	*/
	$('.single-product .social-share .title-share').on('click', function(){
		$('.single-product .social-share').toggleClass("open");
	});
	$('.single-post .social-share .title-share').on('click', function(){
		$('.single-post .social-share').toggleClass("open");
	});

	$('.single-post .social-share.open .title-share').on('click', function(){
		$('.single-post .social-share').removeClass("open");
	});
	
	$('.products-nav .filter-product').on('click', function(){
		$('.products-wrapper .filter-mobile').toggleClass("open");
		$('.products-wrapper').toggleClass('show-modal');
	});
	
	$('.products-nav .filter-product').on('click', function(){
		if( $( ".products-wrapper .products-nav .filter-product" ).not( ".filter-mobile" ) ){
			$('.products-wrapper').removeClass('show-modal');
		}	
	});
	
	$('.product-categories').each(function(){
		var number	 = $(this).data( 'number' ) - 1;
		var lesstext = $(this).data( 'lesstext' );
		var moretext = $(this).data( 'moretext' );
		if( number > 0 ) {
			$(this).find( '>li:gt('+ number +')' ).hide().end();		
			if( $(this).children('li').length > number ){ 
				$(this).append(
					$('<li><a>'+ moretext +'   </a></li>')
					.addClass('showMore')
					.on('click',function(){
						if($(this).siblings(':hidden').length > 0){
							$(this).html( '<a>'+ lesstext +'   </a>' ).siblings(':hidden').show(400);
						}else{
							$(this).html( '<a>'+ moretext +'   </a>' ).show().siblings( ':gt('+ number +')' ).hide(400);
						}
					})
					);
			}
		}
		if( $(this).hasClass( 'accordion-categories' ) ){
			$(this).find( 'li.cat-parent' ).append( '<span class="child-category-more"></span>' );
		}
	});
	
	$(document).on( 'click', '.child-category-more', function(){
		$(this).parent().toggleClass( 'active' );
		//$(this).parent().find( '> ul.children' ).toggle(500);
	});
	
	$('.sw-category-slider5 ul')
	.find('li:gt(7)') /*you want :gt(4) since index starts at 0 and H3 is not in LI */
	.hide()
	.end()
	.each(function(){
			if($(this).children('li').length > 8){ //iterates over each UL and if they have 5+ LIs then adds Show More...
				$(this).append(
					$('<li><a><span class="menu-title">show all</span></a></li>')
					.addClass('showMore')
					.on('click',function(){
						if($(this).siblings(':hidden').length > 0){
							$(this).html('<a><span class="menu-title">Show less</span></a>').siblings(':hidden').show(400);
						}else{
							$(this).html('<a><span class="menu-title">show all</span></a>').show().siblings('li:gt(7)').hide(400);
						}
					})
				);
			}
		});
		
	/*add title to button*/
	$("a.compare").attr('title', custom_text.compare_text);
	$(".yith-wcwl-add-button a").attr('title', custom_text.wishlist_text);
	$("a.add_to_cart_button").attr('title', custom_text.cart_text);
	
	/* 
     ** Filter Shop
     */
    $(' .sw-filter-button').on('click', function() {
        $('.sw-filter-above-shop').toggleClass("open");
        $('.sw-filter-button').toggleClass("off");
		$('body.page-shop-filter-canvas').toggleClass('open');
    });
    $('.sw-filter-button').on('click', function(e) {
        var target = $(this).data('target');
        $(target).toggleClass('open');
        e.preventDefault();
    });

    $('.sw-filter-close').on('click', function(e) {
        var target = $(this).data('target');
        $(target).removeClass('open');
		$('.sw-filter-button').removeClass("off");
		$('body').removeClass('open');
        e.preventDefault();
    });
	
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
	});
	
	
	$('.sw-woo-tab-cat17 .nav.nav-tabs li').on('click', function(e){
		$('.sw-woo-tab-cat17 .categories-res .cat-active').html($(this).find('a').html());
	});
	$('.sw-woo-tab-cat17 .categories-res > span').on('click', function(e){
		$(this).toggleClass('active');
		$('.sw-woo-tab-cat17 .resp-tab .top-tab-slider .nav-tabs').toggleClass("active");
	});
	/*
	** Product listing select box
	*/
	$('.catalog-ordering .orderby .current-li a').html($('.catalog-ordering .orderby ul li.current a').html());
	$('.catalog-ordering .sort-count .current-li a').html($('.catalog-ordering .sort-count ul li.current a').html());
	$('.number-count-product .number-product').html($('.catalog-ordering .sort-count li.current-li a').html());
	$('.full-width-filter .filter-text ').on('click', function(e){
		$('.filter-top-full').toggleClass("open");
		$('.filter-text').toggleClass("active");
        e.preventDefault();
    });
	
	/*
	** Quickview and single product slider
	*/
	$(document).ready(function(){
		if( $.isFunction( $.fancybox ) ){
			$('.fancybox').fancybox({
				'width'     : 850,
				'height'   : '500',
				'autoSize' : false,
				afterShow: function() {
					$( '.quickview-container .product-images' ).each(function(){
						var $id 					= this.id;
						var $rtl 					= $('body').hasClass( 'rtl' );
						var $img_slider 	= $(this).find('.product-responsive');
						var $thumb_slider = $(this).find('.product-responsive-thumbnail' )
						$img_slider.slick({
							slidesToShow: 1,
							slidesToScroll: 1,
							fade: true,
							arrows: false,
							asNavFor: $thumb_slider
						});
						$thumb_slider.slick({
							slidesToShow: 4,
							slidesToScroll: 1,
							asNavFor: $img_slider,
							arrows: true,
							focusOnSelect: true,
							responsive: [				
							{
								breakpoint: 360,
								settings: {
									slidesToShow: 2    
								}
							}
							]
						});

						var el = $(this);
						setTimeout(function(){
							el.removeClass("loading");
							var height = el.find('.product-responsive').outerHeight();
							var target = el.find( ' .item-video' );
							target.css({'height': height,'padding-top': (height - target.outerHeight())/2 });

							var thumb_height = el.find('.product-responsive-thumbnail' ).outerHeight();
							var thumb_target = el.find( '.item-video-thumb' );
							thumb_target.css({ height: thumb_height,'padding-top':( thumb_height - thumb_target.outerHeight() )/2 });
						}, 1000);
					});
				}
			});
		}
		/* 
		** Slider single product image
		*/
		$( '.product-images' ).each(function(){
			var $rtl 			= $('body').hasClass( 'rtl' );
			var $vertical		= $(this).data('vertical');
			var $vendor			= $(this).data('vendor');
			var $autoplay		= $(this).data('autoplay');
			var $dots			= $(this).data('dots');
			var $img_slider 	= $(this).find('.product-responsive');
			var $thumb_slider 	= $(this).find('.product-responsive-thumbnail' );
			var video_link 		= $(this).data('video');
			var number_mobile 	= ( $vertical ) ? 2: 3;
			if( $vertical ){
				
				var number = 4;
				
				if( $vendor ){
					
					number = 3;
					
				}else{
					
					number = 4;
					
				}
			}else{
				
				var number = 5;
				
			}
			
			$img_slider.slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				arrows: false,
				autoplay: $autoplay,
				autoplaySpeed: 2000,
				dots: $dots,
				asNavFor: $thumb_slider,
			});
			$thumb_slider.slick({
				slidesToShow: number,
				slidesToScroll: 1,
				asNavFor: $img_slider,
				arrows: true,
				vertical: $vertical,
				verticalSwiping: $vertical,
				focusOnSelect: true,
				responsive: [
				{
					breakpoint: 768,
					settings: {
						slidesToShow: 3    
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: number_mobile    
					}
				},
				{
					breakpoint: 360,
					settings: {
						slidesToShow: 2    
					}
				}
				]
			});
			var el = $(this);
			setTimeout(function(){
				el.removeClass("loading");				
			}, 1000);
			if( video_link != '' ) {
				$img_slider.append( '<button data-type="popup" class="featured-video-button" data-video="'+ video_link +'"><i class="fa fa-play"></i></button>' );
			}
		});
		
		/* for bundle */
		$( '.product-images-bundle' ).each(function(){
			var $rtl 					= $('body').hasClass( 'rtl' );
			var $vertical			= $(this).data('vertical');
			var $img_slider 	= $(this).find('.products-thumb-big');
			var $thumb_slider = $(this).find('.product-responsive-thumbnail' );
			
			$img_slider.slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				arrows: false,
				rtl: $rtl,
				asNavFor: $thumb_slider,
				infinite: false
			});
			$thumb_slider.slick({
				slidesToShow: 3,
				slidesToScroll: 1,
				asNavFor: $img_slider,
				arrows: true,
				rtl: $rtl,
				infinite: false,
				vertical: $vertical,
				verticalSwiping: $vertical,
				focusOnSelect: true,
				responsive: [
				{
					breakpoint: 1190,
					settings: {
						slidesToShow: 3    
					}
				},
				{
					breakpoint: 992,
					settings: {
						slidesToShow: 3    
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 3    
					}
				},
				{
					breakpoint: 481,
					settings: {
						slidesToShow: 3    
					}
				}
				]
			});

			var el = $(this);
			setTimeout(function(){
				el.removeClass("loading");
				var height = el.find('.product-responsive').outerHeight();
				var target = el.find( ' .item-video' );
				target.css({'height': height,'padding-top': (height - target.outerHeight())/2 });

				var thumb_height = el.find('.product-responsive-thumbnail' ).outerHeight();
				var thumb_target = el.find( '.item-video-thumb' );
				thumb_target.css({ height: thumb_height,'padding-top':( thumb_height - thumb_target.outerHeight() )/2 });
			}, 1000);
		});
		
		/*
		** Popup content
		*/
		if( $('body').html().match( /sw-popup-bottom/ ) ){
			var qv_target =  $('.sw-popup-bottom');
			$(document).on( 'click', 'button[data-type="popup"]', function(){
				var video_url = $(this).data( 'video' );
				qv_target.addClass( 'show loading' );					
				setTimeout(function(){
					qv_target.find( '.popup-inner' ).append( '<div class="video-wrapper"><iframe width="560" height="390" src="'+ video_url +'" frameborder="0" allowfullscreen></iframe></div>' );	
					qv_target.find( '.popup-content' ).css( 'margin-top', ( $(window).height() - qv_target.find( '.popup-content' ).outerHeight() ) /2 );
					qv_target.removeClass( 'loading' );
				}, 1000);
			});
			
			$( '.popup-close' ).on('click', function(){
				qv_target.removeClass( 'show' );
				qv_target.find( '.popup-inner' ).html('');			
			});
			$(document).click(function(e) {			
				var container = qv_target.find( '.popup-inner' );
				if ( !container.is(e.target) && container.has(e.target).length === 0 && qv_target.find( '.popup-inner' ).html().length > 0 ){
					qv_target.removeClass( 'show' );
					qv_target.find( '.popup-inner' ).html('');
				}
			});
		}
	});

	/*
	** Hover on mobile and tablet
	*/
	var mobileHover = function () {
		$('*').on('touchstart', function () {
			$(this).trigger('hover');
		}).on('touchend', function () {
			$(this).trigger('hover');
		});
	};
	mobileHover();
	
	
	/*
	** Vertical Menu 
	*/
	
	$('.vertical-megamenu').each(function(){
		var number	 = $(this).parent().data( 'number' ) - 1;
		var lesstext = $(this).parent().data( 'lesstext' );
		var moretext = $(this).parent().data( 'moretext' );
		$(this).find(	' > li:gt('+ number +')' ).hide().end();		
		if($(this).children('li').length > number ){ 
			$(this).append(
				$('<li><a class="open-more-cat">'+ moretext +'</a></li>')
				.addClass('showMore')
				.on('click', function(){
					if($(this).siblings(':hidden').length > 0){
						$(this).html('<a class="close-more-cat">'+ lesstext +'</a>').siblings(':hidden').show(400);
					}else{
						$(this).html('<a class="open-more-cat">'+ moretext +'</a>').show().siblings( ':gt('+ number +')' ).hide(400);
					}
				})
				);
		}
	});	

	/* 
	** Fix accordion heading state 
	*/
	$('.accordion-heading').each(function(){
		var $this = $(this), $body = $this.siblings('.accordion-body');
		if (!$body.hasClass('in')){
			$this.find('.accordion-toggle').addClass('collapsed');
		}
	});	
	
	/*
	** Footer accordion
	*/
	
	if ($(window).width() < 768) {	

		$('.cusom-menu-mobile .elementor-widget-wp-widget-nav_menu h5').append('<span class="icon-footer"></span>');
		
		$(".cusom-menu-mobile .elementor-widget-wp-widget-nav_menu h5").each(function(){
			$(this).on('click', function(){
				$(this).parent().find("ul.menu").slideToggle();
			});
		});
		
	}
	
	$(".mobile-layout .cusom-menu-mobile .widget_nav_menu h2.widgettitle").append('<span class="icon-footer"></span>');
	
	$('.footer-mstyle2 .mobile_menu2')
	.find('li:gt(7)') /*you want :gt(4) since index starts at 0 and H3 is not in LI */
	.hide()
	.end()
	.each(function(){
			if($(this).children('li').length > 8){ //iterates over each UL and if they have 5+ LIs then adds Show More...
				$(this).append(
					$('<li><a><span class="menu-title">See more</span><span class="menu-img"></span></a></li>')
					.addClass('showMore')
					.on('click',function(){
						if($(this).siblings(':hidden').length > 0){
							$(this).html('<a><span class="menu-title">See less</span><span class="menu-img"></span></a>').siblings(':hidden').show(400);
						}else{
							$(this).html('<a><span class="menu-title">See more</span><span class="menu-img"></span></a>').show().siblings('li:gt(7)').hide(400);
						}
					})
				);
			}
		});
	
	/*
	** Back to top
	**/
	$("#emarket-totop").hide();
	var wh = $(window).height();
	var whtml = $(document).height();
	$(window).scroll(function () {
		if ($(this).scrollTop() > whtml/10) {
			$('#emarket-totop').fadeIn();
		} else {
			$('#emarket-totop').fadeOut();
		}
	});
	
	$('#emarket-totop').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});
	/* end back to top */
		
	 /*
	 ** Fix js 
	 */
	 $('.wpb_map_wraper').on('click', function () {
		$('.wpb_map_wraper iframe').css("pointer-events", "auto");
	 });

	 $( ".wpb_map_wraper" ).on('mouseleave', function() {
		$('.wpb_map_wraper iframe').css("pointer-events", "none"); 
	 });

	
	$(document).ready(function(){
		var className = $('#comments .commentlist .entry-summary').children().eq(1).attr('class');
		if( className != 'children' ){
			$('.commentlist').addClass("not-child");
		}
	});
	
	$('#nav').onePageNav();
	
	$(document).on( 'click', '.grid-view', function(){
		$('.list-view2').removeClass('active');
		$('.grid-view').addClass('active');
		jQuery("ul.products-loop").fadeOut(300, function() {
			$(this).fadeIn(300).addClass( 'grid' ).removeClass( 'list2' );			
		});
	});
	$(document).on( 'click', '.list-view2', function(){
		$( '.grid-view' ).removeClass('active');
		$( '.list-view2' ).addClass('active');
		$("ul.products-loop").fadeOut(300, function() {
			jQuery(this).addClass("list2").fadeIn(300).removeClass( 'grid' );
		});
	});
	/*
	** Change Layout 
	*/

	/*remove loading*/
	$(".sw-woo-tab").fadeIn(300, function() {
		var el = $(this);
		setTimeout(function(){
			el.removeClass("loading");
		}, 1000);
	});
	$(".responsive-slider").fadeIn(300, function() {
		var el = $(this);
		setTimeout(function(){
			el.removeClass("loading");
		}, 1000);
	});
	
}(jQuery));

	/*
	** Check comment form
	*/
	function submitform(){
		if(document.commentform.comment.value=='' || document.commentform.author.value=='' || document.commentform.email.value==''){
			alert('Please fill the required field.');
			return false;
		} else return true;
	}

(function($){		
	
	$(".widget_nav_menu li.menu-compare a").hover(function() {
		$(this).css('cursor','pointer').attr('title', 'Compare');
	}, function() {
		$(this).css('cursor','auto');
	});
	$(".widget_nav_menu li.menu-wishlist a").hover(function() {
		$(this).css('cursor','pointer').attr('title', 'My Wishlist');
	}, function() {
		$(this).css('cursor','auto');
	});
	
	/*
	** Ajax login
	*/
	$('form#login_ajax').on('submit', function(e){
		var target = $(this);		
		var usename = target.find( '#username').val();
		var pass 	= target.find( '#password').val();
		if( usename.length == 0 || pass.length == 0 ){
			target.find( '#login_message' ).addClass( 'error' ).html( custom_text.message );
			return false;
		}
		target.addClass( 'loading' );
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: custom_text.ajax_url,
			headers: { 'api-key':target.find( '#woocommerce-login-nonce').val() },
			data: { 
				'action': 'emarket_custom_login_user', //calls wp_ajax_nopriv_ajaxlogin
				'username': target.find( '#username').val(), 
				'password': target.find( '#password').val(), 
				'security': target.find( '#woocommerce-login-nonce').val() 
			},
			success: function(data){
				target.removeClass( 'loading' );
				target.find( '#login_message' ).html( data.message );
				if (data.loggedin == false){
					target.find( '#username').css( 'border-color', 'red' );
					target.find( '#password').css( 'border-color', 'red' );
					target.find( '#login_message' ).addClass( 'error' );
				}
				if (data.loggedin == true){
					target.find( '#username').removeAttr( 'style' );
					target.find( '#password').removeAttr( 'style' );
					document.location.href = data.redirect;
					target.find( '#login_message' ).removeClass( 'error' );
				}
			}
		});
		e.preventDefault();
	});
	
	/*
	** Responsive Menu
	*/
	$( '.resmenu-container-sidebar .more-menu > a' ).on( 'click', function(e){
		$(this).parent().find( '>ul' ).toggle();
		e.preventDefault();
	});
		
})(jQuery);

jQuery(document).ready(function($) {
	var  $content  = $('#single-left'),
	$sidebar   = $('#single-right');

	if ($sidebar.length > 0 && $content.length > 0) {
		var $window    = $(window),
			offset     = $sidebar.offset(),
			timer;

		$window.scroll(function() {
			clearTimeout(timer);
			timer = setTimeout(function() {
				if ($content.height() > $sidebar.height()) {
					var new_margin = $window.scrollTop() - offset.top;
					if ($window.scrollTop() > offset.top && ($sidebar.height()+new_margin) <= $content.height()) {
						// Following the scroll...
						$sidebar.stop().animate({ marginTop: new_margin }, 300);
						$sidebar.addClass('fixed');
					} else if (($sidebar.height()+new_margin) > $content.height()) {
						// Reached the bottom...
						$sidebar.stop().animate({ marginTop: $content.height()-$sidebar.height() }, 300);
					} else if ($window.scrollTop() <= offset.top) {
						// Initial position...
						$sidebar.stop().animate({ marginTop: 0 }, 300);
						$sidebar.removeClass('fixed');
					}
				}else{
					var new_margin = $window.scrollTop() - offset.top;
					if ($window.scrollTop() > offset.top && ($content.height()+new_margin) <= $sidebar.height()) {
						// Following the scroll...
						$content.stop().animate({ marginTop: new_margin }, 300);
						$content.addClass('fixed');
					} else if (($content.height()+new_margin) > $sidebar.height()) {
						// Reached the bottom...
						$content.stop().animate({ marginTop: $sidebar.height()-$content.height() }, 300);
					} else if ($window.scrollTop() <= offset.top) {
						// Initial position...
						$content.stop().animate({ marginTop: 0 }, 300);
						$content.removeClass('fixed');
					}
				}
			}, 100); 
		});
	}
});
