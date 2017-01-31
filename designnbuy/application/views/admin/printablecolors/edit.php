<div class="pc_contten">
    <div class="pc_top">
        <h1>Edit Printable Color</h1>
	<div class="pc_rgt" style="margin-left: 10px;"><a href="<?php echo BASE_ADMIN_URL . 'printablecolors'; ?>">Printable Color Listing</a></div>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'printablecolors/importcsv'; ?>">Import Printable Colors</a></div>

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
	    <form id="editColorForm" action="<?php echo BASE_ADMIN_URL . 'printablecolors/submitdata'; ?>" method="post" class="col-two">
		<input type="hidden" name="printablecolor_id" value="<?php echo $printablecolor_row['printablecolor_id']; ?>" />
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<div class="field-lable"><label>Name</label></div>
			<div class="field-form">
			    <?php foreach ($languages as $language) { ?>
    			    <div class="input-field validationclass">
    				<input type="text" name="printablecolor_name[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($printablecolor_names[$language['language_id']]) ? htmlspecialchars($printablecolor_names[$language['language_id']]['name']) : ''; ?>" placeholder="" />
    				<span><img src="<?php echo base_url('assets/flags/' . $language['image']); ?>" alt=""></span>
    			    </div>
			    <?php } ?>
			</div>
		    </div>
		    <div class="field-set">
			<label>Printablecolor Categories</label>
			<div class="input-field">
			    <select id="printablecolor_category_id" name="printablecolor_category_id">
				<option value="">Select Printablecolor Category</option>
				<?php foreach ($categories as $category) { ?>
				<?php if($category['status'] != '1') { ?>
				    <option value="<?php echo $category['printablecolor_category_id']; ?>" <?php if ($category['printablecolor_category_id'] == $printablecolor_row['printablecolor_category_id']) echo "selected=selected"; ?>><?php echo $category['category_name']. '(Inactive)'; ?></option>
				<?php } else { ?>
				    <option value="<?php echo $category['printablecolor_category_id']; ?>" <?php if ($category['printablecolor_category_id'] == $printablecolor_row['printablecolor_category_id']) echo "selected=selected"; ?>><?php echo $category['category_name']; ?></option>
				<?php } ?>
    				
				<?php } ?>
			    </select>
			</div>
		    </div>
		    <div class="field-set">
			<label>Color Code</label>
			<div class="input-field">
			    <input class="fixed-width-sm" type="color" value="<?php echo $printablecolor_row['color_code']; ?>" name="color_code" id="color_code"> <span class="color-code" id="span_color_name"><?php echo $printablecolor_row['color_code']; ?></span>
			</div>
		    </div>
		 <!--   <div class="field-set">
			<label>&nbsp;</label>
			<div class="input-field">
			    C &nbsp;<input class="inpu-centr" type="text" value="<?php echo $printablecolor_row['c']; ?>" name="c" style="width:30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			    M &nbsp;<input class="inpu-centr" type="text" value="<?php echo $printablecolor_row['m']; ?>" name="m" style="width:30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			    Y &nbsp;<input class="inpu-centr" type="text" value="<?php echo $printablecolor_row['y']; ?>" name="y" style="width:30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			    K &nbsp;<input class="inpu-centr" type="text" value="<?php echo $printablecolor_row['k']; ?>" name="k" style="width:30px">
			</div>
		    </div> -->
		    <input class="inpu-centr" type="hidden" value="" name="c">
		    <input class="inpu-centr" type="hidden" value="" name="m">
		    <input class="inpu-centr" type="hidden" value="" name="y">
		    <input class="inpu-centr" type="hidden" value="" name="k">
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
	$("#color_code").on('change', function() {
	    $("#span_color_name").text($(this).val());
	});
	
	$("#editColorForm").validate({
	    rules: {
		printablecolor_category_id: {
		    required:true
		}
	    }
	});
	$("[name^=printablecolor_name]").each(function() {
	    $(this).rules('add', {required: true,maxlength: 50, messages: {maxlength: "Maximum 50 characters allowed."}});
	});
	
	
	$('.switch').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off').css('display','none');
	$('.on, .off').css('text-indent','-10000px');
<?php if ($printablecolor_row['status'] != '0') { ?>
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
