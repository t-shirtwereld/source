(function() {
var currentSide="";
var imagewidth = 400;
var imageheight = 485;
var jasonStr = JSON.parse(jasoncord);
var tabId="#tab-3";
var saveCoordinate = saveCoordinateUrl;
var inputBoxX,inputBoxY,inputBoxW,inputBoxH,inputBoxOW,inputBoxOH;

console.log('saveCoordinate');
console.log(saveCoordinate);

/*var jasonStr = {
    "coordinate": [
        {
            "image_path": "http://192.168.0.139/work/print_commerce/pc-newtool/source/designnbuy/uploads/productimage/55549a71a1f16.png",
            "name": "Front",
            "X": "7.08",
            "Y": "3.56",
            "W": "11.73",
            "H": "17.33",
            "OW": "5.87",
            "OH": "8.66"
        },
        {
            "image_path": "http://192.168.0.139/work/print_commerce/pc-newtool/source/designnbuy/uploads/productimage/55549a7d65bb4.png",
            "name": "Back",
            "X": "6.75",
            "Y": "2.41",
            "W": "11.8",
            "H": "17.89",
            "OW": "5.9",
            "OH": "8.95"
        },
        {
            "image_path": "http://192.168.0.139/work/print_commerce/pc-newtool/source/designnbuy/uploads/productimage/55549a8271ab9.png",
            "name": "Left",
            "X": "6.99",
            "Y": "8.41",
            "W": "8.02",
            "H": "12.29",
            "OW": "4.01",
            "OH": "6.16"
        },
        {
            "image_path": "http://192.168.0.139/work/print_commerce/pc-newtool/source/designnbuy/uploads/productimage/55549a8777eeb.png",
            "name": "Right",
            "X": "9.33",
            "Y": "7.61",
            "W": "8.87",
            "H": "13.11",
            "OW": "4.42",
            "OH": "6.57"
        }
    ]
};*/


var resizeDiv;

$(document).ready(function(){

createSideButtons();
createSaveBtn();
createInputsBox();
loadSideData();
interact(tabId + " .resize-drag")
  .draggable({
    onmove: dragMoveListener,
    restrict: {
      restriction: 'parent',
    }
    
  })
  .resizable({
  	restrict: {
      restriction: 'parent',
   },
    edges: { left: true, right: true, bottom: true, top: true }
  })
  .on('resizemove', function (event) {
    var target = event.target,
    x = (parseFloat(target.getAttribute('data-x')) || 0),
    y = (parseFloat(target.getAttribute('data-y')) || 0);
    console.log('target'+target);
 	if(x<0 || y<0 ) return;
    // update the element's style
    target.style.width  = event.rect.width + 'px';
    target.style.height = event.rect.height + 'px';

    // translate when resizing from top or left edges
    x += event.deltaRect.left;
    y += event.deltaRect.top;

    target.style.webkitTransform = target.style.transform =
        'translate(' + x + 'px,' + y + 'px)';

    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);
   // target.textContent =  x + "  " + y+ "  " + event.rect.width + '×' + event.rect.height;
    //setParameters(x,y,(event.rect.width),(event.rect.height));
   	setParameters();
  });


  // this is used later in the resizing demo
  // window.dragMoveListener = dragMoveListener;
  
}); 
function dragMoveListener (event) {
	console.log("target = "+event.target);
    var target = event.target,
        // keep the dragged position in the data-x/data-y attributes
        x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
        y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;
if(x<0 || y<0 ) return;
    // translate the element
    target.style.webkitTransform =
    target.style.transform =
      'translate(' + x + 'px, ' + y + 'px)';

    // update the posiion attributes
    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);
    setParameters();
  }
function setParameters(wid,hit){
	console.log("currentSide = " + currentSide);
	console.log($('#'+currentSide+'> div'));
	var X = makeround($('#'+currentSide+'> div').attr('data-x'));
	var Y = makeround($('#'+currentSide+'> div').attr('data-y'));
	var OW = makeround($('#'+currentSide+'> div').attr('oWidth'));
	var OH = makeround($('#'+currentSide+'> div').attr('oHeight'));
	
	var W ;
	var H ;

	// Updated By Gul
	if(!wid || !hit){
		var bbox = $('#'+currentSide+'> div')[0].getBoundingClientRect();
		W = makeround(bbox.width);
		H = makeround(bbox.height);
	}
	else{
		W = wid;
		H = hit;
	}
	console.log('W');
	console.log(W);
	console.log(makeround(inputBoxW.val()));
	
			
	if(makeround(inputBoxW.val()) != 0 &&  W != makeround(inputBoxW.val()))	{
		var temp1 = inputBoxOW.val();
		var newVal = makeround(temp1 * W/inputBoxW.val());
		$('#'+currentSide+'> div').attr('oWidth',newVal);
	}
	if(makeround(inputBoxW.val())!=0 && H != makeround(inputBoxH.val())){
		var temp1 = inputBoxOH.val();
		var newVal = makeround(temp1 * H/inputBoxH.val());
		$('#'+currentSide+'> div').attr('oHeight',newVal);
	}
	
	inputBoxX.val(X);
	inputBoxY.val(Y);
	inputBoxW.val(W);
	inputBoxH.val(H);
	
	inputBoxOW.val(makeround($('#'+currentSide+'> div').attr('oWidth')));
	inputBoxOH.val(makeround($('#'+currentSide+'> div').attr('oHeight')));

	
	var temp1 = makeround(inputBoxX.val());
	var temp2 = makeround(inputBoxY.val());
	$('#'+currentSide+'> div').css({"transform":"translate("+temp1+"px,"+temp2+"px)"});
}

function loadSideData(){
	console.log('loadSideData');
	
	var divCon = $('<div style="width:500px;height:500px"/>');
	$(tabId).append(divCon);
	$.each(jasonStr.coordinate,function(index,value){
		var sideId = "side_"+(index+1);
		var resizeConDiv = $('<div class="resize-container" id="'+sideId+'" style="display:none"/>');
		divCon.append(resizeConDiv);
		
		var image = new Image();
		image.onload=function(){
			var originalwidth= image.naturalWidth ;
			var originalheight =image.naturalHeight ;
				
			var imgWidth;
			var imgheight;
				
			var ratio = originalwidth/originalheight;			
			if (originalwidth > originalheight)
			{
				imgWidth = imagewidth;
				imgheight = imgWidth/ratio;
			}
			else
			{
				imgheight = imageheight;
				imgWidth = imgheight*ratio;
				if (imgWidth > imagewidth) 
				{
					imgWidth = imagewidth;
					imgheight = imgWidth/ratio;
				}
			}
				
				
			resizeConDiv.append($('<img src="'+value['image_path']+'" alt="" width="'+imgWidth+'" height="'+imgheight+'"/>'));
			//var resizeDiv = ($('<div class="resize-drag">Resize from any edge or corner</div>'));
			var resizeDiv = ($('<div class="resize-drag"></div>'));
			resizeConDiv.append(resizeDiv);
			
			
			 resizeDiv.attr('data-x', makeround(value.X));
			 resizeDiv.attr('data-y', makeround(value.Y));
			 resizeDiv.css('width', makeround(value.W));
			 resizeDiv.css('height', makeround(value.H));
			 resizeDiv.attr('oWidth', makeround(value.OW));
			 resizeDiv.attr('oHeight', makeround(value.OH));
			 //resizeDiv.attr('transform',"translate("+makeround(value.W)+","+makeround(value.H)+")");
			 
			 if(index == 0){
				resizeConDiv.show();
				currentSide = sideId;
			 	setParameters(makeround(value.W),makeround(value.H));
			 }
		};
		image.src=value['image_path'];
		
	});
	
	
}
function createSideButtons(){
	var btnId ='btnCon';
	var btnConDiv = $('<div class="sidepositions" id="'+btnId+'"/>');
	$(tabId).append(btnConDiv);
	console.log("jasonStr.coordinate");
	console.log(jasonStr.coordinate);
	$.each(jasonStr.coordinate,function(index,value){
		//creating side buttons
		var sideId = "sideButton_"+(index+1);
		var btnList = $('<button id ="'+sideId+'">'+value['name']+'</button>');
		btnConDiv.append(btnList);
		btnList.on("click", showSide);
	});
}


function createInputsBox(){
	var inputBoxId ='inputCon';
	var inputBoxConDiv = $('<div class="sidepositions" id="'+inputBoxId+'"/>');
	$(tabId).append(inputBoxConDiv);
	
	var inputId = 'input_X';
	var label = 'X';
	//var inputBoxX = $('<input  onkeyup="inputChangeHandler(this)"  type="text" id ="'+inputId+'"/>');
	inputBoxX = $('<input  type="text" />');
	var labelX = $('<label class="label">'+label+':</label>');
	inputBoxConDiv.append(labelX);
	inputBoxConDiv.append(inputBoxX);
	//inputBoxX.on("keypress",isNumberKey);
	inputBoxX.on("keyup",inputChangeHandler);
	inputBoxX.change();
	
	inputId = 'input_Y';
	label = 'Y';
	inputBoxY = $('<input type="text" />');
	var labelY = $('<label class="label">'+label+':</label>');
	inputBoxConDiv.append(labelY);
	inputBoxConDiv.append(inputBoxY);
	inputBoxY.on("keyup",inputChangeHandler);
	inputBoxY.change();
	
	inputId = 'input_W';
	label = 'Width';
	inputBoxW = $('<input type="text" />');
	var labelW = $('<label class="label">'+label+':</label>');
	inputBoxConDiv.append(labelW);
	inputBoxConDiv.append(inputBoxW);
	inputBoxW.on("keyup",inputChangeHandler);

	
	var inputId = 'input_H';
	label = 'Height';
    inputBoxH = $('<input type="text" />');
	var labelH = $('<label class="label">'+label+':</label>');
	inputBoxConDiv.append(labelH);
	inputBoxConDiv.append(inputBoxH);
	inputBoxH.on("keyup",inputChangeHandler);
	
	var inputId = 'input_OW';
	label = 'Output Width';
    inputBoxOW = $('<input type="text" />');
	var labelOW = $('<label class="label">'+label+':</label>');
	inputBoxConDiv.append(labelOW);
	inputBoxConDiv.append(inputBoxOW);
	inputBoxOW.on("keyup",inputChangeHandler);
	
	var inputId = 'input_OH';
	label = 'Output Height';
	inputBoxOH = $('<input type="text" />');
	var labelOH= $('<label class="label">'+label+':</label>');
	inputBoxConDiv.append(labelOH);
	inputBoxConDiv.append(inputBoxOH);
	inputBoxOH.on("keyup",inputChangeHandler);
}

function inputChangeHandler(e){
	console.log(inputBoxX.val());
	var temp1 ; 
	var temp2 ;
	var ratio = inputBoxW.val()/inputBoxH.val();
	if(e.target == inputBoxX[0]){
		temp1 = makeround(inputBoxX.val());
		temp2 = makeround(inputBoxY.val());
		$('#'+currentSide+'> div').css({"transform":"translate("+temp1+"px,"+temp2+"px)"});
		$('#'+currentSide+'> div').attr('data-x',temp1);
	}
	if(e.target == inputBoxY[0]){
		temp1 = makeround(inputBoxX.val());
		temp2 = makeround(inputBoxY.val());
		$('#'+currentSide+'> div').css({"transform":"translate("+temp1+"px,"+temp2+"px)"});
		$('#'+currentSide+'> div').attr('data-y',temp2);
	}
	if(e.target == inputBoxW[0]){
		
		$('#'+currentSide+'> div').css('width',makeround(inputBoxW.val()));
		temp1 = makeround(inputBoxOW.val());
		temp2 = makeround(inputBoxOH.val());
		var newVal = temp2 * inputBoxW.val()/inputBoxH.val();
		inputBoxOW.val(makeround(newVal));
		$('#'+currentSide+'> div').attr('oWidth',temp1);
	}
	if(e.target == inputBoxH[0]){
		$('#'+currentSide+'> div').css('height',makeround(inputBoxH.val()));
		temp1 = makeround(inputBoxOW.val());
		temp2 = makeround(inputBoxOH.val());
		var newVal = temp1 * inputBoxH.val()/inputBoxW.val();
		inputBoxOH.val(makeround(newVal));
		$('#'+currentSide+'> div').attr('oHeight',temp2);
		
	}
	if(e.target == inputBoxOW[0]){
		$('#'+currentSide+'> div').attr('oWidth',makeround(inputBoxOW.val()));
		inputBoxOH.val(makeround(inputBoxOW.val()/ratio)) ;
		$('#'+currentSide+'> div').attr('oHeight',makeround(inputBoxOH.val()));
	}
	if(e.target == inputBoxOW[0]){
		$('#'+currentSide+'> div').attr('oHeight',makeround(inputBoxOH.val()));
		inputBoxOW.val(makeround(inputBoxOH.val()*ratio)) ;
		$('#'+currentSide+'> div').attr('oWidth',makeround(inputBoxOW.val()));
	}
	//if(e.id=="input_OW"){}
	//if(e.id=="input_OH"){}
	//setParameters();
}
function hidAllSides(){
	$.each(jasonStr.coordinate,function(index,value){
		var imageId ='side_'+(index+1);
		$('#'+imageId).hide();
	});
}
function showSide(evt){
	evt.preventDefault();
	hidAllSides();
	var imageId = evt.target.id.substring(evt.target.id.indexOf("_")+1);
	$("#side_"+imageId).show();
	currentSide = ("side_"+imageId);
	//setParameters();
	
	var X = makeround($('#'+currentSide+'> div').attr('data-x'));
	var Y = makeround($('#'+currentSide+'> div').attr('data-y'));
	var OW = makeround($('#'+currentSide+'> div').attr('oWidth'));
	var OH = makeround($('#'+currentSide+'> div').attr('oHeight'));

	var bbox = $('#'+currentSide+'> div')[0].getBoundingClientRect();
	var W = makeround(bbox.width);
	var H = makeround(bbox.height);

	inputBoxX.val(X);
	inputBoxY.val(Y);
	inputBoxW.val(W);
	inputBoxH.val(H);


	inputBoxOW.val(OW);
	inputBoxOH.val(OH);
	
	
	var temp1 = makeround(inputBoxX.val());
	var temp2 = makeround(inputBoxY.val());
	$('#'+currentSide+'> div').css({"transform":"translate("+temp1+"px,"+temp2+"px)"});

}
function createSaveBtn(){
	var btnId ='savebtnCon';
	var btnConDiv = $('<div class="sidepositions" id="'+btnId+'"/>');
	$(tabId).append(btnConDiv);
	var sideId = "saveButton_";
	var value = "Save Area";
	var btnList = $('<button id ="'+sideId+'">'+value+'</button>');
	btnList.on("click",saveSideData);
	btnConDiv.append(btnList);
}
function saveSideData(evt){
	evt.preventDefault();
	$.each(jasonStr.coordinate,function(index,value){
		var imageId ='side_'+(index+1);
		//$('#'+imageId).hide();
		var X = $('#'+imageId+'> div').attr('data-x');
		var Y = $('#'+imageId+'> div').attr('data-y');
		var W = $('#'+imageId+'> div').css('width').replace('px','');
		var H = $('#'+imageId+'> div').css('height').replace('px','');
		
		var OW = $('#'+imageId+'> div').attr('oWidth');
		var OH = $('#'+imageId+'> div').attr('oHeight');
		
		value.X = X;
		value.Y = Y;
		value.W = W;
		value.H = H;
		value.OW = OW;
		value.OH = OH;
	});
	console.log(jasonStr);
	
	$.ajax({
		type: 'POST',
		url: saveCoordinate,
		cache: false,
		data: {product_id:product_id , jasonData:jasonStr ,noofSide:noofSide },
		success: function (response) {
		    if(response === 'true') {
			$('#savebtnCon').after("<p id='are_suc'>Config Area Saved Successfully...!</p>");
			$('#are_suc').fadeOut(2000);
		    } else {
			$('#savebtnCon').after("<p id='are_err'>Error Saving Config Area...!</p>");
			$('#are_err').fadeOut(2000);
		    }
		}			
		});
		
}

// Utility Functions 
function count_appearance(mainStr, searchFor) {
    return (mainStr.split(searchFor).length - 1);
}
function isNumberKey(evt)
{
    $return = true;
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
        $return = false;
    $val = $(evt.originalTarget).val();
    if (charCode == 46) {
        if (count_appearance($val, '.') > 0) {
            $return = false;
        }
        if ($val.length == 0) {
            $return = false;
        }
    }
   return $return;
}
function makeround(val)
{
	return Math.round(val*100)/100;
}
}());