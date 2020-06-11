// Category Filters
$('.blog-filters__input').change(function(){

	if ($(this).is(':checked')) {
		$(this).parent().addClass('active');
		$(this).parent().siblings().removeClass('active');
	}

	$('.blog-load-more').hide();

	var filter = $('.blog-filters');
	$.ajax({
		url:filter.attr('action'),
		data:filter.serialize(), // form data
		type:filter.attr('method'), // POST
		beforeSend:function(xhr){
			$('.blog-filters').addClass('active');
			$('.post-list').css('opacity','0');
			$('.post-list').css('transform','translateY(100px)');
		},
		success:function(data){
			$('.post-list').html(data);
			$('.post-list').css('opacity','1');
			$('.post-list').css('transform','translateY(0)');
			$('.blog-filters').removeClass('active');
		}
	});
	return false;
});


// Load more
$('.blog-load-more').click(function(){

	var button = $(this),
		data = {
		'action': 'loadmore',
		'query': posts_loadmore,
		'page' : current_page_loadmore
	};

	$.ajax({
		url : '/wp-admin/admin-ajax.php',
		data : data,
		type : 'POST',
		beforeSend : function ( xhr ) {
			button.text('Loading...');
		},
		success : function( data ){
			if( data ) { 
				button.text( 'View more' ).parent().prev().find('.post-list').append(data);
				current_page_loadmore++;

				if ( current_page_loadmore == max_page_loadmore ) 
					button.remove();
			} else {
				button.remove();
			}
		}
	});
});