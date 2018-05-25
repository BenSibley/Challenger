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