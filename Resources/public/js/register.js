!function( $ ) {
	$.fn.register = function( method ) {

		var settings;

		// Public methods
		var methods = {
			init: function( options ) {

				settings = $.extend( true, {}, $.fn.register.defaults, options );

				return this.each(function() {
					var $this = $( this ) ;

					helpers.initProfileForm.apply( $( "form.profile", $this ) );
				});

			}
		};

		var helpers = {
			initProfileForm: function() {
				var $form = $( this );
				$form.ajaxForm( {
					target: $form,
					success: function( data ) {
						if ( $( data ).hasClass( "error" ) ) {
							console.log( "error" );
							helpers.initProfileForm.apply( $form );
						}
						else {
							window.location.reload(true);
						}
					}
				});
			}
		}

		if ( methods[ method ] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
		}
		else if ( typeof method === "object" || !method ) {
			return methods.init.apply( this, arguments );
		}
		else {
			$.error( "Method " +  method + " does not exist in $.register." );
		}
	};

	$.fn.register.defaults = {
	};
} ( window.jQuery );


$( document ).ready(function() {
	$( ".registered" ).register();
});
