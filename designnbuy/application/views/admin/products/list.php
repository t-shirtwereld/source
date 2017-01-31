
<div class="pc_contten">
    <div class="pc_top">
        <h1>Products</h1>
	<div class="pc_rgt" style="width:300px; border-left:none;">
	    <?php if ($stores) { ?>
    	    <span>Select Store</span>
    	    <div class="input-field top_partrgt">
    		<select id="store_change">
		    <option value="">Select Store</option>
			<?php foreach ($stores as $store) { ?>
			    <option value="<?php echo $store['store_id']; ?>" <?php if(isset($store_id) && $store_id == $store['store_id']) { echo "selected=selected"; } ?>><?php echo $store['name']; ?></option>
			<?php } ?>
    		</select>
    	    </div>
	    <?php } ?>   
	</div>
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
 <?php if((isset($store_id) && $store_id !='') || empty($stores)) { ?>
    <div class="pc_search">
	<form name="search" action="<?php echo BASE_ADMIN_URL . 'products/index/'; ?>" method="get" style="float:right;">

	    <div class="switcher">
		<input id="global_search" name="keyword" class="input-text" value="<?php echo $keyword; ?>" type="text" placeholder="Search" />
		<input type="hidden" name="store_id" value="<?php echo $store_id; ?>" />
		<button id="search" class="btn" name="send" title="Search" type="submit"><img src="<?php echo base_url('assets/images/search-icn.svg'); ?>" alt=""></button>
		<a href="<?php echo BASE_ADMIN_URL . 'products'; ?>" style="float: right; margin: 0px 8px; line-height: 36px;"><img src="<?php echo base_url('assets/images/recet.png'); ?>" alt=""></a>
	    </div>
	    <div class="top_dropdown">
		<div class="input-field">
		    <?php if ($categories) {
			echo $categories;
		    } ?>
		</div>
		<div class="input-field">
<?php if ($status) {
    echo $status;
} ?>
		</div>
	    </div>  
	</form>
    </div>
   
    <table class="pc_reference table-shadow" cellpadding="0" cellspacing="0">
	<tr>
            <th class="width-sm" style="width:5%;" >Id</th>
            <th style="width:10%;">SKU</th>
            <th style="width:10%;">Image</th>
            <th style="width:20%;">Name</th>
            <th style="width:20%;">Category</th>
           <!-- <th style="width:15%;">Shop</th> -->
            <th style="width:7%;">Status</th> 
            <th class="width-sm" style="width:7%;">Actions</th>
	</tr>
	<?php
	if (!empty($products)) {

	    foreach ($products as $product) {
		?>
		<tr>
	            <td><?php echo $product['id']; ?></td>
	            <td><?php echo $product['sku']; ?></td>
	            <td><img src="<?php echo $product['image']; ?>"></td>
	            <td><?php echo $product['name']; ?></td>
	            <td><?php echo $product['category_name']; ?></td>
		<!--    <td><?php //echo $product['shop']; ?></td> -->
	            <td><?php echo $product['status']; ?></td>
		    <?php if(isset($store_id) && $store_id != '') { ?>
	            <td><a href="<?php echo BASE_ADMIN_URL . 'products/configuration/?store_id=' . $store_id . '&product_id='. $product['id']; ?>">Configuration</a></td>
		    <?php } else { ?>
		    <td><a href="<?php echo BASE_ADMIN_URL . 'products/configuration/?product_id='. $product['id']; ?>">Configuration</a></td>
		    <?php } ?>
		</tr>
		<?php
	    }
	} else {
	    ?> 
    	<tr>
    	    <td colspan="8"><p style="text-align:center"; >No Records Found! </p></td>
    	</tr>

<?php } ?>
    </table>
    <div class="pc_box">
	<div class="pc_pagination">
	    <ul>
		<?php
		foreach ($links as $link) {
		    echo "<li>" . $link . "</li>";
		}
		?>
	    </ul>
	</div>
	<div class="top_dropdown">
	    <div class="input-field">View
		<select id="limit" name="limit">
		    <option value="25" <?php if ($limit == '25') {
		    echo "selected='selected'";
		} ?>>25</option>
		    <option value="50" <?php if ($limit == '50') {
		    echo "selected='selected'";
		} ?>>50</option>
		    <option value="100" <?php if ($limit == '100') {
		    echo "selected='selected'";
		} ?>>100</option>
		    <option value="200" <?php if ($limit == '200') {
		    echo "selected='selected'";
		} ?>>200</option>
		    <option value="<?php echo $total_rows; ?>" <?php if ($limit == $total_rows) {
		    echo "selected='selected'";
		} ?>>All</option>       
		</select>   
		Per Page</div>
	</div>
    </div>
    <?php } else { ?>
	<table class="pc_reference table-shadow" cellpadding="0" cellspacing="0">
	<tr>
	<td><p style="text-align:center;">Please Select The Store.</p></td>
	</tr>
	</table>
    <?php } ?>
</div>

<script>

    $( document ).ready(function() {
     
	$('#messages_product_view').fadeIn().delay(10000).fadeOut();
	// bind change event to select
	$('#limit').bind('change', function () {
	    var limit_val = $(this).find(":selected").val();
		var store_id = $('#store_change').val();
	    var url = "<?php echo BASE_ADMIN_URL . 'products/index?limit='; ?>"+limit_val+"&store_id="+store_id;

	    if (url) { // require a URL
		window.location = url; // redirect
	    }
	    return false;
	});
	
	$('#store_change').on('change', function () {
          var store_id = $(this).val(); // get selected value
	  var url = "<?php echo BASE_ADMIN_URL . 'products/index?store_id='; ?>"+store_id;
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });

    });
</script>
