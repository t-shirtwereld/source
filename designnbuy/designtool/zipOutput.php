<?php  
require '../app/Mage.php';
Mage::app();

$isSvg = $_REQUEST['isSvg'];
$item_id = $_REQUEST['item_id'];

if(isset($item_id) && $isSvg == 1)
{	
	$filesToZip = array();
	
	$vectorImagePath = Mage::getBaseDir(). DS .'media' . DS .'cartimages'. DS;
	
	$_item = Mage::getModel('sales/order_item')->load($item_id);
	$productOption = $_item->getProductOptions();
	$savestr = $productOption['info_buyRequest']['savestr'];
	$svgFiles = explode(",",$savestr);
	echo "<pre>";
	print_r($svgFiles);
	foreach ($svgFiles as $svg):	
		if(file_exists($vectorImagePath.$svg)):			
		$filesToZip[] = 'cartimages/'.$svg;
		$svgfileContents = file_get_contents($vectorImagePath.$svg);  
		$doc = new DOMDocument();
		$dom->preserveWhiteSpace = False;
		$doc->loadXML($svgfileContents);
		foreach ($doc->getElementsByTagNameNS('http://www.w3.org/2000/svg', 'svg') as $element):
			foreach ($element->getElementsByTagName("*") as $tags):				
				if($tags->getAttribute('xlink:href')!=''):
					$imageUrl = $tags->getAttribute('xlink:href');
					$name = pathinfo($imageUrl, PATHINFO_FILENAME);
					$uploadedImage = explode('media/',$imageUrl);					
					if($uploadedImage[1]!=''):
						$filesToZip[] = $uploadedImage[1];
					endif;
				endif;
			endforeach;
		endforeach;
		endif;
	endforeach;	
	echo "<pre>";
	print_r($filesToZip);
	
	$result = zipFilesAndDownload($filesToZip,'my-archive123.zip');
}


/* creates a compressed zip file */
function zipFilesAndDownload($files = array(),$destination = '',$overwrite = false) {
	$mediaPath = Mage::getBaseDir(). DS .'media' . DS;
    //if the zip file already exists and overwrite is false, return false
    //if(file_exists($destination) && !$overwrite) { return false; }
    //vars
    $valid_files = array();
    //if files were passed in...
    if(is_array($files)) {
        //cycle through each file
        foreach($files as $file) {
            //make sure the file exists			
            if(file_exists($mediaPath.$file)) {
                $valid_files[] = $file;
            }
        }
    }	
    //if we have good files...
    if(count($valid_files)) {
        //create the archive
        $zip = new ZipArchive();        
		$res = $zip->open($destination, ZipArchive::CREATE );
		
		if ( $res === TRUE) {       
			//add the files
			foreach($valid_files as $file) {		
				echo basename($file);
				$zip->addFile($mediaPath.$file,basename($file));
				
			}
			$zip->close();
		} else {
			switch($res){
				case ZipArchive::ER_EXISTS:
					$ErrMsg = "File already exists.";
					break;

				case ZipArchive::ER_INCONS:
					$ErrMsg = "Zip archive inconsistent.";
					break;
				   
				case ZipArchive::ER_MEMORY:
					$ErrMsg = "Malloc failure.";
					break;
				   
				case ZipArchive::ER_NOENT:
					$ErrMsg = "No such file.";
					break;
				   
				case ZipArchive::ER_NOZIP:
					$ErrMsg = "Not a zip archive.";
					break;
				   
				case ZipArchive::ER_OPEN:
					$ErrMsg = "Can't open file.";
					break;
				   
				case ZipArchive::ER_READ:
					$ErrMsg = "Read error.";
					break;
				   
				case ZipArchive::ER_SEEK:
					$ErrMsg = "Seek error.";
					break;
			   
				default:
					$ErrMsg = "Unknow (Code $rOpen)";
					break;
				   
			   
			}
			 die( 'ZipArchive Error: ' . $ErrMsg);
		}
        
       
		header("Content-type: application/zip");
		header("Content-Disposition: attachment; filename=$destination");
		header("Pragma: no-cache");
		header("Expires: 0");
		readfile("$destination");
		exit;       
    }
    else
    {
        echo "No files to download.";
    }
}


?> 