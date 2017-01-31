<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $language['mymedia']; ?></title>
	<link href="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/css/style.css'; ?>" rel="stylesheet" type="text/css" />
	<script src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/javascript/javascript/plugins/jquery-2.1.1.min.js'; ?>"></script>
	<script src="<?php echo $siteBaseUrl . 'designnbuy/assets/pcmedia/js/jquery.form.js'; ?>"></script>
    </head>
    <body>
	<div class="upload_btn upload-img">
	    <form id="upload_new_image" method="post" enctype="multipart/form-data" action='<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/uploadNewMedia'; ?>'>
		<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
		<input type="hidden" name="language_id" value="<?php echo $language_id; ?>" />
		<input type="file" name="new_image" id="new_image" />
		<div id="preview"></div>
		<a href="#"><?php echo $language['uploadimage']; ?></a>
	    </form>
	</div>
	<div class="clear"></div>

	<?php if (!empty($media)) { ?>
	    <?php $i = 1; ?>
	    <?php foreach ($media as $m) { ?>
		<?php
			$imgDir = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'uploadedImage' . DIRECTORY_SEPARATOR;
			$imagepath = $siteBaseUrl . 'designnbuy/assets/images/uploadedImage/';
			$ext = pathinfo($imgDir . $m['image'], PATHINFO_EXTENSION);
		?>

		<div class="design_frame design-frame-<?php echo $i; ?>">
		    <br />
		    <div class="thume-images">
			<a class="delete" href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/deleteMyMedia/' . $m['userimage_id']; ?>"></a>
			<?php if (file_exists($imgDir . $m['image'])) { ?>
	    		<img src="<?php echo $imagepath . $m['image']; ?>" />
			<?php } ?>
		    </div>
			<?php if($ext != 'svg'): ?>
		    <div class="upload_btn">
			<form id="imageform-<?php echo $m['userimage_id']; ?>" method="post" enctype="multipart/form-data" action='<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/uploadHd'; ?>'>
			    <input type="hidden" name="user_id" value="<?php echo $m['user_id']; ?>" />
			    <input type="hidden" name="language_id" value="<?php echo $language_id; ?>" />
			    <input type="hidden" name="userimage_id" value="<?php echo $m['userimage_id']; ?>" />
			    <input type="hidden" name="siteBaseUrl" value="<?php echo $siteBaseUrl; ?>" />
			    <input type="file" name="image_hd" id="photoimg-<?php echo $m['userimage_id']; ?>" />
			</form>
			<a href="#"><?php echo $language['uploadhd']; ?></a>
		    </div>
	     <!--       <div class="detail_box" id="detail_box-<?php echo $m['userimage_id']; ?>">
		    <?php if (!empty($m['image_hd'])) { ?>
			<?php foreach ($m['image_hd'] as $hd) { ?>
			    <?php if (file_exists($imgDir . $hd['image'])) { ?>
		    					<div class="thume_name" id="thume_name-<?php echo $m['userimage_id']; ?>">
		    					    <a target="_blank" href="<?php echo $imagepath . $hd['image']; ?>">					<?php
		    $longString = $hd['image'];
		    $separator = '/...../';
		    $separatorlength = strlen($separator);
		    $maxlength = 20 - $separatorlength;
		    $start = $maxlength / 2;
		    $trunc = strlen($longString) - $maxlength;
		    echo substr_replace($longString, $separator, $start, $trunc);
				?>
		    					    </a>
		    					    <a class="deleteHD" href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/deleteHdMedia/' . $hd['id']; ?>">
		    						<img src="<?php echo base_url('assets/pcmedia/images/delete.svg'); ?>" alt="" />
		    					    </a>
		    					</div>
			    <?php } ?>
			<?php } ?>
		    <?php } ?>
		    </div>  -->
		    <div class="box">
			<?php if (!empty($m['image_hd'])) { ?>
			    <?php foreach ($m['image_hd'] as $hd) { ?>
				<?php if (file_exists($imgDir . $hd['image'])) { ?>
				    <?php
				    $longString = $hd['image'];
				    $separator = '/...../';
				    $separatorlength = strlen($separator);
				    $maxlength = 20 - $separatorlength;
				    $start = $maxlength / 2;
				    $trunc = strlen($longString) - $maxlength;
				    $fileext = pathinfo($hd['image'], PATHINFO_EXTENSION);
				    if ($fileext == 'psd') {
					$psdimagename[$m['userimage_id']] = substr_replace($longString, $separator, $start, $trunc);
					$psdimage[$m['userimage_id']] = $hd['image'];
					$psdid[$m['userimage_id']] = $hd['id'];
				    } else if ($fileext == 'ai') {
					$aiimagename[$m['userimage_id']] = substr_replace($longString, $separator, $start, $trunc);
					$aiimage[$m['userimage_id']] = $hd['image'];
					$aiid[$m['userimage_id']] = $hd['id'];
				    } else if ($fileext == 'pdf') {
					$pdfimagename[$m['userimage_id']] = substr_replace($longString, $separator, $start, $trunc);
					$pdfimage[$m['userimage_id']] = $hd['image'];
					$pdfid[$m['userimage_id']] = $hd['id'];
				    } else if ($fileext == 'eps') {
					$epsimagename[$m['userimage_id']] = substr_replace($longString, $separator, $start, $trunc);
					$epsimage[$m['userimage_id']] = $hd['image'];
					$epsid[$m['userimage_id']] = $hd['id'];
				    } else if ($fileext == 'tif' || $fileext == 'tiff') {
					$tifimagename[$m['userimage_id']] = substr_replace($longString, $separator, $start, $trunc);
					$tifimage[$m['userimage_id']] = $hd['image'];
					$tifid[$m['userimage_id']] = $hd['id'];
				    } else if ($fileext == 'cdr') {
					$cdrimagename[$m['userimage_id']] = substr_replace($longString, $separator, $start, $trunc);
					$cdrimage[$m['userimage_id']] = $hd['image'];
					$cdrid[$m['userimage_id']] = $hd['id'];
				    } else if ($fileext == 'ps') {
					$psimagename[$m['userimage_id']] = substr_replace($longString, $separator, $start, $trunc);
					$psimage[$m['userimage_id']] = $hd['image'];
					$psid[$m['userimage_id']] = $hd['id'];
				    }
				    ?>	
			
				<?php } ?>
			    <?php } ?>
			<?php } ?>
			<div class="list psd transition-hover">
			    <?php if (isset($psdimage[$m['userimage_id']]) && $psdimage[$m['userimage_id']] != '') { ?>
	    		    <img id="psd-<?php echo $m['userimage_id']; ?>" class="" src="<?php echo base_url("assets/pcmedia/images/psd.png"); ?>" />
			    <?php } else { ?>
	    		    <img id="psd-<?php echo $m['userimage_id']; ?>" class="grayscale" src="<?php echo base_url("assets/pcmedia/images/psd.png"); ?>" />
			    <?php } ?>
			    <div class="transition-hover-content psd-<?php echo $m['userimage_id']; ?>">
				<div class="upload-text" id="psd<?php echo $m['userimage_id']; ?>">
				    <?php if (isset($psdimage[$m['userimage_id']]) && $psdimage[$m['userimage_id']] != '') { ?>
	    			    <div class="upload-textcon"> 
	    				<a href="<?php echo $imagepath . $psdimage[$m['userimage_id']]; ?>" target="_blank"><?php echo $psdimagename[$m['userimage_id']]; ?></a>
	    				<a href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/deleteHdMedia/' . $psdid[$m['userimage_id']]; ?>" class="deleteHD">
	    				    <img alt="" src="<?php echo base_url('assets/pcmedia/images/delete.png'); ?>" />
	    				</a>
	    			    </div>	
				    <?php } else { ?>
	    			    <div class="upload-textcon"></div>
				    <?php } ?>
				</div> 
			    </div>  
			</div>
			<div class="list ai transition-hover">
			     <?php if (isset($aiimage[$m['userimage_id']]) && $aiimage[$m['userimage_id']] != '') { ?>
			    <img id="ai-<?php echo $m['userimage_id']; ?>" class="" src="<?php echo base_url("assets/pcmedia/images/ai.png"); ?>" />
			    <?php } else { ?>
			    <img id="ai-<?php echo $m['userimage_id']; ?>" class="grayscale" src="<?php echo base_url("assets/pcmedia/images/ai.png"); ?>" />
			    <?php } ?>
			    <div class="transition-hover-content">
				<div class="upload-text" id="ai<?php echo $m['userimage_id']; ?>">
				    <?php if (isset($aiimage[$m['userimage_id']]) && $aiimage[$m['userimage_id']] != '') { ?>
	    			    <div class="upload-textcon"> 
	    				<a href="<?php echo $imagepath . $aiimage[$m['userimage_id']]; ?>" target="_blank"><?php echo $aiimagename[$m['userimage_id']]; ?></a>
	    				<a href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/deleteHdMedia/' . $aiid[$m['userimage_id']]; ?>" class="deleteHD">
	    				    <img alt="" src="<?php echo base_url('assets/pcmedia/images/delete.png'); ?>" />
	    				</a>
	    			    </div>	
				    <?php } else { ?>
	    			    <div class="upload-textcon"></div>
				    <?php } ?>
				</div> 
			    </div>  
			</div>
			<div class="list pdf transition-hover">
			    <?php if (isset($pdfimage[$m['userimage_id']]) && $pdfimage[$m['userimage_id']] != '') { ?>
			    <img id="pdf-<?php echo $m['userimage_id']; ?>" class="" src="<?php echo base_url("assets/pcmedia/images/pdf.png"); ?>" />
			    <?php } else { ?>
			    <img id="pdf-<?php echo $m['userimage_id']; ?>" class="grayscale" src="<?php echo base_url("assets/pcmedia/images/pdf.png"); ?>" />
			    <?php } ?>
			    <div class="transition-hover-content">
				<div class="upload-text" id="pdf<?php echo $m['userimage_id']; ?>">
				    <?php if (isset($pdfimage[$m['userimage_id']]) && $pdfimage[$m['userimage_id']] != '') { ?>
	    			    <div class="upload-textcon"> 
	    				<a href="<?php echo $imagepath . $pdfimage[$m['userimage_id']]; ?>" target="_blank"><?php echo $pdfimagename[$m['userimage_id']]; ?></a>
	    				<a href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/deleteHdMedia/' . $pdfid[$m['userimage_id']]; ?>" class="deleteHD">
	    				    <img alt="" src="<?php echo base_url('assets/pcmedia/images/delete.png'); ?>" />
	    				</a>
	    			    </div>	
				    <?php } else { ?>
	    			    <div class="upload-textcon"></div>
				    <?php } ?>
				</div>
			    </div>

			</div>
			<div class="list eps transition-hover">
			    <?php if (isset($epsimage[$m['userimage_id']]) && $epsimage[$m['userimage_id']] != '') { ?>
			    <img id="eps-<?php echo $m['userimage_id']; ?>" class="" src="<?php echo base_url("assets/pcmedia/images/eps.png"); ?>" />
			    <?php } else { ?>
			    <img id="eps-<?php echo $m['userimage_id']; ?>" class="grayscale" src="<?php echo base_url("assets/pcmedia/images/eps.png"); ?>" />
			    <?php } ?>
			    <div class="transition-hover-content">
				<div class="upload-text" id="eps<?php echo $m['userimage_id']; ?>">
				    <?php if (isset($epsimage[$m['userimage_id']]) && $epsimage[$m['userimage_id']] != '') { ?>
	    			    <div class="upload-textcon"> 
	    				<a href="<?php echo $imagepath . $epsimage[$m['userimage_id']]; ?>" target="_blank"><?php echo $epsimagename[$m['userimage_id']]; ?></a>
	    				<a href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/deleteHdMedia/' . $epsid[$m['userimage_id']]; ?>" class="deleteHD">
	    				    <img alt="" src="<?php echo base_url('assets/pcmedia/images/delete.png'); ?>" />
	    				</a>
	    			    </div>	
				    <?php } else { ?>
	    			    <div class="upload-textcon"></div>
				    <?php } ?>
				</div>
			    </div>

			</div>
			<div class="list tif transition-hover">
			    <?php if (isset($tifimage[$m['userimage_id']]) && $tifimage[$m['userimage_id']] != '') { ?>
			    <img id="tif-<?php echo $m['userimage_id']; ?>" class="" src="<?php echo base_url("assets/pcmedia/images/tiff.png"); ?>" />
			    <?php } else { ?>
			    <img id="tif-<?php echo $m['userimage_id']; ?>" class="grayscale" src="<?php echo base_url("assets/pcmedia/images/tiff.png"); ?>" />
			    <?php } ?>
			    <div class="transition-hover-content">
				<div class="upload-text" id="tif<?php echo $m['userimage_id']; ?>">
				    <?php if (isset($tifimage[$m['userimage_id']]) && $tifimage[$m['userimage_id']] != '') { ?>
	    			    <div class="upload-textcon"> 
	    				<a href="<?php echo $imagepath . $tifimage[$m['userimage_id']]; ?>" target="_blank"><?php echo $tifimagename[$m['userimage_id']]; ?></a>
	    				<a href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/deleteHdMedia/' . $tifid[$m['userimage_id']]; ?>" class="deleteHD">
	    				    <img alt="" src="<?php echo base_url('assets/pcmedia/images/delete.png'); ?>" />
	    				</a>
	    			    </div>	
				    <?php } else { ?>
	    			    <div class="upload-textcon"></div>
				    <?php } ?>
				</div>
			    </div>

			</div>
			<div class="list cdr transition-hover">
			    <?php if (isset($cdrimage[$m['userimage_id']]) && $cdrimage[$m['userimage_id']] != '') { ?>
			    <img id="cdr-<?php echo $m['userimage_id']; ?>" class="" src="<?php echo base_url("assets/pcmedia/images/cdr.png"); ?>" />
			    <?php } else { ?>
			    <img id="cdr-<?php echo $m['userimage_id']; ?>" class="grayscale" src="<?php echo base_url("assets/pcmedia/images/cdr.png"); ?>" />
			    <?php } ?>
			    <div class="transition-hover-content">
				<div class="upload-text" id="cdr<?php echo $m['userimage_id']; ?>">
				    <?php if (isset($cdrimage[$m['userimage_id']]) && $cdrimage[$m['userimage_id']] != '') { ?>
	    			    <div class="upload-textcon"> 
	    				<a href="<?php echo $imagepath . $cdrimage[$m['userimage_id']]; ?>" target="_blank"><?php echo $cdrimagename[$m['userimage_id']]; ?></a>
	    				<a href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/deleteHdMedia/' . $cdrid[$m['userimage_id']]; ?>" class="deleteHD">
	    				    <img alt="" src="<?php echo base_url('assets/pcmedia/images/delete.png'); ?>" />
	    				</a>
	    			    </div>	
				    <?php } else { ?>
	    			    <div class="upload-textcon"></div>
				    <?php } ?>
				</div>
			    </div>

			</div>
			<div class="list ps transition-hover">
			    <?php if (isset($psimage[$m['userimage_id']]) && $psimage[$m['userimage_id']] != '') { ?>
			    <img id="ps-<?php echo $m['userimage_id']; ?>" class="" src="<?php echo base_url("assets/pcmedia/images/ps.png"); ?>" />
			    <?php } else { ?>
			    <img id="ps-<?php echo $m['userimage_id']; ?>" class="grayscale" src="<?php echo base_url("assets/pcmedia/images/ps.png"); ?>" />
			    <?php } ?>
			    <div class="transition-hover-content">
				<div class="upload-text" id="ps<?php echo $m['userimage_id']; ?>">
				    <?php if (isset($psimage[$m['userimage_id']]) && $psimage[$m['userimage_id']] != '') { ?>
	    			    <div class="upload-textcon"> 
	    				<a href="<?php echo $imagepath . $psimage[$m['userimage_id']]; ?>" target="_blank"><?php echo $psimagename[$m['userimage_id']]; ?></a>
	    				<a href="<?php echo $siteBaseUrl . 'designnbuy/pcstudio_media/deleteHdMedia/' . $psid[$m['userimage_id']]; ?>" class="deleteHD">
	    				    <img alt="" src="<?php echo base_url('assets/pcmedia/images/delete.png'); ?>" />
	    				</a>
	    			    </div>	
				    <?php } else { ?>
	    			    <div class="upload-textcon"></div>
				    <?php } ?>
				</div>
			    </div>

			</div>
		    </div>
			<?php endif; ?>
		    <br /><br /><br />
		</div>

		<?php if ($i % 3 == 0) { ?>
	    	<div class="bottom-border">&nbsp;</div>
		<?php } ?>
		<?php $i++; ?>
	    <?php } ?>
	<?php } ?>
	<script>
	    $( document ).ready(function() {
		var sure = "<?php echo $language['areyousure']; ?>";
		var deleterecord = "<?php echo $language['errodeleterecord']; ?>";
		var deleteimagenotavailable = "<?php echo $language['deleteimagenotavailable']; ?>";
		$("a.delete").click(function(event){
		    event.preventDefault();
		    if (confirm(deleteimagenotavailable)) {
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
		
		
		$(document.body).on("click", ".deleteHD", function(event) {
		    event.preventDefault();
		    if (confirm(sure)) {
			var parent = $(this).parent();
			var grandparent = $(this).parent().parent().parent().parent();
			var href = $(this).attr("href");
			$.ajax({
			    type: "GET",
			    url: href,
			    success: function(response) {		    
				if (response === "true") {
				    $(grandparent).find('img').first().addClass('grayscale');
				    parent.fadeOut(1000,function() { parent.remove(); });
				} else {
				    alert(deleterecord);
				}		    
			    }
			});
		    } 
		    return false;			
		});	
	    

		$('input[name=image_hd]').on('change', function()
		{
		    var browseId = $(this).attr("id");
		    var result = browseId.split('-');
		    $("#imageform-" + result[1]).append('<img src="<?php echo base_url("assets/pcmedia/images/ajax-loader.gif"); ?>" class="loader"/> ');
		    $("#imageform-" + result[1]).ajaxForm({
			success: function(response) { 
			    response = JSON.parse(response);
			    var text = response.result;
			    var id = response.id;	    
			    $(".loader").fadeOut("slow");
			    if(id && id != '') {
				$("#" + id + result[1]).html(text).fadeIn('slow');
				$('#' + id + '-' + result[1]).removeClass('grayscale');
			    } else {
				$("#imageform-" + result[1]).parent().after(text);
				$(".hd_error_message").fadeOut(4000);
			    }
			} 
		    }).submit();
		});
		
		$('#new_image').on('change', function()
		{
		    $("#preview").html('');
		    $("#preview").html('<img src="<?php echo base_url("assets/pcmedia/images/loader.gif"); ?>" alt="Uploading...."/>');
		    $("#upload_new_image").ajaxForm({
			success: function(response) { 
			    //  $(".loader").fadeOut("slow");
			    //  $("#detail_box-" + result[1]).append(response).show('slow');
			    if(response == "true") {
				window.location.reload(); 
			    } else {
				$("#preview").html('<p style="color:red;">'+response+'</p>').fadeOut(4000);;
			    }
			} 
		    }).submit();
		});
	    
	    }); 
	</script>
    </body>
</html>