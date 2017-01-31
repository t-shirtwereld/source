jQuery(document).ready(function() {
	jQuery(".various").fancybox({
		maxWidth	: '75%',
		maxHeight	: 500,
		fitToView	: false,
		width		: '100%',
		height		: '100%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	
	jQuery(".message_board").fancybox({
	        type		: 'iframe',
		maxWidth	: '75%',
		maxHeight	: 500,
		fitToView	: false,
		width		: '100%',
		height		: '100%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
});
jQuery.noConflict();