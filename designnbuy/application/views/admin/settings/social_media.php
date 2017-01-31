<div class="pc_contten">
    <div class="pc_top">
        <h1>Social Media Configuration</h1>
	<!--<div class="pc_rgt"><a href="#">Fonts</a></div>-->
    </div>

    <?php if( validation_errors()) { ?>
    <div id="messages_product_view">
	<ul class="messages">
	    <li class="error-msg">
		<?php echo validation_errors(); ?>
	    </li>
	</ul>
    </div>
<?php } ?>
    
    <?php if($this->session->flashdata('msg')) { ?>
    <div id="messages_product_view">
	<ul class="messages">
	    <li class="success-msg">
		<?php echo $this->session->flashdata('msg'); ?>
	    </li>
	</ul>
    </div>
    <?php } ?>

    <div>&nbsp;</div>

    <div>
	<div class="pc_contten box-box">
	    <form action="<?php echo BASE_ADMIN_URL.'settings/update_social_media'; ?>" method="post" class="col-two">
		<input type="hidden" name="id" value="<?php echo $setting['id']; ?>" />
		<div class="input-area">
		    <div class="field-set">
			<label>Facebook APP Id</label>
			<div class="input-field validationclass">
			    <input type="text" name="facebook_app_id" value="<?php echo $setting['facebook_app_id']; ?>" placeholder="">
			</div>
		    </div>
		    <div class="field-set">
			<label>Flickr API Key</label>
			<div class="input-field">
			    <input type="text" name="flicker_api_key" value="<?php echo $setting['flicker_api_key']; ?>" placeholder="">
			</div>
		    </div>
		    <div class="field-set">
			<label>Instagram Client ID</label>
			<div class="input-field">
			    <input type="text" name="instagram_client_id" value="<?php echo $setting['instagram_client_id']; ?>" placeholder="">
			</div>
		    </div>
		</div>
		<div class="input-area">
		    <div class="field-set">
			<label>&nbsp;</label>
			<div class="input-field">
			    <input type="submit" class="btn" value="Save" />
			</div>
		    </div>
		</div>
	    </form>
	</div>
    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
    $(document).ready(function() {	
	$('#messages_product_view').fadeIn().delay(10000).fadeOut();
    });
</script>
