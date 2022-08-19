var $ = window.jQuery;

window.UTILS = window.UTILS || { };

window.UTILS.extended_password_field = {

	init: function() {

		var enableRevealPassword = function() {

			/*
				Selector for revealable password fields.
			*/
			var passwordFieldSelector = "input[type='password'][data-password_reveal='1']";

			/*
				Sets positioning and sizing for password reveal button
				Called on init, then on window.resize.
				Also should be called when some layout changes affects
				password field and its container.
			*/
			var setPwRevealPositions = function() {
				$.each( $( ".password-input-container" ), function( i, el ) {
					var pwContainer = $( el );
					var pwInput = pwContainer.find( "input" );
					var revealButton = pwContainer.find( ".password-reveal-button" );

					// Reset container style attr to its original state
					pwContainer.removeAttr( "style" );
					pwContainer.attr( "style", pwContainer.attr( "data-style" ) );

					// If container position is static, set it to relative
					// to enable abs. positioning of reveal button
					if ( pwContainer.css( "position" ) === "static" ) {
						pwContainer.css( "position", "relative" );
					}

					// Position reveal button
					revealButton.css( "height", pwInput.outerHeight() + "px" );
					var posH = pwInput.offset().left - pwContainer.offset().left +
					pwInput.outerWidth() - revealButton.width();
					var posV = pwInput.offset().top - pwContainer.offset().top;
					revealButton.css( "left", posH + "px" );
					revealButton.css( "top", posV + "px" );

					// Copy input text color to button icon color
					revealButton.css( "color", pwInput.css( "color" ) );

				} );
			};

			/*
				Toggles password visibility
			*/
			var togglePasswordReveal = function() {

				var revealButton = $( this );
				var pwInput = revealButton.closest( ".password-input-container" ).
				find( ".input--password" );

				if ( pwInput.attr( "type" ) === "password" ) {
					revealButton.addClass( "revealed" );
					pwInput.attr( "type", "text" );
				} else {
					revealButton.removeClass( "revealed" );
					pwInput.attr( "type", "password" );
				}
				pwInput.focus();
			};

			/*
				Initialization of password reveal
			*/
			var pwFields = $( passwordFieldSelector );
			$.each( pwFields, function( i, el ) {

				// Get password input
				var pwInput = $( el );
				pwInput.addClass( "input--password" );

				var pwContainer = pwInput.parent();
				pwContainer.addClass( "password-input-container" );

				// Backup style attribute if present
				pwContainer.attr( "data-style", pwContainer.attr( "style" ) );

				var revealButton = pwContainer.find( ".password-reveal-button" );

				revealButton.on( "click", togglePasswordReveal );

			} );

			setPwRevealPositions();
			$( window ).on( "resize", setPwRevealPositions );

		};

		enableRevealPassword();

		// Progress bar
		$( "input[data-extended_password_field]" ).on( "keyup", function( event ) {
			if ( event.which === 13 ) {
				return false;
			}
			var progressBar = $( this ).parent().find( ".password_strength__progressbar" );

			var $field = $( this ),
					password = $field.val(),
					lang = $( "html" ).attr( "lang" ),
					url = "/api/" + lang + "/password_strength_analyzer/analyze/",
					minimumScoreRequired = $field.
						data( "minimum_password_strength_required" );
			$.ajax( {
				type: "POST",
				url: url,
				data: {
					format: "json",
					password: password
				},
				success: function( data ) {
					console.log(
						"password strength: " + data.score + "%, " +
						"required: " + minimumScoreRequired + "%"
					);
					var progressBarIndicator = progressBar.find( ".progress-bar" );
					progressBarIndicator.css( "width", data.score + "%" );
					progressBarIndicator.attr( "aria-valuenow", data.score + "%" );
					progressBarIndicator.text( data.score + "%" );
					progressBarIndicator.removeClass( "progress-bar-danger bg-danger" );
					progressBarIndicator.removeClass( "progress-bar-warning bg-warning" );
					progressBarIndicator.removeClass( "progress-bar-success bg-success" );
					if ( data.score < minimumScoreRequired * 0.75 ) {
						progressBarIndicator.addClass( "progress-bar-danger bg-danger" );
					} else if ( data.score < minimumScoreRequired ) {
						progressBarIndicator.addClass( "progress-bar-warning bg-warning" );
					} else {
						progressBarIndicator.addClass( "progress-bar-success bg-success" );
					}
				},
				dataType: "json"
			} );
		} );
	}

};
