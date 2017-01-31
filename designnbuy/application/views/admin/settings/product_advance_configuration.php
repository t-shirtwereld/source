<div class="pc_contten">
    <div class="pc_top">
        <h1>Product Advance Configuration</h1>
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
	    <form action="<?php echo BASE_ADMIN_URL.'settings/update_product_advance_configuration'; ?>" method="post" class="col-two">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="hidden" name="name" value="<?php echo $name; ?>" />
		<div class="input-area">
		    <div class="field-set">
			<label>Color Option</label>
			<div class="input-field validationclass">
			    <input type="text" name="option_abbreviation[color_option]" value="<?php echo $setting['color_option']; ?>" placeholder="">
			</div>
		    </div>
		    <div class="field-set">
			<label>Size Option</label>
			<div class="input-field">
			    <input type="text" name="option_abbreviation[size_option]" value="<?php echo $setting['size_option']; ?>" placeholder="">
			</div>
		    </div>
		    <div class="field-set">
			<label>Area Option</label>
			<div class="input-field">
			    <input type="text" name="option_abbreviation[area_option]" value="<?php echo $setting['area_option']; ?>" placeholder="">
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
