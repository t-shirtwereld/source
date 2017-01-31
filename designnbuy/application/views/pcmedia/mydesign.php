<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
	<title><?php echo $language['mydesign']; ?></title>
	<link href="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/css/style.css'; ?>" rel="stylesheet" type="text/css" />
	<script src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/javascript/javascript/plugins/jquery-2.1.1.min.js'; ?>"></script>	
	<link rel="stylesheet" type="text/css" href="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/css/swiper.min.css'; ?>" media="screen" />	   
    </head>
    <body>
	<?php if (!empty($designs)) { ?>
	    <?php $i = 1; ?>
	    <?php foreach ($designs as $design) { ?>
		<?php
		$imgDir = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'saveimg' . DIRECTORY_SEPARATOR . $design['designed_id'] . DIRECTORY_SEPARATOR;
		$imagepath = $siteBaseUrl . 'designnbuy/assets/images/saveimg/' . $design['designed_id'] . '/';
		?>

		<div class="design_frame" id="design-<?php echo $design['my_design_id']; ?>">
		    <div class="my-dsgn">
			<h2><?php echo $design['design_name']; ?></h2>
			<h3><?php echo $design['product_name']; ?></h3>
			<div class="top_link">
			    <a target="_parent" href="<?php echo $siteBaseUrl . $mydesign_plateform_path . $design['my_design_id']; ?>"><img src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/images/edit.svg'; ?>" alt="" /></a>&nbsp;
			    <a class="delete" href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/deleteMydesign/' . $design['my_design_id']; ?>"><img src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/images/delete.svg'; ?>" alt="" /></a>
			</div>
			<div id="product_detail_page">
			    <div class="product-big-box">
				<div class="product-big-swiper swiper-container">
				    <div class="swiper-wrapper">
					<?php if (file_exists($imgDir . $design['side1_png']) && getimagesize($imgDir . $design['side1_png'])) { ?>
	    				<div class="swiper-slide">
	    				    <div class="product-big-image"> 
	    					<a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $design['side1_png']; ?>">
	    					    <img src="<?php echo $imagepath . $design['side1_png']; ?>" />
	    					</a>
	    				    </div>
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $design['side2_png']) && getimagesize($imgDir . $design['side2_png'])) { ?>
	    				<div class="swiper-slide">
	    				    <div class="product-big-image"> 
	    					<a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $design['side2_png']; ?>">
	    					    <img src="<?php echo $imagepath . $design['side2_png']; ?>" />
	    					</a>
	    				    </div>
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $design['side3_png']) && getimagesize($imgDir . $design['side3_png'])) { ?>
	    				<div class="swiper-slide">
	    				    <div class="product-big-image"> 
	    					<a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $design['side3_png']; ?>">
	    					    <img src="<?php echo $imagepath . $design['side3_png']; ?>" />
	    					</a>
	    				    </div>
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $design['side4_png']) && getimagesize($imgDir . $design['side4_png'])) { ?>
	    				<div class="swiper-slide">
	    				    <div class="product-big-image"> 
	    					<a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $design['side4_png']; ?>">
	    					    <img src="<?php echo $imagepath . $design['side4_png']; ?>" />
	    					</a>
	    				    </div>
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $design['side5_png']) && getimagesize($imgDir . $design['side5_png'])) { ?>
	    				<div class="swiper-slide">
	    				    <div class="product-big-image"> 
	    					<a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $design['side5_png']; ?>">
	    					    <img src="<?php echo $imagepath . $design['side5_png']; ?>" />
	    					</a>
	    				    </div>
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $design['side6_png']) && getimagesize($imgDir . $design['side6_png'])) { ?>
	    				<div class="swiper-slide">
	    				    <div class="product-big-image"> 
	    					<a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $design['side6_png']; ?>">
	    					    <img src="<?php echo $imagepath . $design['side6_png']; ?>" />
	    					</a>
	    				    </div>
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $design['side7_png']) && getimagesize($imgDir . $design['side7_png'])) { ?>
	    				<div class="swiper-slide">
	    				    <div class="product-big-image"> 
	    					<a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $design['side7_png']; ?>">
	    					    <img src="<?php echo $imagepath . $design['side7_png']; ?>" />
	    					</a>
	    				    </div>
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $design['side8_png']) && getimagesize($imgDir . $design['side8_png'])) { ?>
	    				<div class="swiper-slide">
	    				    <div class="product-big-image"> 
	    					<a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $design['side8_png']; ?>">
	    					    <img src="<?php echo $imagepath . $design['side8_png']; ?>" />
	    					</a>
	    				    </div>
	    				</div>
					<?php } ?>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
		</div>
		<?php if ($i % 3 == 0) { ?>
	    	<div class="clear">&nbsp;</div>
	    	<div class="bottom-border">&nbsp;</div>
	    	<div class="clear">&nbsp;</div>
		<?php } ?>
		<?php $i++; ?>
	    <?php } ?>
	<?php } ?>
	<script src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/js/swiper.min.js'; ?>"></script>
	<script>
	    var swiper = new Swiper('.swiper-container');  
	</script>
	<script type="text/javascript" src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/source/jquery.fancybox.js'; ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/source/jquery.fancybox.css'; ?>" media="screen" />
	<script>
	    $( document ).ready(function() {
		var sure = "<?php echo $language['areyousure']; ?>";
		var deleterecord = "<?php echo $language['errodeleterecord']; ?>";
		$("a.delete").click(function(event){
		    event.preventDefault();
		    if (confirm(sure)) {
			var parent = $(this).parent().parent();
			var href = $(this).attr("href");
			$.ajax({
			    type: "GET",
			    url: href,
			    success: function(response) {		    
				if (response === "true") {
				    var c1 = parent.next("div").attr("class");
				    var c2 = parent.prev("div").attr("class");
				    if(c1 === 'clear' && c2 === 'clear') {
					parent.next(".clear").remove();
					parent.next(".bottom-border").remove();
					parent.next(".clear").remove();				       
				    }
				    parent.fadeOut(1000,function() { parent.remove(); }); 
				} else {
				    alert(deleterecord);
				}		    
			    }
			});
		    } 
		    return false;			
		});	
	    }); 
	</script>
	<script>
	    $(document).ready(function() {
		$(".fancybox").fancybox({
		    openMethod 	: 'zoomIn',
		    closeMethod : 'zoomOut',
		    nextEffect : 'fade',
		    prevEffect : 'fade', 
		    arrows : 'true',
		    scrolling: 'no',
		    width: 'auto',
		    height: 'auto',
		    centerOnScroll: false,
		    resizeOnWindowResize : false
		});
	    }); 
	</script>
    </body>
</html>
