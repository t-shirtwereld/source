<div class="pc_contten">
    <div class="pc_top">
        <h1>Add Artwork Area Size</h1>
		<div class="pc_rgt" style="margin-left: 10px;"><a href="<?php echo BASE_ADMIN_URL . 'sqarea'; ?>">Artwork Area Size Listing</a></div>
		<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'sqarea/importcsv'; ?>">Import Artwork Area Sizes</a></div>
    </div>

    <?php
    $error_msg = '';
    if ($this->session->flashdata('error')) {
	$error_msg = $this->session->flashdata('error');
    } else {
	$error_msg = validation_errors();
    }
    ?>
    <?php if ($error_msg != '') { ?>
        <div id="messages_product_view">
    	<ul class="messages">
    	    <li class="error-msg">
		    <?php echo $error_msg; ?>
    	    </li>
    	</ul>
        </div>
    <?php } ?>

    <div>&nbsp;</div>

    <div>
	<div class="pc_contten box-box">
	    <form id="addForm" action="<?php echo BASE_ADMIN_URL . 'sqarea/submitdata'; ?>" method="post" class="col-two">
		<input type="hidden" name="sqarea_id" value="" />
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<label>Square Area</label>
			<div class="field-form">
			    <div class="input-field validationclass">
				<input type="text" name="square_area" value="" placeholder="" />
			    </div>
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
	$("#addForm").validate({
	    rules: {
		square_area: {
		    required:true,
		    number:true,
		    min: 1,
		    maxlength:5
		}
	    },
	    messages: {
		square_area: {
		    maxlength:"Maximum 5 numbers allowed."
		}
	    }
	});
    });
</script>