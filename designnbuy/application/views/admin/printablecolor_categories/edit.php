<div class="pc_contten">
    <div class="pc_top">
        <h1>Edit Color Category</h1>
	<div class="pc_rgt" style="margin-left: 10px;"><a href="<?php echo BASE_ADMIN_URL . 'printablecolor_categories'; ?>"> Color Categories</a></div>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'printablecolor_categories/importcsv'; ?>">Import Color Categories</a></div>
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
	    <form id="editCategoryForm" action="<?php echo BASE_ADMIN_URL . 'printablecolor_categories/submitdata'; ?>" method="post" class="col-two">
		<input type="hidden" name="printablecolor_category_id" value="<?php echo $category_row['printablecolor_category_id']; ?>" />
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<div class="field-lable"><label>Name</label></div>
			<div class="field-form">
			    <div class="input-field validationclass">
				<input type="text" name="name" value="<?php echo htmlspecialchars($category_row['category_name']); ?>" placeholder="" />
			    </div>
			    <div class="input-field">
			    </div>
			</div>
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

		</div>
	    </form>
	</div>
    </div>
</div>
<div class="clearfix"></div>
<script>
    $(document).ready(function() {	   
	$("#editCategoryForm").validate({
	    rules: {
		name: {
		    required:true,
		    maxlength: 50
		}
	    },
	    messages: {
		name: {
		    maxlength: "Maximum 50 characters allowed."
		}
	    }
	}); 
	
	$('.switch').css('background', 'url("<?php echo base_url('assets/images/switch.png'); ?>")');
	$('.on_off').css('display','none');
	$('.on, .off').css('text-indent','-10000px');
<?php if ($category_row['status'] != '0') { ?>
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
