<div class="pc_contten">
    <div class="pc_top">
        <h1>Multi Language</h1>
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
		    <h2>Select Language</h2>
		    <div class="input-field language_select">
			<select id="select_lang" name="select_lang">
			    <?php foreach ($languages as $language) { ?>
    			    <option value="<?php echo $language['iso_code']; ?>" <?php if ($language['iso_code'] == $lang) { echo "selected='selected'"; } ?>><?php echo $language['name']; ?></option>
			    <?php } ?>
			</select>
		    </div>
	    <form action="<?php echo BASE_ADMIN_URL . 'settings/saveMultiLanguage/' . $lang; ?>" method="post" class="col-two">
		<h2>Element Labels</h2>
		<div class="input-area element_label">
<?php foreach ($labels as $key => $value) { ?>
		    <?php if($value['pc_type'] == 'elementLabel') { ?>
    		    <div class="field-set">
    			<label><?php echo $value['pc_label']; ?></label>
    			<div class="input-field validationclass">
			    <input type="text" name="language_data[<?php echo $key ?>]" value="<?php echo substr(html_entity_decode(htmlspecialchars_decode($value['pc_text'])),0,$value['pc_maxlength']); ?>" placeholder="" maxlength="<?php echo $value['pc_maxlength']; ?>" />
    			</div>
    		    </div>
		    <?php } ?>
<?php } ?>
		</div>
		<h2>Roll Over</h2>
		<div class="input-area element_label">
<?php foreach ($labels as $key => $value) { ?>
		    <?php if($value['pc_type'] == 'rollOver') { ?>
    		    <div class="field-set">
    			<label><?php echo $value['pc_label']; ?></label>
    			<div class="input-field validationclass">
    			    <input type="text" name="language_data[<?php echo $key ?>]" value="<?php echo substr(html_entity_decode(htmlspecialchars_decode($value['pc_text'])),0,$value['pc_maxlength']); ?>" placeholder="" maxlength="<?php echo $value['pc_maxlength']; ?>" />
    			</div>
    		    </div>
		    <?php } ?>
<?php } ?>
		</div>
		<h2>QR Code Panel</h2>
		<div class="input-area element_label">
<?php foreach ($labels as $key => $value) { ?>
		    <?php if($value['pc_type'] == 'qrCodePanel') { ?>
    		    <div class="field-set">
    			<label><?php echo $value['pc_label']; ?></label>
    			<div class="input-field validationclass">
    			    <input type="text" name="language_data[<?php echo $key ?>]" value="<?php echo substr(html_entity_decode(htmlspecialchars_decode($value['pc_text'])),0,$value['pc_maxlength']); ?>" placeholder="" maxlength="<?php echo $value['pc_maxlength']; ?>" />
    			</div>
    		    </div>
		    <?php } ?>
<?php } ?>
		</div>
		<h2>Notifications</h2>
		<div class="input-area element_label">
<?php foreach ($labels as $key => $value) { ?>
		    <?php if($value['pc_type'] == 'notification') { ?>
    		    <div class="field-set">
    			<label><?php echo $value['pc_label']; ?></label>
    			<div class="input-field validationclass">
    			    <input type="text" name="language_data[<?php echo $key ?>]" value="<?php echo substr(html_entity_decode(htmlspecialchars_decode($value['pc_text'])),0,$value['pc_maxlength']); ?>" placeholder="" maxlength="<?php echo $value['pc_maxlength']; ?>" />
    			</div>
    		    </div>
		    <?php } ?>
<?php } ?>
		</div>
		<h2>Others</h2>
		<div class="input-area element_label">
<?php foreach ($labels as $key => $value) { ?>
		    <?php if($value['pc_type'] == 'others') { ?>
    		    <div class="field-set">
    			<label><?php echo $value['pc_label']; ?></label>
    			<div class="input-field validationclass">
    			    <input type="text" name="language_data[<?php echo $key ?>]" value="<?php echo substr(html_entity_decode(htmlspecialchars_decode($value['pc_text'])),0,$value['pc_maxlength']); ?>" placeholder="" maxlength="<?php echo $value['pc_maxlength']; ?>" />
    			</div>
    		    </div>
		    <?php } ?>
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
	$("#select_lang").change(function() {
	    window.location.href = "<?php echo get_base_url().'designnbuy/admin/settings/multilanguage/'; ?>" + $(this).val();
	});
	
	$('#messages_product_view').fadeIn().delay(5000).fadeOut();
    });
    
</script>
