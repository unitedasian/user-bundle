!function( $ ) {
    $.fn.profile = function( method ) {

        var settings;

        // Public methods
        var methods = {
            init: function( options ) {

                settings = $.extend( true, {}, $.fn.profile.defaults, options );

                return this.each(function() {
                    var $this = $( this ) ;

                    $( "._profile", this ).autotabs( $.extend( true, {}, settings.autotabs, {
                        success: {
                            profile: helpers.initProfileTab,
                            password: helpers.initPasswordTab
                        }
                    } ) );
                });

            }
        };

        var helpers = {
            initProfileTab: function() {
                var pane = $( this );

                $( "form", pane ).ajaxForm( {
                    target: pane,
                    success: function() {
                        helpers.initProfileTab.apply( pane );
                    }
                } );
            },

            initPasswordTab: function() {
                var pane = $( this );

                $( "form", pane ).ajaxForm( {
                    target: pane,
                    success: function() {
                        helpers.initPasswordTab.apply( pane );
                    }
                } );
            }
        }

        if ( methods[ method ] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
        }
        else if ( typeof method === "object" || !method ) {
            return methods.init.apply( this, arguments );
        }
        else {
            $.error( "Method " +  method + " does not exist in $.profile." );
        }
    };

    $.fn.profile.defaults = {
        autotabs: {
            cookie_name: "uam_user_profile",
            active_class: "active",
            vertical: true,
            tabs_class: "nav nav-pills nav-stacked",
            tabs_selector: ".tabs"
        }
    };
} ( window.jQuery );

$( document ).ready(function() {
    $( ".profile" ).profile( typeof uam_user == 'undefined' ? {} : uam_user );
});
