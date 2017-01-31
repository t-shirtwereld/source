<div class="pc_contten">
    <div class="pc_top">
        <h1>Edit Clipart</h1>
	<div class="pc_rgt" style="margin-left: 10px;" ><a href="<?php echo BASE_ADMIN_URL . 'cliparts'; ?>">Clipart Listing</a></div>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'cliparts/importcsv'; ?>">Import Cliparts </a></div>
    </div>

    <?php if (validation_errors()) { ?>
        <div id="messages_product_view">
    	<ul class="messages">
    	    <li class="error-msg">
		    <?php echo validation_errors(); ?>
    	    </li>
    	</ul>
        </div>
    <?php } ?>

    <div>&nbsp;</div>

    <div>
	<div class="pc_contten box-box">
	    <form id="editClipartForm" action="<?php echo BASE_ADMIN_URL . 'cliparts/submitdata'; ?>" method="post" class="col-two" enctype="multipart/form-data">
		<input type="hidden" name="clipart_id" value="<?php echo $clipart_row['clipart_id']; ?>" />
		<input type="hidden" name="is_clipart_design" value="<?php echo $clipart_row['is_clipart_design']; ?>" />
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<div class="field-lable"><label>Name</label></div>
			<div class="field-form">
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field validationclass">
    				<input type="text" name="clipart_name[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($clipart_names[$language['language_id']]) ? htmlspecialchars($clipart_names[$language['language_id']]['name']) : ''; ?>" placeholder="" />
    				<span><img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt=""></span>
    			    </div>
			    <?php } ?>
			</div>
		    </div>
		    <div class="field-set">
			<label>Clipart Categories</label>
			<div class="input-field">
			    <select id="clipart_category_id" name="clipart_category_id">
				<option value="">Select Clipart Category</option>
				<?php foreach ($categories as $category) { ?>
    				<option value="<?php echo $category['clipart_category_id']; ?>" <?php if ($category['clipart_category_id'] == $clipart_row['clipart_category_id']) echo "selected=selected"; ?>><?php echo $category['name']; ?></option>
				    <?php if (!empty($category['child'])) { ?>
					<?php foreach ($category['child'] as $sub) { ?>
	    				<option value="<?php echo $sub['clipart_category_id']; ?>" <?php if ($sub['clipart_category_id'] == $clipart_row['clipart_category_id']) echo "selected=selected"; ?>><?php echo '&nbsp;&nbsp;&nbsp;' . $sub['name']; ?></option>
					<?php } ?>
				    <?php } ?>
				<?php } ?>
			    </select>
			</div>
		    </div>	
		    <?php if (PACKAGE_TYPE == 'PRO') { ?>
		    <?php if($clipart_row['is_clipart_design'] == '1') { ?>
		    <input type="hidden" name="clipart_price" value="0" />
		    <div class="field-set" id="clipart_upload">
			<label>File</label>
			<div class="input-field">
			    <a style="background-color: #e0e0e0; color: #505050; padding: 10px;" href="<?php echo BASE_ADMIN_URL.'cliparts/customize/'.$clipart_row['clipart_id']; ?>">Load Template Builder</a><br /><br />
			    <?php if($clipart_row['clipart_image'] != '') { ?>
				<object id="thumb" type="image/svg+xml" data="<?php echo base_url('assets/images/cliparts/' . $clipart_row['clipart_image']); ?>" height="150" width="150"></object>
			    <?php } ?>
			</div>
		    </div>
		    <?php } else { ?> 
		    <div class="field-set" id="clipart_design">
			<label>&nbsp;</label>
			<div class="input-field">
			    <input type="hidden" name="is_clipart_design" value="0" />
			    <input id="file_upload" name="file_upload" type="file"/><br />
			    <?php if($clipart_row['clipart_image'] != '') { ?>
				<object id="thumb" type="image/svg+xml" data="<?php echo base_url('assets/images/cliparts/' . $clipart_row['clipart_image']); ?>" height="150" width="150"></object>
			    <?php } ?>
			</div>
		    </div>
		    
		    <div class="field-set" id="clipart_price">
			<label>Clipart Price</label>
			<div class="input-field" style="margin-top:4px;">
			    <input type="text" class="required"  name="clipart_price" value="<?php echo $clipart_row['clipart_price']; ?>" placeholder="" />
			</div>
		    </div>
		    <?php } } else { ?>
		    <div class="field-set" id="clipart_design">
			<label>&nbsp;</label>
			<div class="input-field">
			    <input type="hidden" name="is_clipart_design" value="0" />
			    <input id="file_upload" name="file_upload" type="file"/><br />
			    <?php if($clipart_row['clipart_image'] != '') { ?>
				<object id="thumb" type="image/svg+xml" data="<?php echo base_url('assets/images/cliparts/' . $clipart_row['clipart_image']); ?>" height="150" width="150"></object>
			    <?php } ?>
			</div>
		    </div>
		    
		    <div class="field-set" id="clipart_price">
			<label>Clipart Price</label>
			<div class="input-field" style="margin-top:4px;">
			    <input type="text" class="required"  name="clipart_price"  value="<?php echo $clipart_row['clipart_price']; ?>" placeholder="" />
			</div>
		    </div>
		    <?php } ?>
    		    

		    
		     
		    <div class="field-set">
			<label>Status</label>
			<div class="input-field" style="margin-top:10px;">
			    <fieldset class="switch">
				<label class="off">Inactive<input type="radio" class="on_off" name="status" value="0"/></label>
				<label class="on">Active<input type="radio" class="on_off" name="status" value="1"/></label>
			    </fieldset>
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
<script>
    $(document).ready(function() {	   
	$("#editClipartForm").validate({
	    rules: {
		clipart_category_id: {
		    required:true
		},
		clipart_price: {
		    number:true
		},
		file_upload: {
		    extension: 'svg|SVG'
		}
	    },
	    messages: {
		file_upload: {
		    extension: 'Please upload SVG file only..!'
		}
	    }
	});  
	$("[name^=clipart_name]").each(function() {
	    $(this).rules('add', {required: true,maxlength: 50, messages: {maxlength: "Maximum 50 characters allowed."}});
	});
	
	$('.switch').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off').css('display','none');
	$('.on, .off').css('text-indent','-10000px');
<?php if ($clipart_row['status'] != '0') { ?>
    	$('.switch').css('background-position', 'left');
    	$("input[name=status][value=1]").attr('checked', 'checked');
<?php } else { ?>
    	$('.switch').css('background-position', 'right');
    	$("input[name=status][value=0]").attr('checked', 'checked');
<?php } ?>
	$("input[name=status]").change(function() {
	    var button = $(this).val();

	    if(button == '0'){ $('.switch').css('background-position', 'right'); }
	    if(button == '1'){ $('.switch').css('background-position', 'left'); }	   

	});
    });
</script>
