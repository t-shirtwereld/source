
<div class="pc_contten">
    <div class="pc_top">
        <h1>Product Configuration</h1>
    <div class="pc_rgt"><a href="<?php echo base_url(); ?>admin/products/">Products</a></div>
</div>
<?php if($this->session->flashdata('msg')){ ?>
<div id="messages_product_view">
  <ul class="messages">
      <li class="error-msg">
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
      <div id="tabs-container">

                <ul class="tabs-menu">
                    <li class="current"><a href="#tab-1">General Configuration</a></li>
                    <li><a href="#tab-2">Images</a></li>
                    <?php if(!empty($productimages)): ?>
                    <li><a href="#tab-3">Configure Design Area</a></li>
                   <?php endif; ?>
                    <li <?php if($is_pretemplate=='yes'): ?> style="display:block;" <?php endif; ?> <?php if($is_pretemplate=='no' || $is_pretemplate=='' ): ?> style="display:none;" <?php endif; ?> ><a href="#tab-4">Preload Template</a></li>
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

                    <div id="tab-4" class="tab-content">
                         <?php $this->load->view('admin/products/preloadtemplates'); ?>
                    </div>
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
        base_unit: "required",
        width:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        height:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        cut_1:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        cut_2:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        cut_3:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        cut_4:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        bleed_1:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        bleed_2:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        bleed_3:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        bleed_4:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        corner_radius:{
            required: "input[name='is_canvas'][value='yes']:checked",
            number: true
        },
        color_picker_type:{
            required: "input[name='is_canvas'][value='yes']:checked",
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
        },
        messages: {
        no_of_sides: "This is required field.",
        base_unit: "This is required field.",
        width: {
            required : "This is required field.",
            number : "Please enter only decimal values"
        },
        height: {
            required : "This is required field.",
            number : "Please enter only decimal values"
        },
        cut_1: {
            required : "Required",
            number : "Please enter only decimal values"
        },
        cut_2: {
            required : "Required.",
            number : "Please enter only decimal values"
        },
        cut_3: {
            required : "Required",
            number : "Please enter only decimal values"
        },
        cut_4: {
            required : "Required",
            number : "Please enter only decimal values"
        },
        bleed_1: {
            required : "Required",
            number : "Please enter only decimal values"
        },
        bleed_2: {
            required : "Required.",
            number : "Please enter only decimal values"
        },
        bleed_3: {
            required : "Required.",
            number : "Please enter only decimal values"
        },
        bleed_4: {
            required : "Required.",
            number : "Please enter only decimal values"
        },
        corner_radius: {
            required : "This is required field.",
            number : "Please enter only decimal values"
        },
        color_picker_type: "This is required field.",
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
        $('input[name="is_canvas"]').click(function(){
            if($(this).attr("value")=="yes"){
                $("#advancesettings").show();
            }
            if($(this).attr("value")=="no"){
                $("#advancesettings").hide();
            }
        });
    });
</script>
