<div class="pc_contten">
    <div class="pc_top">
        <h1>Languages</h1>
	<div class="pc_rgt" style="margin-left: 10px;"><a href="<?php echo BASE_ADMIN_URL . 'language/addlanguage'; ?>">Add New Language</a></div>
    </div>

    <div class="pc_search">
	<!--	<div class="switcher">
		    <form name="search" action="<?php echo BASE_ADMIN_URL . 'language/index/'; ?>" method="get">
			<input id="global_search" name="keyword" class="input-text" type="text" placeholder="Search" value="<?php echo $keyword; ?>"/>
			<button id="search" class="btn" name="send" title="Search" type="submit"><img src="<?php echo base_url('assets/images/search-icn.svg'); ?>" alt=""></button>
			<a href="<?php echo BASE_ADMIN_URL . 'language'; ?>">Reset</a>
		    </form>
		</div> -->
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

    <table class="pc_reference table-shadow" cellpadding="0" cellspacing="0">
	<tr>
	    <th style="width:10%;">Id</th>
            <th style="width:20%;">Image</th>
	    <th style="width:30%;">Name</th>
	    <th style="width:20%;">Code</th>
	    <th style="width:10%;">Status</th>
            <th style="width:10%;">Actions</th>
	</tr>
	<?php
	if (!empty($languages)) {
	    foreach ($languages as $l) {
		?>
		<tr>
		    <td><?php echo $l['language_id']; ?></td>
		    <td><img src="<?php echo base_url('assets/flags/' . $l['image']); ?>" style="width:16px !important; height:16px;"/></td> 
		    <td><?php echo $l['name']; ?></td>
		    <td><?php echo $l['language_code']; ?></td>
		    <td><?php echo $l['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
		    <td><a href="<?php echo BASE_ADMIN_URL . 'language/editlanguage/' . $l['language_id']; ?>">Edit</a>&nbsp;&nbsp;&nbsp;<a class="delete" href="<?php echo BASE_ADMIN_URL . 'language/deletelanguage/' . $l['language_id']; ?>">Delete</a></td>
		</tr>
		<?php
	    }
	} else {
	    ?>
    	<tr><td colspan="6" style="text-align:center;">No Records Found!</td></tr>
	<?php } ?>
    </table>
    <!--  <div class="pc_box">
	    <div class="pc_pagination">
		<ul>
	    
		</ul>
	    </div>
	</div> -->
</div>
<script>
    $( document ).ready(function() {
	
	$('#messages_product_view').fadeIn().delay(10000).fadeOut();
	
    });
</script>

