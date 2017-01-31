<script type="text/javascript" src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
<div class="pc_contten">
    <div class="pc_top">
        <h1>Help Data</h1>
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
    		    <option value="<?php echo $language['language_id']; ?>" <?php
		    if ($language['language_id'] == $lang_id) {
			echo "selected='selected'";
		    }
			?>><?php echo $language['name']; ?></option>
			    <?php } ?>
		</select>
	    </div>
	    <form action="<?php echo BASE_ADMIN_URL . 'settings/save_help_data/' . $lang_id; ?>" method="post" class="col-one">
		<input type="hidden" name="help_data_id" value="<?php echo $help['help_data_id']; ?>" />
		<h2>Help Content</h2>
		<div class="input-area">
		    <textarea class="ckeditor" name="help_data_text"><?php echo htmlspecialchars_decode($help['help_data_text']); ?></textarea>
		</div>
		<div class="input-area">
		    <div class="input-field">
			<input type="submit" class="btn" value="Save" />
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
	    window.location.href = "<?php echo get_base_url() . 'designnbuy/admin/settings/help_data/'; ?>" + $(this).val();
	});
	
	$('#messages_product_view').fadeIn().delay(5000).fadeOut();
	
	CKEDITOR.replace( 'help_data_text',
	{
	   filebrowserBrowseUrl : '<?php echo get_base_url() . "designnbuy/assets/js/ckeditor/browse.php?imagepath=" . get_base_url() . "designnbuy/designtool/help/images&dir=" . $_SERVER['DOCUMENT_ROOT'] . "/designtool/help/images/"; ?>',
	   filebrowserUploadUrl : '<?php echo get_base_url() . "designnbuy/admin/settings/ckEditorFileUpload"; ?>'
	});
    });
    
</script>
