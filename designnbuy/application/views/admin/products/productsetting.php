<div class="pc_contten">
    <div class="pc_top">
        <h1>Product Configuration</h1>
	<div class="pc_rgt"><a href="<?php echo base_url(); ?>admin/products/">Products</a></div>
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
    <div id="error-message"></div>
    <div>&nbsp;</div>
    <div>
	<div class="pc_contten box-box box-bgnone"> 
	    <form name="configuration" id="productsetting" action="<?php echo BASE_ADMIN_URL . 'products/submitdata/'; ?>" method="post">
		<input type="hidden" name="store_id" value="<?php echo $store_id; ?>" />
		<div id="tabs-container">

		    <ul class="tabs-menu">
			<li class="current"><a href="#tab-1">General Configuration</a></li>
			<li><a href="#tab-2">Images</a></li>
			<?php if (!empty($productimages)): ?>
    			<li><a href="#tab-3">Configure Design Area</a></li>
			<?php endif; ?>
			<?php if ($is_pretemplate == 'yes' && $configurefeature['9'] == '1' && PACKAGE_TYPE == 'PRO'): ?>
    			<li><a id="iframe" href="#tab-4">Preload Template</a></li>
			<?php endif; ?>
			<?php // ADDED BY SOMIN */ ?>
			<?php if ($is_3d == '1' && $configurefeature['9'] == '1' && PACKAGE_TYPE == 'PRO'): ?>
    			<li><a  href="#tab-5">3D Setting</a></li>
			<?php endif; ?>
			<?php // ADDED BY SOMIN */ ?>
		    </ul>
		    <div class="tab">
			<div id="tab-1" class="tab-content">
			    <div class="col-two" >
				<?php $this->load->view('admin/products/settings'); ?>
			    </div>
			</div>
			<div id="tab-2" class="tab-content">
			    <?php $this->load->view('admin/products/productimage'); ?>
			</div>
			<div id="tab-3" class="tab-content">
			    <?php $this->load->view('admin/products/configarea'); ?>
			</div>
			<?php if(PACKAGE_TYPE == 'PRO' && $is_pretemplate == 'yes' && $configurefeature['9'] == '1') { ?>
			<div id="tab-4" class="tab-content">
			    <?php $this->load->view('admin/products/preloadtemplates'); ?>
			</div>
			<?php } ?>
			<?php // ADDED BY SOMIN */ ?>
			<?php if(PACKAGE_TYPE == 'PRO' && $is_3d == '1' && $configurefeature['9'] == '1') { ?>
			<div id="tab-5" class="tab-content">
			   <?php $this->load->view('admin/products/3dproduct'); ?>
			</div>
			<?php } ?>
			<?php // ADDED BY SOMIN */ ?>
		    </div>
		</div>
		<div class="clearfix"></div>


		<input type="submit" class="btn submit" value="Submit"> 


	    </form>
	</div>

    </div>
    <div class="clearfix"></div>
</div>
<script>    
    $(document).ready(function() {     
	$("#productsetting").validate({
	    ignore: "",
	    rules: {
		no_of_sides: "required",
		side_1_label: {
		    required: "input[name='global_side_label'][value='0']:checked",
		    maxlength: 50
		},
		side_2_label: {
		    required: "input[name='global_side_label'][value='0']:checked",
		    maxlength: 50
		},
		side_3_label: {
		    required: "input[name='global_side_label'][value='0']:checked",
		    maxlength: 50
		},
		side_4_label: {
		    required: "input[name='global_side_label'][value='0']:checked",
		    maxlength: 50
		},
		side_5_label: {
		    required: "input[name='global_side_label'][value='0']:checked",
		    maxlength: 50
		},
		side_6_label: {
		    required: "input[name='global_side_label'][value='0']:checked",
		    maxlength: 50
		},
		side_7_label: {
		    required: "input[name='global_side_label'][value='0']:checked",
		    maxlength: 50
		},
		side_8_label: {
		    required: "input[name='global_side_label'][value='0']:checked",
		    maxlength: 50
		}
	    },
	    invalidHandler: function(form, validator) {
		var errors = validator.numberOfInvalids();

		if (errors) {
		    $("#error-message").show().text("Warning: Please check the form carefully for errors!");
		    $("#error-message").addClass('validationerror');

		} else {
		    $("#error-message").hide();
		}
	    }
	}); 
	var myRule = {
	    required: true,
	    messages: {
		required: "This is required field."
	    }
	}   
	$(".printing_method_name").each(function() {
	    $(this).rules('add', myRule);
	});  
        
    });
</script> 
<script type="text/javascript">
    $(document).ready(function(){
	<?php if($product_settings['global_side_label'] == '1') { ?>	    
	    $("#display_global_side_label").hide();
	<?php } else { ?>
	    $("#display_global_side_label").show();
	<?php } ?>
	$('input[name="global_side_label"]').click(function(){
            if($(this).attr("value")=="1"){
                $("#display_global_side_label").hide();
            }
            if($(this).attr("value")=="0"){
                $("#display_global_side_label").show();
            }
        });
    });
</script>
