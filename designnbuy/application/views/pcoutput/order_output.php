<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Order Output</title>
	<link href="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/css/style.css'; ?>" rel="stylesheet" type="text/css" />
	<script src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/javascript/javascript/plugins/jquery-2.1.1.min.js'; ?>"></script>	
	<link rel="stylesheet" type="text/css" href="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/css/swiper.min.css'; ?>" media="screen" />	
    </head>
    <body>
	<div class="board_main">
	    <div class="board-lftcontten output">
		<h1>Print Ready Output</h1>

		<div class="order-div">
		    <h2>Order Details</h2>
		    <div class="order-details_box" style="margin-bottom:30px;">
			<div class="details_box_text"><span>Date: </span><?php echo $order['date_added']; ?></div>
			<div class="details_box_text"><span>Customer: </span><?php echo $order['firstname'] . ' ' . $order['lastname']; ?></div>
			<div class="details_box_text"><span>No of Items: </span><?php echo count($orderproducts); ?></div>
			<div class="details_box_text"><span>Total Amount: </span><?php echo $order['total']; ?></div>
		    </div>
		</div>

		<?php if (!empty($orderproducts)) { ?>
		    <?php foreach ($orderproducts as $orderproduct) { ?>
			<?php
			$imgDir = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'orderimages' . DIRECTORY_SEPARATOR . $orderproduct['designed_id'] . DIRECTORY_SEPARATOR;
			$imagepath = $siteBaseUrl . 'designnbuy/assets/images/orderimages/' . $orderproduct['designed_id'] . '/';
			$outputfilepath = $siteBaseUrl . 'designnbuy/assets/images/output/' . $orderproduct['designed_id'] . '/' . $orderproduct['output_file'];
			?>
			<div class="part_board">
			    <div class="only-prod">
				<h2><?php echo $orderproduct['product_name']; ?></h2>
				<a class="button" href="<?php echo $outputfilepath; ?>" download>Download Print Ready Files</a>
				<div class="prod_box">
				    <div class="prod_box_lft">
					<div class="prod_box_text"><span>Printing Method: </span><?php echo $orderproduct['printing_method_name']; ?></div>
					<?php if(PLATEFORM == 'prestashop') { ?>
					    <?php echo $orderproduct['option_data']; ?>
					<?php } else { ?>
					    <?php if (!empty($orderproduct['option_data'])) { ?>
					    <div class="prod_box_text"><?php foreach ($orderproduct['option_data'] as $option) { ?>	    				   
							<span><?php echo $option['name']; ?>: </span><?php echo $option['value']; ?>

							<br />
						    <?php } ?>

					    </div>
					    <?php } ?>
					<?php } ?>
				   <!--<div class="prod_box_text"><span>Size: </span>25</div>-->
				    </div>
				    <div class="prod_box_text"><span>Notes: </span><?php echo $orderproduct['notes']; ?><div class="prod_box_text">
				</div>
			    </div>


			    <div class="popupside">
				<div class="pn_contten">


				</div>

			    </div>
			    <div class="part_board_slide">
				<h3>Design Preview</h3>
				<div data-role="page" id="product_detail_page">
				    <!-- /panel -->  
				    <div class="product-big-box">
					<div class="product-big-swiper swiper-container">
					    <div class="swiper-wrapper">
						<?php if (file_exists($imgDir . $orderproduct['side1_png']) && getimagesize($imgDir . $orderproduct['side1_png'])) { ?>
	    					<div class="swiper-slide">
	    					    <div class="product-big-image"> 
	    						<a href="#">
	    						    <img src="<?php echo $imagepath . $orderproduct['side1_png']; ?>" />
	    						</a>
	    					    </div>
	    					</div>
						<?php } ?>
						<?php if (file_exists($imgDir . $orderproduct['side2_png']) && getimagesize($imgDir . $orderproduct['side2_png'])) { ?>
	    					<div class="swiper-slide">
	    					    <div class="product-big-image"> 
	    						<a href="#">
	    						    <img src="<?php echo $imagepath . $orderproduct['side2_png']; ?>" />
	    						</a>
	    					    </div>
	    					</div>
						<?php } ?>
						<?php if (file_exists($imgDir . $orderproduct['side3_png']) && getimagesize($imgDir . $orderproduct['side3_png'])) { ?>
	    					<div class="swiper-slide">
	    					    <div class="product-big-image"> 
	    						<a href="#">
	    						    <img src="<?php echo $imagepath . $orderproduct['side3_png']; ?>" />
	    						</a>
	    					    </div>
	    					</div>
						<?php } ?>
						<?php if (file_exists($imgDir . $orderproduct['side4_png']) && getimagesize($imgDir . $orderproduct['side4_png'])) { ?>
	    					<div class="swiper-slide">
	    					    <div class="product-big-image"> 
	    						<a href="#">
	    						    <img src="<?php echo $imagepath . $orderproduct['side4_png']; ?>" />
	    						</a>
	    					    </div>
	    					</div>
						<?php } ?>
						<?php if (file_exists($imgDir . $orderproduct['side5_png']) && getimagesize($imgDir . $orderproduct['side5_png'])) { ?>
	    					<div class="swiper-slide">
	    					    <div class="product-big-image"> 
	    						<a href="#">
	    						    <img src="<?php echo $imagepath . $orderproduct['side5_png']; ?>" />
	    						</a>
	    					    </div>
	    					</div>
						<?php } ?>
						<?php if (file_exists($imgDir . $orderproduct['side6_png']) && getimagesize($imgDir . $orderproduct['side6_png'])) { ?>
	    					<div class="swiper-slide">
	    					    <div class="product-big-image"> 
	    						<a href="#">
	    						    <img src="<?php echo $imagepath . $orderproduct['side6_png']; ?>" />
	    						</a>
	    					    </div>
	    					</div>
						<?php } ?>
						<?php if (file_exists($imgDir . $orderproduct['side7_png']) && getimagesize($imgDir . $orderproduct['side7_png'])) { ?>
	    					<div class="swiper-slide">
	    					    <div class="product-big-image"> 
	    						<a href="#">
	    						    <img src="<?php echo $imagepath . $orderproduct['side7_png']; ?>" />
	    						</a>
	    					    </div>
	    					</div>
						<?php } ?>
						<?php if (file_exists($imgDir . $orderproduct['side8_png']) && getimagesize($imgDir . $orderproduct['side8_png'])) { ?>
	    					<div class="swiper-slide">
	    					    <div class="product-big-image"> 
	    						<a href="#">
	    						    <img src="<?php echo $imagepath . $orderproduct['side8_png']; ?>" />
	    						</a>
	    					    </div>
	    					</div>
						<?php } ?>
					    </div>
					    <div class="swiper-pagination"></div>
					    <div class="swiper-button-next"></div>
					    <div class="swiper-button-prev"></div>
					</div>
					<!--	<div class="product-thumb">
						    <div class=" swiper-container">
							<div class="swiper-wrapper">
					<?php if (file_exists($imgDir . $orderproduct['side1_png']) && getimagesize($imgDir . $orderproduct['side1_png'])) { ?>
	    						    <div class="swiper-slide">
	    							<span class="thumb">
	    							    <a href="#">
	    								<img src="<?php echo $imagepath . $orderproduct['side1_png']; ?>" height="60px" width="55px" />
	    							    </a>
	    							</span>
	    						    </div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side2_png']) && getimagesize($imgDir . $orderproduct['side2_png'])) { ?>
	    						    <div class="swiper-slide">
	    							<span class="thumb">
	    							    <a href="#">
	    								<img src="<?php echo $imagepath . $orderproduct['side2_png']; ?>" height="60px" width="55px" />
	    							    </a>
	    							</span>
	    						    </div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side3_png']) && getimagesize($imgDir . $orderproduct['side3_png'])) { ?>
	    						    <div class="swiper-slide">
	    							<span class="thumb">
	    							    <a href="#">
	    								<img src="<?php echo $imagepath . $orderproduct['side3_png']; ?>" height="60px" width="55px" />
	    							    </a>
	    							</span>
	    						    </div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side4_png']) && getimagesize($imgDir . $orderproduct['side4_png'])) { ?>
	    						    <div class="swiper-slide">
	    							<span class="thumb">
	    							    <a href="#">
	    								<img src="<?php echo $imagepath . $orderproduct['side4_png']; ?>" height="60px" width="55px" />
	    							    </a>
	    							</span>
	    						    </div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side5_png']) && getimagesize($imgDir . $orderproduct['side5_png'])) { ?>
	    						    <div class="swiper-slide">
	    							<span class="thumb">
	    							    <a href="#">
	    								<img src="<?php echo $imagepath . $orderproduct['side5_png']; ?>" height="60px" width="55px" />
	    							    </a>
	    							</span>
	    						    </div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side6_png']) && getimagesize($imgDir . $orderproduct['side6_png'])) { ?>
	    						    <div class="swiper-slide">
	    							<span class="thumb">
	    							    <a href="#">
	    								<img src="<?php echo $imagepath . $orderproduct['side6_png']; ?>" height="60px" width="55px" />
	    							    </a>
	    							</span>
	    						    </div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side7_png']) && getimagesize($imgDir . $orderproduct['side7_png'])) { ?>
	    						    <div class="swiper-slide">
	    							<span class="thumb">
	    							    <a href="#">
	    								<img src="<?php echo $imagepath . $orderproduct['side7_png']; ?>" height="60px" width="55px" />
	    							    </a>
	    							</span>
	    						    </div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side8_png']) && getimagesize($imgDir . $orderproduct['side8_png'])) { ?>
	    						    <div class="swiper-slide">
	    							<span class="thumb">
	    							    <a href="#">
	    								<img src="<?php echo $imagepath . $orderproduct['side8_png']; ?>" height="60px" width="55px" />
	    							    </a>
	    							</span>
	    						    </div>
					<?php } ?>
							</div>
						    </div>
						</div> -->
				    </div>
				</div>
			    </div>
			</div>
			<div class="clear">&nbsp;</div>
		    <?php } ?>
		<?php } ?>
		<script src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/js/swiper.min.js'; ?>"></script>
		<script>
		    $(document).ready(function(e) {
			init_prod_detail();			
		    });
		   
		    function init_prod_detail(){		
			var swiper = new Swiper('.swiper-container');  
			  var page = [];
			$('.product-big-swiper').each(function(index, element){
			    var $el = $(this);
			    page[index] = $el.swiper({
				calculateHeight:true,
				calculateWidth:true,
				autoResize:true,
				pagination:  $el.find('.swiper-pagination')[0],
				paginationClickable: true,
				//nextButton: '.swiper-button-next',
				//prevButton: '.swiper-button-prev',
				spaceBetween: 30
			    });
			    
			    $el.find('.swiper-button-prev').on('click', function(){
				page[index].slidePrev();
			    });

			    $el.find('.swiper-button-next').on('click', function(){
				page[index].slideNext();
			    });
							
			});		
		    }				
		</script>
	    </div>
	</div>

    </body>
</html>
