<div class="pc_contten">
    <div class="pc_top">
        <h1>Import Cliparts</h1>
	<div class="pc_rgt"><a href="<?php echo BASE_ADMIN_URL . 'cliparts'; ?>">Cliparts</a></div>
    </div>

    <?php
    $error_msg = '';
    if (isset($error) && $error != '') {
	$error_msg = $error;
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
	<div class="pc_contten box-box box-download">
	    <form action="<?php echo BASE_ADMIN_URL . 'cliparts/upload_importcsv'; ?>" method="post" class="col-two" enctype="multipart/form-data">
		<div class="input-area" style="margin-bottom:30px;">
		    <div class="field-set">
			<label>Upload CSV</label>
			    
			    <div class="input-field">
				<input type="file" name="clipart_csv" value=""/>    
				<p>How to import cliparts?</p>
				<p>Download the "Download sample format" file and edit the text as per your needed to import the clipart.</p>
				<p>You also need to upload all required clipart svg in the "designnbuy/assets/images/cliparts " folder.</p>
				<p>Note: File must be in "csv" extenstion and set the all cliparts permission to 777</p>
			    </div>

		    </div>
		    <div class="input-area">
			<div class="field-set">
			    <label>&nbsp;</label>
			    <div class="input-field">
				<input type="submit" class="btn" value="Import Cliparts" />
			    </div>
			</div>
		    </div>
		</div>
	    </form>
	    <a class="download-btn" href="<?php echo base_url('assets/images/importcsvsample/Clipart_data.csv'); ?>">Download Sample CSV Format</a>
	</div>
    </div>
</div>
<div class="clearfix"></div>