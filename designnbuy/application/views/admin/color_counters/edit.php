<div class="pc_contten">
    <div class="pc_top">
        <h1>Edit Color Counter</h1>
	<div class="pc_rgt" style="margin-left: 10px;"><a href="<?php echo BASE_ADMIN_URL . 'color_counters'; ?>">Color Counter Listing</a></div>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'color_counters/importcsv'; ?>">Import Color Counter</a></div>
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
	    <form id="editForm" action="<?php echo BASE_ADMIN_URL . 'color_counters/submitdata'; ?>" method="post" class="col-two">
		<input type="hidden" name="color_counter_id" value="<?php echo $color_counter_row['color_counter_id'] ?>" />
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<label>Color Counter</label>
			<div class="field-form">
			    <div class="input-field validationclass">
				<input type="text" name="color_counter" value="<?php echo $color_counter_row['color_counter'] ?>" placeholder="" />
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
	$("#editForm").validate({
	    rules: {
		color_counter: {
		    required:true,
		    number:true,
		    min: 1,
		    maxlength:5,
		    digits: true
		}
	    },
	    messages: {
		color_counter: {
		    maxlength:"Maximum 5 numbers allowed.",
		    digits: "Decimal Values are not allowed"
		}
	    }
	});
    });
</script>