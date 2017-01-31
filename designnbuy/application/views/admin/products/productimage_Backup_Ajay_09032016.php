 <?php if(!empty($no_of_side)): ?>
 <table class="pc_reference table-shadow imageclass">
       <tr>
           <th class="width-sm">Side</th>
           <th style="width:15%">Product image</th>
           <th style="width:15%">Mask image</th>
           <th style="width:15%">Overlay image</th>
       </tr>

       <?php for($i=1,$j=1; $i<=8; $i++, $j++){?>

       <tr>
        <td>Side <?php echo $j; ?></td>
        <td>
            <input type="hidden" id="file_name_product<?php echo $i; ?>" name="side<?php echo $i; ?>_product" value="<?php echo $productimages['side'.$i.'_product']; ?>">
            <input type="hidden" id="image" name="image">
            <input type="file" id="uploadify_product<?php echo $i; ?>">
            <div class="productimage">
            <?php if(empty($productimages['side'.$i.'_product'])){ ?> <?php }else{ ?> <a href="javascript:;" class="cancel" id="cancel_product<?php echo $i; ?>" onclick="deleteimage('side<?php echo $i; ?>_product',<?php echo $product_id; ?>,<?php echo $i; ?>,'product<?php echo $i; ?>')" ><img src="<?php echo base_url('/assets/images/close.gif') ?>" /></a> <?php } ?>
            <img class="toolimage" src="<?php echo base_url('/uploads/productimage') ?>/<?php if(empty($productimages['side'.$i.'_product'])){ echo 'default.jpg'; }else{ echo $productimages['side'.$i.'_product']; } ?>" id="uploadify_image_product<?php echo $i; ?>" width="100" height="100" style="border: 1px solid #ddd;"></div>
        </td>
        <td>
            <input type="hidden" id="file_name_mask<?php echo $j; ?>" name="side<?php echo $j; ?>_mask" value="<?php echo $productimages['side'.$i.'_mask']; ?>">
            <input type="hidden" id="image" name="image">
            <input type="file" id="uploadify_mask<?php echo $j; ?>">
            <div class="productimage">
            <?php if(empty($productimages['side'.$i.'_mask'])){ ?> <?php }else{ ?> <a href="javascript:;" id="cancel_mask<?php echo $i; ?>" onclick="deleteimage('side<?php echo $i; ?>_mask',<?php echo $product_id; ?>,<?php echo $i; ?>,'mask<?php echo $i; ?>')" ><img src="<?php echo base_url('/assets/images/close.gif') ?>" /></a> <?php } ?>
             <img class="toolimage" src="<?php echo base_url('/uploads/productimage') ?>/<?php if(empty($productimages['side'.$i.'_mask'])){ echo 'default.jpg'; }else{ echo $productimages['side'.$i.'_mask']; } ?>" id="uploadify_image_mask<?php echo $j; ?>" width="100" height="100" style="border: 1px solid #ddd;"></div>
        </td>
        <td>
           <input type="hidden" id="file_name_overlay<?php echo $j; ?>" name="side<?php echo $j; ?>_overlay"  value="<?php echo $productimages['side'.$i.'_overlay']; ?>">
            <input type="hidden" id="image" name="image">
            <input type="file" id="uploadify_overlay<?php echo $j; ?>">
            <div class="productimage"><?php if(empty($productimages['side'.$i.'_overlay'])){ ?> <?php }else{ ?> <a href="javascript:;" id="cancel_overlay<?php echo $j; ?>" onclick="deleteimage('side<?php echo $i; ?>_overlay',<?php echo $product_id; ?>,<?php echo $i; ?>,'overlay<?php echo $i; ?>')" ><img src="<?php echo base_url('/assets/images/close.gif') ?>" /></a> <?php } ?>
            <img class="toolimage" src="<?php echo base_url('/uploads/productimage') ?>/<?php if(empty($productimages['side'.$i.'_overlay'])){ echo 'default.jpg'; }else{ echo $productimages['side'.$i.'_overlay']; } ?>" id="uploadify_image_overlay<?php echo $j; ?>" width="100" height="100" style="border: 1px solid #ddd;"></div>
        </td>
       </tr>
        <script type="text/javascript">
            $(function() {
                $('#uploadify_product<?php echo $i; ?>').uploadify({
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
                           $('#uploadify_image_product<?php echo $i; ?>').attr('src', "<?php echo base_url('/uploads/productimage') ?>/" + d  );
                          $('#file_name_product<?php echo $i; ?>').val(d);
                       }else{
                          alert("You have uploaded wrong file.");
                          $('#uploadify_image_product<?php echo $i; ?>').attr('src', "<?php echo base_url('/uploads/productimage') ?>/default.jpg");
                          $('#file_name_product<?php echo $i; ?>').val("");
                       }
                    }
                }); 
            });
            </script>
            <script type="text/javascript">
            $(function() {
                $('#uploadify_mask<?php echo $j; ?>').uploadify({
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
                           $('#uploadify_image_mask<?php echo $j; ?>').attr('src', "<?php echo base_url('/uploads/productimage') ?>/" + d  );
                           $('#file_name_mask<?php echo $j; ?>').val(d);
                       }else{
                          alert("You have uploaded wrong file.");
                          $('#uploadify_image_mask<?php echo $j; ?>').attr('src', "<?php echo base_url('/uploads/productimage') ?>/default.jpg");
                          $('#file_name_mask<?php echo $j; ?>').val("");

                       }
                    }
                }); 
            });
            </script>
             <script type="text/javascript">
            $(function() {
                $('#uploadify_overlay<?php echo $j; ?>').uploadify({
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
                        $('#uploadify_image_overlay<?php echo $j; ?>').attr('src', "<?php echo base_url('/uploads/productimage') ?>/" + d  );
                        $('#file_name_overlay<?php echo $j; ?>').val(d);
                      }else{
                          alert("You have uploaded wrong file.");
                          $('#uploadify_image_overlay<?php echo $j; ?>').attr('src', "<?php echo base_url('/uploads/productimage') ?>/default.jpg");
                          $('#file_name_overlay<?php echo $j; ?>').val("");
                       }
                    }
                }); 
            });
            </script>
       <?php } ?>
   </table>
   <script type="text/javascript">
   function deleteimage(imageid,row,num,side) {
    var that = $(this);
    var conf = confirm("Are you sure you want to delete this image?");
    if(conf == true){
     $.ajax({
           type: "POST",
           url: '<?php echo base_url(); ?>admin/products/deleteimage',
           data: {
                productid:row,
                side:imageid
            },
         success: function(data)
           {
              $("#uploadify_image_"+side).attr('src',"<?php echo base_url('/uploads/productimage') ?>/default.jpg");
              $("#cancel_"+side).hide();
              $('#file_name_'+side).val("");
           }
         });
      }
  }
</script>
<?php else: ?>
  <div id="messages_product_view">
  <ul class="messages">
      <li class="error-msg">
          You have to save general configuration to add image.
    </li>
  </ul>
</div>
<?php endif; ?>
<style type="text/css">
.productimage img.toolimage{
  width: 100px !important;
  height: 100px;
}
</style>