/*global jQuery */
/*jshint browser:true */
/*!
 * FitVids 1.1
 *
 * Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
 * Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
 * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
 *
 */

;(function( $ ){

    'use strict';

    $.fn.fitVids = function( options ) {
        var settings = {
            customSelector: null,
            ignore: null
        };

        if(!document.getElementById('fit-vids-style')) {
            // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
            var head = document.head || document.getElementsByTagName('head')[0];
            var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
            var div = document.createElement("div");
            div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
            head.appendChild(div.childNodes[1]);
        }

        if ( options ) {
            $.extend( settings, options );
        }

        return this.each(function(){
            var selectors = [
                'iframe[src*="player.vimeo.com"]',
                'iframe[src*="youtube.com"]',
                'iframe[src*="youtube-nocookie.com"]',
                'iframe[src*="kickstarter.com"][src*="video.html"]',
                'object',
                'embed'
            ];

            if (settings.customSelector) {
                selectors.push(settings.customSelector);
            }

            var ignoreList = '.fitvidsignore';

            if(settings.ignore) {
                ignoreList = ignoreList + ', ' + settings.ignore;
            }

            var $allVideos = $(this).find(selectors.join(','));
            $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
            $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

            $allVideos.each(function(){
                var $this = $(this);
                if($this.parents(ignoreList).length > 0) {
                    return; // Disable FitVids on this video.
                }
                if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
                if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
                {
                    $this.attr('height', 9);
                    $this.attr('width', 16);
                }
                var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
                    width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
                    aspectRatio = height / width;
                if(!$this.attr('id')){
                    var videoID = 'fitvid' + Math.floor(Math.random()*999999);
                    $this.attr('id', videoID);
                }
                $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
                $this.removeAttr('height').removeAttr('width');
            });
        });
    };
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );
jQuery(document).ready(function($){

    var body = $('body');
    var siteHeader = $('#site-header');
    var titleContainer = $('#title-container');
    var toggleNavigation = $('#toggle-navigation');
    var menuPrimaryContainer = $('#menu-primary-container');
    var menuPrimary = $('#menu-primary');
    var menuPrimaryItems = $('#menu-primary-items').length ? $('#menu-primary-items') : $('.menu-unset > ul');
    var toggleDropdown = $('.toggle-dropdown');
    //var toggleSidebar = $('#toggle-sidebar');
    //var sidebarPrimary = $('#sidebar-primary');
    //var sidebarPrimaryContent = $('#sidebar-primary-content');
    //var sidebarWidgets = $('#sidebar-primary-widgets');
    //var socialMediaIcons = siteHeader.find('.social-media-icons');
    var menuLink = $('.menu-item').children('a');

    objectFitAdjustment();

    toggleNavigation.on('click', openPrimaryMenu);

    $('.post-content').fitVids({
        customSelector: 'iframe[src*="dailymotion.com"], iframe[src*="slideshare.net"], iframe[src*="animoto.com"], iframe[src*="blip.tv"], iframe[src*="funnyordie.com"], iframe[src*="hulu.com"], iframe[src*="ted.com"], iframe[src*="wordpress.tv"]'
    });

    $(window).resize(function(){
        keepDropdownsVisible();
        objectFitAdjustment();
        
        if ( window.innerWidth > 799 ) {
            if ( menuPrimaryContainer.hasClass('open') ) {
                menuPrimaryContainer.css('padding-top', 0);
                menuPrimaryContainer.removeClass('open');
                toggleNavigation.removeClass('open');
                body.css('overflow', 'auto');
                siteHeader.removeClass('open');
            }
        }
    });

    // Jetpack infinite scroll event that reloads posts.
    $( document.body ).on( 'post-load', function () {
        objectFitAdjustment();
    } );

    function openPrimaryMenu() {

        if( menuPrimaryContainer.hasClass('open') ) {
            siteHeader.removeClass('open');
            menuPrimaryContainer.removeClass('open');
            $(this).removeClass('open');
            body.css('overflow', 'auto');
            $('.overflow-container').css('position', 'static');

            // change screen reader text
            $(this).children('span').text(objectL10n.openMenu);

            // change aria text
            $(this).attr('aria-expanded', 'false');

            menuPrimaryItems.find('li').removeClass('visible');
            
            setTimeout( function(){ 
                menuPrimaryContainer.css('margin-top', 0);
            }, 500)

        } else {
            siteHeader.addClass('open');
            menuPrimaryContainer.addClass('open');
            $(this).addClass('open');
            body.css('overflow', 'hidden');
            $('.overflow-container').css('position', 'fixed');
            $('#menu-overflow-cover').css('height', parseInt($('#title-container').offset().top + $('#title-container').height() + 12) + 'px' );

            // change screen reader text
            $(this).children('span').text(objectL10n.closeMenu);

            // change aria text
            $(this).attr('aria-expanded', 'true');

            var marginTop = siteHeader.outerHeight();
            if ( body.hasClass('admin-bar') ) {
                if ( window.innerWidth < 783 ) {
                    marginTop += 46;
                } else {
                    marginTop += 32;
                }   
            }
            if ( body.hasClass('has-header-box') ) {
                marginTop -= $('#header-box').outerHeight(true);
            }
            menuPrimaryContainer.css('margin-top', marginTop + 'px');

            var delay = 200/menuPrimaryItems.children().length;;
            var currentDelay = 75
            menuPrimaryItems.find('li').each(function() {
                const li = $(this);
                setTimeout( function(){ 
                    li.addClass('visible');
                }, currentDelay)
                currentDelay += delay;
            });
        }
    }

    // If the right side of a dropdown menu is to the right of the menu primary container, add the flipped class
    function keepDropdownsVisible() {

        if ( window.innerWidth > 799 ) {
            const submenus = menuPrimary.find('.sub-menu');
            submenus.each(function () {
                if ( $(this).offset().left + $(this).width() > window.innerWidth ) {
                    $(this).addClass('flipped');
                } else {
                    $(this).removeClass('flipped');
                }
            });
        }
    }
    keepDropdownsVisible();

    /* allow keyboard access/visibility for dropdown menu items */
    menuLink.focus(function(){
        $(this).parents('ul').addClass('focused');
    });
    menuLink.focusout(function(){
        $(this).parents('ul').removeClass('focused');
    });

    // mimic cover positioning without using cover
    function objectFitAdjustment() {

        // if the object-fit property is not supported
        if( !('object-fit' in document.body.style) ) {

            $('.featured-image').each(function () {

                if ( !$(this).parent().parent('.post').hasClass('ratio-natural') ) {

                    var image = $(this).children('img').add($(this).children('a').children('img'));

                    // don't process images twice (relevant when using infinite scroll)
                    if ( image.hasClass('no-object-fit') ) {
                        return;
                    }

                    image.addClass('no-object-fit');

                    // if the image is not wide enough to fill the space
                    if (image.outerWidth() < $(this).outerWidth()) {

                        image.css({
                            'width': '100%',
                            'min-width': '100%',
                            'max-width': '100%',
                            'height': 'auto',
                            'min-height': '100%',
                            'max-height': 'none'
                        });
                    }
                    // if the image is not tall enough to fill the space
                    if (image.outerHeight() < $(this).outerHeight()) {

                        image.css({
                            'height': '100%',
                            'min-height': '100%',
                            'max-height': '100%',
                            'width': 'auto',
                            'min-width': '100%',
                            'max-width': 'none'
                        });
                    }
                }
            });
        }
    }

    const parentMenuItems = menuPrimaryContainer.find('.menu-item-has-children, .page_item_has_children');
    var openMenu = false;
    if (window.innerWidth > 799) {  
        $(window).on('touchstart', tabletSubMenus);
    }
    function tabletSubMenus() {
        $(window).off('touchstart', tabletSubMenus);
        parentMenuItems.on('click', openDropdown);
        $(document).on('touchstart', (function(e) {
            if ( openMenu ) {
                if ($(e.target).parents('.menu-primary').length == 0) {
                    parentMenuItems.removeClass('menu-open');
                    openMenu = false
                }
            }
        }));
    }
    function openDropdown(e){
        if (!$(this).hasClass('menu-open')){
            e.preventDefault();
            $(this).addClass('menu-open');
            openMenu = true;
        }
    }
});

/* fix for skip-to-content link bug in Chrome & IE9 */
window.addEventListener("hashchange", function(event) {

    var element = document.getElementById(location.hash.substring(1));

    if (element) {

        if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
            element.tabIndex = -1;
        }

        element.focus();
    }

}, false);