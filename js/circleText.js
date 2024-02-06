(function($) {
	
	function initTurnCss() {
		var css = "@-webkit-keyframes circTxt--rotating{from{transform-origin:center;-webkit-transform:rotate(0);-o-transform:rotate(0);transform:rotate(0)}to{transform-origin:center;-webkit-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}",
			head = document.head || document.getElementsByTagName("head")[0],
			style = document.createElement("style");

		head.appendChild(style);

		style.type = "text/css";
		if (style.styleSheet) {
			// This is required for IE8 and below.
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}
	}

	function createCircularText(txt, radius, classIndex) {
		(txt = txt.split("")), (classIndex = classIndex[0]);
		//classIndex = $('.circTxt')[0];
		var deg = 360 / txt.length,
			origin = 0;
		txt.forEach(ea => {
			ea = `<p style='height:${radius}px;width:${radius}px;text-align:center;position:absolute;transform:rotate(${origin}deg);transform-origin:bottom center'>${ea}</p>`;
			classIndex.innerHTML += ea;
			origin += deg;
		});
	}

	$.fn.circleText = function(parameters = {}) {
		$(this).each(function() {
			var paramsdefault = {
				padding: 10,
				glue: "",
				turn: false,
				duration: 10,
				repeat: 1,
				radius: 100,
				background: "",
				rounded: true,
				reverse: false,
			};

			var params = {
				padding:
					"padding" in parameters ? parameters.padding : paramsdefault.padding,
				glue: "glue" in parameters ? parameters.glue : paramsdefault.glue,
				turn: "turn" in parameters ? parameters.turn : paramsdefault.turn,
				duration:
					"duration" in parameters ? parameters.duration : paramsdefault.duration,
				repeat: "repeat" in parameters ? parameters.repeat : paramsdefault.repeat,
				radius: "radius" in parameters ? parameters.radius : paramsdefault.radius,
				background:
					"background" in parameters
						? parameters.background
						: paramsdefault.background,
				rounded:
					"rounded" in parameters ? parameters.rounded : paramsdefault.rounded,
				content:
					"content" in parameters ? parameters.content : paramsdefault.content,
					reverse:
					"reverse" in parameters ? parameters.reverse : paramsdefault.reverse
			};

			//set the content
			var content;
            if (params.content) {
                //if !empty params.content -> set the content as content + glue
                content = params.content + params.glue;
            } else if ($(this).text().length > 0) {
                //if empty params.content -> set the content as innerhtml + glue
                content = $(this).html() + params.glue;
            } else {
                content = "You forgot to include content ❤";
            }
			//erase the html to create all chars after
			$(this).html("");

			//create the circular text
			createCircularText(content.repeat(params.repeat), params.radius, $(this));

			//set css for the container
			$(this).css({
				height: params.radius * 2 + params.padding * 2,
				width: params.radius * 2 + params.padding * 2,
				display: "flex",
				"justify-content": "center",
				padding: params.padding,
				background: params.background,
				"border-radius": params.rounded ? "50%" : ""
			});

			//set the
			if (params.turn === true) {
				initTurnCss();
				var animation_direction = (params.reverse) ? 'reverse' : 'normal'
				var animation = `circTxt--rotating ${params.duration}s linear infinite ${animation_direction}`;
				$(this).css({
					"transform-origin": "center",
					animation: animation
				});
			}
		});
	};
})(jQuery);