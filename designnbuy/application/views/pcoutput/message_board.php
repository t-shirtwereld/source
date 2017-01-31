<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
	<title><?php echo $language['messageboard']; ?></title>
	<link href="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/css/style.css'; ?>" rel="stylesheet" type="text/css" />
	<script src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/javascript/javascript/plugins/jquery-2.1.1.min.js'; ?>"></script>	
	<link rel="stylesheet" type="text/css" href="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/css/swiper.min.css'; ?>" media="screen" />
    </head>
    <body>
	<div class="board_main">
	    <div class="board-lft">
		<div class="board-lftcontten">
		    <h1><?php echo $language['messageboard']; ?></h1>

		    <div class="order-div">
			<h2><?php echo $language['orderdetails']; ?></h2>
			<div class="order-details_box">
			    <div class="details_box_text"><span><?php echo $language['date']; ?>: </span><?php echo $order['date_added']; ?></div>
			    <div class="details_box_text"><span><?php echo $language['customer']; ?>: </span><?php echo ucfirst($order['firstname']) . ' ' . ucfirst($order['lastname']); ?></div>
			    <div class="details_box_text"><span><?php echo $language['noofitems']; ?>: </span><?php echo count($orderproducts); ?></div>
			    <div class="details_box_text"><span><?php echo $language['totalamount']; ?>: </span><?php echo $order['total']; ?></div>
			</div>

			<?php if (!empty($orderproducts)) { ?>
			    <?php $j = 1; ?>
			    <?php foreach ($orderproducts as $orderproduct) { ?>
				<div style="padding-bottom:20px;"> 
				    <div class="order-img">
					<?php
					$imgDir = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'orderimages' . DIRECTORY_SEPARATOR . $orderproduct['designed_id'] . DIRECTORY_SEPARATOR;
					$imagepath = $siteBaseUrl . 'designnbuy/assets/images/orderimages/' . $orderproduct['designed_id'] . '/';
					?>
					<h3><?php echo $j . '.  ' . $orderproduct['product_name']; ?></h3>
					<?php if (file_exists($imgDir . $orderproduct['side1_png']) && getimagesize($imgDir . $orderproduct['side1_png'])) { ?>
	    				<div class="orderlist-box">
	    				    <img src="<?php echo $imagepath . $orderproduct['side1_png']; ?>">
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side2_png']) && getimagesize($imgDir . $orderproduct['side2_png'])) { ?>
	    				<div class="orderlist-box">
	    				    <img src="<?php echo $imagepath . $orderproduct['side2_png']; ?>">
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side3_png']) && getimagesize($imgDir . $orderproduct['side3_png'])) { ?>
	    				<div class="orderlist-box">
	    				    <img src="<?php echo $imagepath . $orderproduct['side3_png']; ?>">
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side4_png']) && getimagesize($imgDir . $orderproduct['side4_png'])) { ?>
	    				<div class="orderlist-box">
	    				    <img src="<?php echo $imagepath . $orderproduct['side4_png']; ?>">
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side5_png']) && getimagesize($imgDir . $orderproduct['side5_png'])) { ?>
	    				<div class="orderlist-box">
	    				    <img src="<?php echo $imagepath . $orderproduct['side5_png']; ?>">
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side6_png']) && getimagesize($imgDir . $orderproduct['side6_png'])) { ?>
	    				<div class="orderlist-box">
	    				    <img src="<?php echo $imagepath . $orderproduct['side6_png']; ?>">
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side7_png']) && getimagesize($imgDir . $orderproduct['side7_png'])) { ?>
	    				<div class="orderlist-box">
	    				    <img src="<?php echo $imagepath . $orderproduct['side7_png']; ?>">
	    				</div>
					<?php } ?>
					<?php if (file_exists($imgDir . $orderproduct['side8_png']) && getimagesize($imgDir . $orderproduct['side8_png'])) { ?>
	    				<div class="orderlist-box">
	    				    <img src="<?php echo $imagepath . $orderproduct['side8_png']; ?>">
	    				</div>
					<?php } ?>

				    </div>
				    <div class="order-notes">
					<h3><?php echo $language['notes']; ?></h3>
					<div class="orderlist-note">
					    <p><?php echo $orderproduct['notes']; ?></p>
					</div>
				    </div>
				    <div class="clear"></div>
				</div>

				<?php
				$j++;
			    }
			    ?>
<?php } ?>

		    </div>

		    <div class="comment-div">
			<?php if (!empty($comments)) { ?>
    			<h2><?php echo $language['comments']; ?></h2>
    <?php foreach ($comments as $comment) { ?>
				<div class="comments_box">
				    <p class="user-date">
					<strong>
					    <?php
					    if ($comment['user_id'] != 0) {
						echo ucfirst($order['firstname']) . ' ' . ucfirst($order['lastname']);
					    } else {
						echo 'Admin';
					    }
					    ?>
					</strong>&nbsp;&nbsp;
	<?php echo date("D, M j g:i a", strtotime($comment['created'])); ?>
				    </p>
				    <p id="comment"><?php echo htmlspecialchars_decode($comment['comment']); ?></p>
				    <div class="file_conntten">
					<div class="file_name">
					    <?php
					    foreach ($comment['files'] as $cimg) {
						$ext = pathinfo($cimg['file_name'], PATHINFO_EXTENSION);
						if (strtolower($ext) == 'pdf') {
						    $extimage = 'pdf-icon.png';
						} else if (strtolower($ext) == 'docx') {
						    $extimage = 'wordpad-icon.png';
						} else if (strtolower($ext) == 'psd') {
						    $extimage = 'psd-icon.png';
						} else {
						    $extimage = 'image-icon.png';
						}
						$realimage = $siteBaseUrl . 'designnbuy/assets/images/message_board/' . $thread_id . '_' . $order['order_id'] . '/' . $cimg['file_name'];
						$realextimage = $siteBaseUrl . 'designnbuy/assets/pcmedia/images/' . $extimage;
						?>
	    				    <span><a href="<?php echo $realimage; ?>" target="_blank" download><img src="<?php echo $realextimage; ?>" /><?php echo $cimg['real_file_name']; ?></a></span>
	<?php } ?>
					</div>
				    </div>
				</div>
			    <?php } ?>
<?php } ?>
		    </div>





		    <div class="leave-cmt">
			<h2 id="leave_comment"><?php echo $language['leaveacomment']; ?></h2>
			<form action="" id="usrform" method="post">
			    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>" />
			    <input type="hidden" name="language_id" value="<?php echo $language_id; ?>" />
<?php if ($user_type != 'customer') { ?>		    
    			    <input type="hidden" name="user_id" value="0" />
    			    <input type="hidden" name="customer_name" value="Admin" />			
<?php } else { ?>
    			    <input type="hidden" name="user_id" value="<?php echo $order['customer_id']; ?>" />
    			    <input type="hidden" name="customer_name" value="<?php echo ucfirst($order['firstname']) . ' ' . ucfirst($order['lastname']); ?>" />
<?php } ?>
			    <input type="hidden" name="siteBaseUrl" value="<?php echo $siteBaseUrl; ?>" />
			    <textarea name="comment" rows="6" form="usrform"></textarea>
			    <div class="type-file-hidden">
				<input type="file" name="file[]" multiple />
				<!--<span>Attach file(s) to this comment</span>-->
			    </div>
			    <div class="pull-right"><button class="btn" type="submit" title="<?php echo $language['addthiscomment']; ?>"><?php echo $language['addthiscomment']; ?></button></div>

			</form>
		    </div>


		</div>
	    </div>
	    <div class="board-rgt">
		<div class="board-rgtcontten">
		    <div class="block">
			<div class="bg-color"><h2><?php echo $language['emailnotifications']; ?></h2></div>
			<p><?php echo $language['emailnotificationmessage']; ?></p>
			<div class="detail_box">
			    <?php if (!empty($notificationEmails)) { ?>
				    <?php foreach ($notificationEmails as $email) { ?>
				    <div class="thume_name">
					<?php echo $email['email']; ?>
					<?php if ($email['user_type'] == $user_type) { ?>
	    				<a class="delete" href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_output/deleteNotificationEmail/' . $email['notification_id'] . '/' . $language_id; ?>"><img src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/images/delete.svg'; ?>" alt="" /></a>
				    <?php } ?>
				    </div>
				<?php } ?>
<?php } ?>
			    <div class="addnew-id" id="sub_new_email">
				<p id="error-message" style="color:red;"></p>
				<a id="subscribe_email" style="float:right; margin-top:2px;" href="#"><?php echo $language['subscribeemail']; ?></a>
			    </div>
			</div>
		    </div>

		    <div class="block">
			<div class="bg-color"><h2><?php echo $language['previewdesign']; ?></h2></div>
			<?php if (!empty($orderproducts)) { ?>
			    <?php $i = 1; ?>
			    <?php foreach ($orderproducts as $orderproduct) { ?>
				<?php
				$imgDir = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'orderimages' . DIRECTORY_SEPARATOR . $orderproduct['designed_id'] . DIRECTORY_SEPARATOR;
				$imagepath = $siteBaseUrl . 'designnbuy/assets/images/orderimages/' . $orderproduct['designed_id'] . '/';
				$outputfilepath = $siteBaseUrl . 'designnbuy/assets/images/output/' . $orderproduct['designed_id'] . '/' . $orderproduct['output_file'];
				?>
				<div class="popup-slide">
				    <div class="text_slide">
					<h3><strong><?php echo $orderproduct['product_name']; ?></strong></h3>
					<div class="top_link">
					    <a href="#">
						<img src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/images/fullscreen.svg'; ?>" alt="" />
					    </a>
					</div>
				    </div>
				    <div id="product_detail_page">
					<!-- /panel -->  
					<div class="product-big-box">
					    <div class="product-big-swiper swiper-container">
						<div class="swiper-wrapper">
	<?php if (file_exists($imgDir . $orderproduct['side1_png']) && getimagesize($imgDir . $orderproduct['side1_png'])) { ?>
	    					    <div class="swiper-slide">
	    						<div class="product-big-image"> 
	    						    <a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $orderproduct['side1_png']; ?>">
	    							<img src="<?php echo $imagepath . $orderproduct['side1_png']; ?>" />
	    						    </a>
	    						</div>
	    					    </div>
						    <?php } ?>
	<?php if (file_exists($imgDir . $orderproduct['side2_png']) && getimagesize($imgDir . $orderproduct['side2_png'])) { ?>
	    					    <div class="swiper-slide">
	    						<div class="product-big-image"> 
	    						    <a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $orderproduct['side2_png']; ?>">
	    							<img src="<?php echo $imagepath . $orderproduct['side2_png']; ?>" />
	    						    </a>
	    						</div>
	    					    </div>
						    <?php } ?>
	<?php if (file_exists($imgDir . $orderproduct['side3_png']) && getimagesize($imgDir . $orderproduct['side3_png'])) { ?>
	    					    <div class="swiper-slide">
	    						<div class="product-big-image"> 
	    						    <a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $orderproduct['side3_png']; ?>">
	    							<img src="<?php echo $imagepath . $orderproduct['side3_png']; ?>" />
	    						    </a>
	    						</div>
	    					    </div>
						    <?php } ?>
	<?php if (file_exists($imgDir . $orderproduct['side4_png']) && getimagesize($imgDir . $orderproduct['side4_png'])) { ?>
	    					    <div class="swiper-slide">
	    						<div class="product-big-image"> 
	    						    <a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $orderproduct['side4_png']; ?>">
	    							<img src="<?php echo $imagepath . $orderproduct['side4_png']; ?>" />
	    						    </a>
	    						</div>
	    					    </div>
						    <?php } ?>
	<?php if (file_exists($imgDir . $orderproduct['side5_png']) && getimagesize($imgDir . $orderproduct['side5_png'])) { ?>
	    					    <div class="swiper-slide">
	    						<div class="product-big-image"> 
	    						    <a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $orderproduct['side5_png']; ?>">
	    							<img src="<?php echo $imagepath . $orderproduct['side5_png']; ?>" />
	    						    </a>
	    						</div>
	    					    </div>
						    <?php } ?>
	<?php if (file_exists($imgDir . $orderproduct['side6_png']) && getimagesize($imgDir . $orderproduct['side6_png'])) { ?>
	    					    <div class="swiper-slide">
	    						<div class="product-big-image"> 
	    						    <a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $orderproduct['side6_png']; ?>">
	    							<img src="<?php echo $imagepath . $orderproduct['side6_png']; ?>" />
	    						    </a>
	    						</div>
	    					    </div>
						    <?php } ?>
	<?php if (file_exists($imgDir . $orderproduct['side7_png']) && getimagesize($imgDir . $orderproduct['side7_png'])) { ?>
	    					    <div class="swiper-slide">
	    						<div class="product-big-image"> 
	    						    <a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $orderproduct['side7_png']; ?>">
	    							<img src="<?php echo $imagepath . $orderproduct['side7_png']; ?>" />
	    						    </a>
	    						</div>
	    					    </div>
						    <?php } ?>
	<?php if (file_exists($imgDir . $orderproduct['side8_png']) && getimagesize($imgDir . $orderproduct['side8_png'])) { ?>
	    					    <div class="swiper-slide">
	    						<div class="product-big-image"> 
	    						    <a class="fancybox" rel="gallery<?php echo $i; ?>" href="<?php echo $imagepath . $orderproduct['side8_png']; ?>">
	    							<img src="<?php echo $imagepath . $orderproduct['side8_png']; ?>" />
	    						    </a>
	    						</div>
	    					    </div>
	<?php } ?>
						</div>
					    </div>
					</div>
				    </div>
				</div>
				<?php $i++; ?>
			    <?php } ?>
<?php } ?>
		    </div>		    
		    <script src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/js/swiper.min.js'; ?>"></script>
		    <script>
			var swiper = new Swiper('.swiper-container');  
		    </script>
		    <script type="text/javascript" src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/source/jquery.fancybox.js'; ?>"></script>
		    <link rel="stylesheet" type="text/css" href="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/source/jquery.fancybox.css'; ?>" media="screen" />
		    <script>
			$(document).ready(function() {
			    var add = "<?php echo $language['add']; ?>";
			    var sure = "<?php echo $language['areyousure']; ?>";
			    var remove = "<?php echo $language['remove']; ?>";
			    
			    $('#subscribe_email').click(function(){
				var thread_id = '<?php echo $thread_id; ?>';
				var user_type = '<?php echo $user_type; ?>';
				var language_id = '<?php echo $language_id; ?>';
				var box_html = $('<div class="input-field email-notif-bxx"><form action="" method="post"><input type="text" name="email" value=""/><input type="hidden" name="language_id" value="' + language_id + '" /><input type="hidden" name="thread_id" value="' + thread_id + '" /><input type="hidden" name="user_type" value="' + user_type + '" /><div class="add-cancel"><input class="add-email" onclick="addEmail(this.form)" type="button" value="' + add + '" /><a href="javascript:;" onclick="removeSelectedEmail(this)">' + remove + '</a></div></form></div>');
				$('#sub_new_email').prepend(box_html);
				box_html.fadeIn('slow');
				return false;
			    });	
    			    
			    $(document.body).on("click", ".delete", function(event) {
				event.preventDefault();	
				var row = this;
				confirm(sure);
				var href = $(this).attr("href");
				$.ajax({
				    type: "GET",
				    url: href,
				    dataType: "json",
				    success: function(result) {
					if (result.response === "true") {
					    $(row).closest("div").css("background-color", "red");
					    $(row).closest("div").delay(1000).fadeOut(2000);			
					} else {
					    $('#error-message').html(result.message);
					}
				    }
				});		       
			    });
    			    
			    $('#usrform').submit(function(event) {
				event.preventDefault();
				var formData = new FormData($(this)[0]);
				$.ajax({
				    type        : 'POST',
				    url         : '<?php echo $siteBaseUrl . "designnbuy/pcstudio_output/addComment/" . $thread_id; ?>',
				    data        : formData,
				    dataType    : 'json',
				    async	: false,
				    cache	: false,
				    contentType	: false,
				    processData	: false, 
				    success: function (returndata) {
					if(returndata.response === 'true') {				    
					    var comment_html = returndata.html;	
					    $('#leave_comment').before(comment_html).fadeIn('slow');
					    $(':input','#usrform').not(':button, :submit, :reset, :hidden').val('');				    
					} else {
					    $('#leave_comment').after(returndata.message).fadeIn('slow');
					    setTimeout(function(){
						$('#error-comment').fadeOut('slow');
					    }, 3000);
					}				
					return false;
				    }
				});
				return false;
			    });
			});

			function removeSelectedEmail(el){
			    $(el).parent().parent().remove();
			}
    			
			function addEmail(form) {
			    var invalidemail = "<?php echo $language['invalidemail']; ?>";
			    var email = form.email.value;		   
			    var thread_id = form.thread_id.value;
			    var user_type = form.user_type.value;
			    var language_id = form.language_id.value;
			    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			    var email_valid = regex.test(email);
			    if(email_valid == true) {
				$.ajax({
				    type: "POST",
				    url: '<?php echo $siteBaseUrl . "designnbuy/pcstudio_output/addNotificationEmail/"; ?>',
				    data: 'email='+ email + '&thread_id=' + thread_id + '&user_type=' + user_type + '&language_id=' + language_id,
				    dataType: "json",
				    success: function(result) {
					if (result.response === "true") {
					    var deleteurl = '<?php echo $siteBaseUrl . "designnbuy/pcstudio_output/deleteNotificationEmail/"; ?>' + result.notification_id + '/' + language_id;
					    var imageurl = '<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/images/delete.svg'; ?>';
					    $('#sub_new_email').before('<div class="thume_name">' + result.data['email'] + '<a class="delete" href="' + deleteurl + '"><img src="' + imageurl + '" alt="" /></a></div>');
					    $('.email-notif-bxx').remove();
					    form.email.value = '';
					} else {
					    $('#error-message').html(result.message);
					    setTimeout(function(){
						$('#error-message').html('');
					    }, 3000);
					    // $("#error-message").fadeTo( 1000, 0 );		
					}
				    }
				});	
			    } else {
				//$('#error-message').html();
				$('#error-message').html(invalidemail);
				setTimeout(function(){
				    $('#error-message').html('');
				}, 3000);
				//$("#error-message").fadeTo( 1000, 0 );
				//$('#error-message').empty();
			    }
			}
		    </script>
		    <script>
			$(document).ready(function() {
			    $(".fancybox").fancybox({
				openMethod 	: 'zoomIn',
				closeMethod : 'zoomOut',
				nextEffect : 'fade',
				prevEffect : 'fade', 
				arrows : 'true'
			    });
			});
		    </script>
		</div>
	    </div>

	</div>

    </body>
</html>
