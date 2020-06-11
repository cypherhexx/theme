// --------------------------------------------------------------------------------------------------
// Mobile Navigation
// @author Stephen Greig
// --------------------------------------------------------------------------------------------------
//SVG Icons
var iconClose = "<svg class='icon icon-times'><use xlink:href='"+themeURL+"/assets/img/icons-sprite.svg#icon-times'></use></svg>";
var iconAngleUp = "<svg class='icon icon-angle-up'><use xlink:href='"+themeURL+"/assets/img/icons-sprite.svg#icon-angle-up'></use></svg>";
var iconAngleDown = "<svg class='icon icon-angle-down'><use xlink:href='"+themeURL+"/assets/img/icons-sprite.svg#icon-angle-down'></use></svg>";
        
// Copy primary and secondary menus to .mob-nav element
var mobNav = document.querySelector('.mob-nav .scroll-container');

if($('.desktop-nav .main-menu').length > 0) {
    var copyPrimaryMenu = document.querySelector('.desktop-nav .main-menu').cloneNode(true);
    mobNav.appendChild(copyPrimaryMenu);
}

// Add Close Icon element
$( "<div class='mob-nav-close'>"+iconClose+"</div>" ).insertAfter( ".mob-nav .scroll-container" );

// Add dropdown arrow to links with sub-menus
$( "<span class='sub-arrow'>"+iconAngleDown+iconAngleUp+"</span>" ).insertBefore(".mob-nav .sub-menu");

// Show sub-menu when dropdown arrow is clicked
$('.sub-arrow').click(function() {
    $(this).toggleClass('active');
    $(this).prev('a').toggleClass('active');
    $(this).next('.sub-menu').slideToggle();
    $(this).children().toggle();
});

// Add underlay element after mobile nav
$( "<div class='mob-nav-underlay'></div>" ).insertAfter( ".mob-nav" );

// Show underlay and fix the body scroll when menu button is clicked
$('.mob-nav-toggle').click(function() {
    $('.mob-nav,.mob-nav-underlay').addClass('active');
    $('body').addClass('fixed');
});

// Hide menu when close icon or underlay is clicked
$('.mob-nav-underlay,.mob-nav-close').click(function() {
    $('.mob-nav,.mob-nav-underlay').removeClass('active');
    $('body').removeClass('fixed');
});