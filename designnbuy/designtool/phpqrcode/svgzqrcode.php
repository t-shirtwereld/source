 <?php

    include('qrlib.php');
    include('config.php');
    
    // Configuring SVG
    
    $dataText   = 'PHP QR Code :)';
    $svgTagId   = 'id-of-svg';
    $saveToFile = false;
    $imageWidth = 250; // px
    
    // SVG file format support
    $svgCode = QRcode::svg($dataText, $svgTagId, $saveToFile, QR_ECLEVEL_L, $imageWidth);
    echo "<pre>";
	print_r($svgCode);
    echo $svgCode; 