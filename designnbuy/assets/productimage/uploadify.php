<?php 

if($_FILES['Filedata']['error']==0) {
			
			$filename = $_FILES['Filedata']['name'];
			//$filename = uniqid().'.'.$file->ext();
			//$filepath = 'uploads/'.$filename;
			$filepath = $_SERVER['DOCUMENT_ROOT'] . "/woodesignerhtml5_canvas/designnbuy/assets/productimage/".$filename;
			move_uploaded_file($_FILES['Filedata']['tmp_name'], $filepath);
			echo $filename;
		} else echo 'false';

		?>