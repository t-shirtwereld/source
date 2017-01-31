
<div class="input-area" style="margin-bottom:30px;">
<input type="hidden" id="designnbuy_threed_id" name="designnbuy_threed_id" value="<?php echo $product_3d['designnbuy_threed_id']; ?>">
<div class="field-set">
<label>3D Modal</label>
<div class="input-field">
    <input type="hidden" id="modal_image" name="modal_image" value="<?php echo $product_3d['modal_image']; ?>">
    <input type="hidden" id="image" name="image">
    <input type="file" id="uploadify_3d">
    <div id="uploadify_image_3d">	
	    <?php 
	        if(!empty($product_3d['modal_image'])){
	           echo $product_3d['modal_image'];
	        }
	     ?>
    </div>
</div>
</div>	
 <script type="text/javascript">
	$(function() {
	    $('#uploadify_3d').uploadify({
	        uploader: "<?php echo base_url('assets/swf/uploadify.swf'); ?>",
	        script: "<?php  echo base_url();?>admin/products/uploadify",
	        cancelImg: "<?php echo base_url('assets/images/close.gif'); ?>",
	        auto: true,
	        multi: false,
	        sizeLimit : '8388608',
	        fileExt: "*.obj",
	        fileDesc: 'obj Files only',
	        onComplete: function(a,b,c,d) {
	          if(d!=1){
	               $('#uploadify_image_3d').text(d);
	              $('#modal_image').val(d);
	           }else{
	              alert("You have uploaded wrong file.");
	              $('#uploadify_image_3d').attr('src', "<?php echo base_url('/uploads/productimage') ?>/default.jpg");
	              $('#modal_image').val("");
	           }
	        }
	    }); 
	});
</script> 
<div class="field-set">
<label>Map Image</label>
<div class="input-field">
    <input type="hidden" id="map_image" name="map_image" value="<?php echo $product_3d['map_image']; ?>">
    <input type="hidden" id="image1" name="image1">
    <input type="file" id="uploadify_map">
    <div class="productimage">
     <img class="toolimage" src="<?php echo base_url('/uploads/productimage') ?>/<?php if(empty($product_3d['map_image'])){ echo 'default.jpg'; }else{ echo $product_3d['map_image']; } ?>" id="uploadify_image_map_image" width="100" height="100" style="border: 1px solid #ddd;">
   </div>
</div>
</div>	
 <script type="text/javascript">
    $(function() {
        $('#uploadify_map').uploadify({
            uploader: "<?php echo base_url('assets/swf/uploadify.swf'); ?>",
            script: "<?php  echo base_url();?>admin/products/uploadify",
            cancelImg: "<?php echo base_url('assets/images/close.gif'); ?>",
            auto: true,
            multi: false,
            sizeLimit : '8388608',
            fileExt: "*.jpg;*.jpeg;*.png",
            fileDesc: 'Web Image Files (.JPG or .PNG)',
            onComplete: function(a,b,c,d) {
              if(d!=1){
                  $('#uploadify_image_map_image').attr('src', "<?php echo base_url('/uploads/productimage') ?>/" + d  );
                  $('#map_image').val(d);
               }else{
                  alert("You have uploaded wrong file.");
                  $('#uploadify_image_map_image').attr('src', "<?php echo base_url('/uploads/productimage') ?>/default.jpg");
                  $('#map_image').val("");
               }
            }
        }); 
    });
 </script> 
<?php  
   if(isset($no_of_side) && isset($product_id) && $product_id != '')
   {
?>
    <script>
  var product_id = '<?php echo $product_id; ?>';
  var noofSide = '1';
  var jasonStrthreed = '<?php echo $threedcoordinates; ?>';
  var saveCoordinate3d = '<?php echo get_base_url().'designnbuy/admin/products/save3dCoordinate/'; ?>';
  
    </script> 
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/styleHandler.css'); ?>" />

  <script src="<?php echo base_url('assets/js/handler-3d.js'); ?>" type="text/javascript"></script> 
    <?php } else { ?> 
        <p>Please first upload product images and than edit product or make product customizable</p>
<?php    } ?>

</div>
<style type="text/css">
#savebtnCon3d #saveButton_{ float: right;}
</style>
