<?php if(isset($product_id) && $product_id != '') { ?>
    <iframe name="Pretemplate" id="pretemplate" src="" width="100%" height="850px" frameborder="0" ></iframe> 
<?php } ?>
<script type="text/javascript">
$("#iframe").click(function () { 	
      $("#pretemplate").attr("src", "<?php echo get_base_url().'designnbuy/pcstudio/preTemplate/?product_id='.$product_id; ?>");
});
</script>
