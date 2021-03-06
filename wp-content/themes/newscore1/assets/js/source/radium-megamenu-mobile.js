
/**
 * Modified for Radium Megamenu
 * by Franklin M Gitonga
 * http://radiumthemes.com
 *
 * jquery.dlmenu.js v1.0.1
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
;( function( $, window, undefined ) {

    'use strict';

    // global
    var Modernizr = window.Modernizr, $body = $( 'body' );

    $.DLMenu = function( options, element ) {
        this.$el = $( element );
        this._init( options );
    };

    // the options
    $.DLMenu.defaults = {
        // classes for the animation effects
        animationClasses : { classin : 'dl-animate-in-1', classout : 'dl-animate-out-1' },
        // callback: click a link that has a sub menu
        // el is the link element (li); name is the level name
        onLevelClick : function( el, name ) { return false; },
        // callback: click a link that does not have a sub menu
        // el is the link element (li); ev is the event obj
        onLinkClick : function( el, ev ) { return false; }
    };

    $.DLMenu.prototype = {
        _init : function( options ) {

            // options
            this.options = $.extend( true, {}, $.DLMenu.defaults, options );

            // cache some elements and initialize some variables
            this._config();

            var animEndEventNames = {
                    'WebkitAnimation' : 'webkitAnimationEnd',
                    'OAnimation' : 'oAnimationEnd',
                    'msAnimation' : 'MSAnimationEnd',
                    'animation' : 'animationend'
                },
                transEndEventNames = {
                    'WebkitTransition' : 'webkitTransitionEnd',
                    'MozTransition' : 'transitionend',
                    'OTransition' : 'oTransitionEnd',
                    'msTransition' : 'MSTransitionEnd',
                    'transition' : 'transitionend'
            };

            // animation end event name
            this.animEndEventName = animEndEventNames[ Modernizr.prefixed( 'animation' ) ] + '.dlmenu';
            // transition end event name
            this.transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ] + '.dlmenu';

            if (Modernizr) {

                // support for css animations and css transitions
                this.supportAnimations = Modernizr.cssanimations;
                this.supportTransitions = Modernizr.csstransitions;
            }

            this._initEvents();

        },
        _config : function() {
            this.open = false;
            this.$trigger = this.$el.children( '.menu-trigger' );
            this.$menu = this.$el.children( 'ul.radium_mega' );
            this.$menuitems = this.$menu.find( 'li:not(.dl-back)' );
            this.$el.find( 'a').siblings('ul.sub-menu' ).prepend( '<li class="dl-back"><a href="#">back</a></li>' );
            this.$el.find( 'a').siblings('div.radium-mega-div' ).find('ul.sub-menu:first').prepend( '<li class="dl-back"><a href="#">back</a></li>' );
            this.$back = this.$menu.find( '.dl-back' );
        },

        _initEvents : function() {

            var self = this;

            this.$trigger.on( 'click.dlmenu', function() {

                event.preventDefault();

                if( self.open ) {
                    self._closeMenu();
                }
                else {
                    self._openMenu();
                }

            });

            this.$menuitems.on( 'click.dlmenu', function( event ) {

                event.stopPropagation();

                var $item = $(this),
                    $submenu = $item.find( 'ul.sub-menu' );

                    $item.find('div>ul>li>ul').removeClass('sub-menu');

                    $item.find('div>ul>li>ul>li ul').addClass('radium-mega-sub-menu');

                if( $submenu.length > 0 ) {

                    var $flyin = $submenu.clone().css( 'opacity', 0 ).insertAfter( self.$menu ),
                        onAnimationEndFn = function() {
                            self.$menu.off( self.animEndEventName ).removeClass( self.options.animationClasses.classout ).addClass( 'dl-subview' );
                            $item.addClass( 'dl-subviewopen' ).parents( '.dl-subviewopen:first' ).removeClass( 'dl-subviewopen' ).addClass( 'dl-subview' );

                            if ( $item.parent().parent().hasClass('radium_mega_menu_columns') ) {

                                $item.parent().parent().addClass( 'dl-subview' );

                            }

                            $flyin.remove();
                        };

                    setTimeout( function() {
                        $flyin.addClass( self.options.animationClasses.classin );
                        self.$menu.addClass( self.options.animationClasses.classout );
                        if( self.supportAnimations ) {
                            self.$menu.on( self.animEndEventName, onAnimationEndFn );
                        }
                        else {
                            onAnimationEndFn.call();
                        }

                        self.options.onLevelClick( $item, $item.children( 'a:first' ).text() );

                    });

                    if ( self.$el.find( 'ul.sub-menu' ).siblings('a').find('.sub-indicator').length !== 0 ) {

                        self.$el.find( 'ul.sub-menu' ).siblings('a').append( '<span class="sub-indicator"></span>' );

                    }

                    event.preventDefault();

                } else {

                    self.options.onLinkClick( $item, event );

                }

            });

            this.$back.on( 'click.dlmenu', function( event ) {

                var $this = $( this ),
                    $submenu = $this.parents( 'ul.sub-menu:first' ),
                    $item = $submenu.parent();

                    if ( $item.hasClass('radium-mega-div') ) {
                        $item = $submenu.parent().parent();
                    }

                    var $flyin = $submenu.clone().insertAfter( self.$menu );

                var onAnimationEndFn = function() {
                    self.$menu.off( self.animEndEventName ).removeClass( self.options.animationClasses.classin );
                    $flyin.remove();
                };

                setTimeout( function() {

                    $flyin.addClass( self.options.animationClasses.classout );

                    self.$menu.addClass( self.options.animationClasses.classin );

                    if( self.supportAnimations ) {

                        self.$menu.on( self.animEndEventName, onAnimationEndFn );

                    } else {

                        onAnimationEndFn.call();

                    }

                    $item.removeClass( 'dl-subviewopen' );

                    var $subview = $this.parents( '.dl-subview:first' );

                    if( $subview.is( 'li' ) ) {

                        $subview.addClass( 'dl-subviewopen' );
                    }

                    $subview.removeClass( 'dl-subview' );

                    if ( $item.parent().parent().hasClass('radium_mega_menu_columns') ) {

                        $item.parent().parent().removeClass( 'dl-subviewopen' ).parent().parent().parent().removeClass( 'dl-subview' ).addClass('dl-subviewopen');

                    }

                });

                return false;

            } );


            // listen for destroyed, call teardown
            this.$el.on("destroyed.dlmenu", $.proxy(this.teardown, this));

        },

        closeMenu : function() {
            if( this.open ) {
                this._closeMenu();
            }
        },

        _closeMenu : function() {
            var self = this,
                onTransitionEndFn = function() {

                    self.$menu.off( self.transEndEventName );
                    self._resetMenu();

                };

            this.$menu.removeClass( 'dl-menuopen' );

            this.$menu.addClass( 'dl-menu-toggle' );

            this.$trigger.removeClass( 'dl-active' );

            if( this.supportTransitions ) {
                this.$menu.on( this.transEndEventName, onTransitionEndFn );
            }
            else {
                onTransitionEndFn.call();
            }

            //remove overlay
            $('#menu-overlay').remove();

            this.open = false;
        },

        openMenu : function() {

            if( !this.open ) {
                this._openMenu();
            }

        },

        _openMenu : function() {

            var self = this;

            // clicking somewhere else makes the menu close
            //$body.off( 'click' ).on( 'click.dlmenu', function() {
                //self._closeMenu() ;
            //});

            this.$menu.addClass( 'dl-menuopen dl-menu-toggle' ).on( this.transEndEventName, function() {
                $( this ).removeClass( 'dl-menu-toggle' );
            });

            this.$trigger.addClass( 'dl-active' );
            this.open = true;

            //create overlay
            $('body').append('<div id="menu-overlay" />');

        },

        // resets the menu to its original state (first level of options)
        _resetMenu : function() {

            this.$menu.removeClass( 'dl-subview' );
            this.$menuitems.removeClass( 'dl-subview dl-subviewopen' );

        },

        destroy: function() {

            this.$el.unbind("destroyed", this.teardown);
            this.teardown();

        },

        // set back our element
        teardown: function() {

            // roll back changes
            this.$menu.removeClass( 'dl-menuopen dl-menu-toggle' );
            this.$trigger.removeClass( 'dl-active' ).off();
            this._closeMenu();
            this.$el.find( 'ul.sub-menu .dl-back' ).remove();
            this.$el.removeClass(this.name);
            this.$menuitems.off();
            this.$back.off();
            this.$el.removeData();
            this.$el = null;

        }

    };

    var logError = function( message ) {

        if ( window.console ) {
            window.console.error( message );
        }

    };

    $.fn.dlmenu = function( options ) {
        if ( typeof options === 'string' ) {
            var args = Array.prototype.slice.call( arguments, 1 );
            this.each(function() {
                var instance = $.data( this, 'dlmenu' );
                if ( !instance ) {
                    logError( "cannot call methods on dlmenu prior to initialization; " +
                    "attempted to call method '" + options + "'" );
                    return;
                }
                if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
                    logError( "no such method '" + options + "' for dlmenu instance" );
                    return;
                }
                instance[ options ].apply( instance, args );
            });
        }
        else {
            this.each(function() {
                var instance = $.data( this, 'dlmenu' );
                if ( instance ) {
                    instance._init();
                } else {
                    instance = $.data( this, 'dlmenu', new $.DLMenu( options, this ) );
                }
            });
        }
        return this;
    };

})( jQuery, window );