<!doctype html>
<html>
    <head>
	<meta charset="utf-8">
	<?php if (isset($title) && $title != '') { ?>
    	<title><?php echo $title; ?></title>
	<?php } else { ?>
    	<title><?php echo "Admin Panel"; ?></title>
	<?php } ?>

	<title><?php echo $title; ?></title>	
	<link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/css/switch_button.css'); ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.2.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/additional-methods.min.js'); ?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery-ui.css'); ?>">
	<script src="<?php echo base_url('assets/js/jquery-ui.js'); ?>"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/swfobject.js'); ?>"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/uploadify.min.js'); ?>"></script>
	<script type="text/javascript">
	var iframeurl = '<?php echo get_base_url()."designnbuy"; ?>';
	var baseurl = '<?php echo get_base_url(); ?>';
	var addressurl = top.location.href;
	
	if(addressurl.indexOf(iframeurl) > -1){
		window.stop();
		window.document.execCommand('Stop');
		console.clear();
		location.href = baseurl;
	}
	    $(document).ready(function() {
		
		$(".tabs-menu a").click(function(event) {
		    event.preventDefault();
		    $(this).parent().addClass("current");
		    $(this).parent().siblings().removeClass("current");
		    var tab = $(this).attr("href");
		    $(".tab-content").not(tab).css("display", "none");
		    $(tab).fadeIn();
		});
		
		
	    });

	</script>
    </head>
    <body>
	<?php $active = $this->router->fetch_class(); ?>
	<!-- Header -->
	<div class="pc_menu">
	    <ul class="main-nav">
		<li <?php if ($active == 'cliparts' || $active == 'clipart_categories'): ?>class="active" <?php endif; ?>>
		    <a href="#">Clipart</a>
		    <ul>
			<li><a href="<?php echo BASE_ADMIN_URL . 'clipart_categories'; ?>">Clipart Categories</a></li>
			<li><a href="<?php echo BASE_ADMIN_URL . 'cliparts'; ?>">Clipart</a></li>
		    </ul>
		</li>
		<li <?php if ($active == 'fonts'): ?>class="active" <?php endif; ?>><a href="<?php echo BASE_ADMIN_URL . 'fonts'; ?>">Fonts</a></li>
		<li <?php if ($active == 'printablecolors' || $active == 'printablecolor_categories'): ?>class="active" <?php endif; ?>>
		    <a href="#">Printable Colors</a>
		    <ul>
			<li><a href="<?php echo BASE_ADMIN_URL . 'printablecolor_categories'; ?>">Color Categories</a></li>
			<li><a href="<?php echo BASE_ADMIN_URL . 'printablecolors'; ?>">Printable Colors</a></li>
		    </ul>
		</li>
		<li <?php if ($active == 'color_counters' || $active == 'sqarea' || $active == 'qranges' || $active == 'printingmethods'): ?>class="active" <?php endif; ?>>
		    <a href="#">Printing Methods</a>
		    <ul>
			<li>
			    <a href="#">Pricing Parameters</a>
			    <ul>
				<li><a href="<?php echo BASE_ADMIN_URL . 'color_counters'; ?>">Color Counter</a></li>
				<li><a href="<?php echo BASE_ADMIN_URL . 'sqarea'; ?>">Artwork Area Size</a></li>
				<li><a href="<?php echo BASE_ADMIN_URL . 'qranges'; ?>">Quantity Range</a></li>
			    </ul>
			</li>
			<li><a href="<?php echo BASE_ADMIN_URL . 'printingmethods'; ?>">Printing Methods</a></li>
		    </ul>
		</li>
		<li <?php if ($active == 'products'): ?>class="active" <?php endif; ?> ><a href="<?php echo BASE_ADMIN_URL . 'products'; ?>">Products</a></li>
		<li <?php if ($active == 'settings' || $active == 'language'): ?>class="active" <?php endif; ?>>
		    <a href="#">Settings</a>
		    <ul>
			<li><a href="<?php echo BASE_ADMIN_URL . 'settings'; ?>">General Configuration</a></li>
			<li><a href="<?php echo BASE_ADMIN_URL . 'settings/social_media'; ?>">Social Media Configuration</a></li>
			<li><a href="<?php echo BASE_ADMIN_URL . 'settings/configure_feature'; ?>">Design Studio Configuration</a></li>
			<!--<li><a href="<?php echo BASE_ADMIN_URL . 'settings/product_advance_configuration'; ?>">Product Advance Configuration</a></li>-->
			<li><a href="<?php echo BASE_ADMIN_URL . 'language'; ?>">Languages</a></li>
			<li><a href="<?php echo BASE_ADMIN_URL . 'settings/multilanguage'; ?>">Labels and Messages</a></li>
			<!-- <li><a href="<?php echo BASE_ADMIN_URL . 'settings/personalizer'; ?>">Personalize Design Studio</a></li> -->
			<li><a href="<?php echo BASE_ADMIN_URL . 'settings/help_data'; ?>">Help Content</a></li>
		    </ul>
		</li>
	    </ul>
	</div>
	<!-- End Header -->


	<?php echo $content_for_layout; ?>
	<script>
	    $( document ).ready(function() {
		$(".delete").click(function(event){
		    event.preventDefault();	
		    var row = this;
		    if (confirm("Are you sure?") == true) {
			var href = $(this).attr("href");
			$.ajax({
			    type: "GET",
			    url: href,
			    success: function(response) {
				if (response === "true") {
				    $('.pc_search').after('<div id="messages_product_view"><ul class="messages"><li class="success-msg">Entry Deleted Successfully...!</li></ul></div>');
				    $(row).closest("tr").css("background-color", "#faebe7");
				    $(row).closest("tr").delay(500).fadeOut(2000);
				    $('#messages_product_view').delay(1000).fadeOut(5000);			
				} else {
				    alert("Error");
				}
			    }
			});	
		    }
		});
	    });
	</script>
    </body>
</html>