// --------------------------------------------------------------------------------------------------
// Trigger Animations when in viewport
// --------------------------------------------------------------------------------------------------

;(function($, win) {
	$.fn.inViewport = function(cb) {
		return this.each(function(i,el) {
			function visPx(){
			var elH = $(el).outerHeight(),
				H = $(win).height(),
				r = el.getBoundingClientRect(), t=r.top, b=r.bottom;
			return cb.call(el, Math.max(0, t>0? Math.min(elH, H-t) : Math.min(b, H)));  
			}
			visPx();
			$(win).on("resize scroll", visPx);
		});
	};
}(jQuery, window));


$("[data-animate]").inViewport(function(px) {
	//console.log( px ); // `px` represents the amount of visible height
    var anim = $(this).data('animate');
    
    var elHeight = $(this).height();

    var thirtyPercent = (elHeight/100)*30;

	if(px > thirtyPercent) {
        $(this).css('opacity','1');
		$(this).addClass(anim);
	}
});