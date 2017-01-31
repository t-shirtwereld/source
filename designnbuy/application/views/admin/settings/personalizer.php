<div class="pc_contten">
    <div class="pc_top">
        <h1>Personalize Design Studio</h1>
    </div>
      <?php if ($this->session->flashdata('msg')) { ?>
        <div id="messages_product_view">
    	<ul class="messages">
    	    <li class="success-msg">
		    <?php echo $this->session->flashdata('msg'); ?>
    	    </li>
    	</ul>
        </div>
    <?php } ?>
    <div>
	<div class="pc_contten box-box">
	    <form action="<?php echo BASE_ADMIN_URL . 'settings/save_personlizer/' . $lang; ?>" method="post" class="col-two">
		<div class="input-area">
		    <?php foreach ($labels as $key => $value) { ?>
    		    <div class="field-set">
    			<label><?php echo $value['title']; ?></label>
    			<input type="hidden" name="personalizer[<?php echo $key; ?>][type]" value="<?php echo $themeOptionValue->type; ?>">
    			<input type="hidden" name="personalizer[<?php echo $key; ?>][title]" value="<?php echo $value['title']; ?>">								
    			<input type="hidden" name="personalizer[<?php echo $key; ?>][css]" value="<?php echo htmlspecialchars($value['css']); ?>">
    			<input type="hidden" name="personalizer[<?php echo $key; ?>][description]" value="<?php echo $value['description']; ?>">
    			<input type="hidden" name="personalizer[<?php echo $key; ?>][code_session]" value="<?php echo $value['code_session']; ?>">
    			<input type="hidden" name="personalizer[<?php echo $key; ?>][default_value]" value="<?php echo $value['default_value']; ?>">
    			<div class="input-field">
    			    <input id="color_code" class="fixed-width-sm valid" type="color" name="personalizer[<?php echo $key; ?>][current_value]; ?>" value="<?php echo $value['current_value']; ?>" aria-invalid="false">
    			    <span id="span_color_name" class="color-code"><?php echo $value['current_value']; ?></span>
    			    <small style="display:block;"><?php echo $value['description']; ?></small>
    			</div>
    		    </div>
		    <?php } ?>
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
	$("#color_code").on('change', function() {
	    $("#span_color_name").text($(this).val());
	});
	
	$('#messages_product_view').fadeIn().delay(5000).fadeOut();
    });
    
</script>
