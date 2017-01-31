<?php  
   if(isset($no_of_side) && isset($product_id) && $product_id != '')
   {
?>
    <script>
	var product_id = '<?php echo $product_id; ?>';
	var noofSide = '<?php echo $no_of_side; ?>';
	var jasoncord = '<?php echo $coordinates; ?>';
	var saveCoordinateUrl = '<?php echo get_base_url().'designnbuy/admin/products/saveCoordinate/'; ?>';
	
    </script>	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/styleHandler.css'); ?>" />
	<script src="<?php echo base_url('assets/js/interact-1.2.4.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/js/handler.js'); ?>" type="text/javascript"></script>
    <?php } else { ?> 
		    <p>Please first upload product images and than edit product or make product customizable</p>
<?php    } ?>