var Smm = {
	fontDirectory: mediapath + 'font/font-selector/',
    selectorId: 'font-selector',
    visible: false
};

Smm.fonts = jsonfontList;

Smm.loadFont = function(fontFace, active, loading){
    console.log('Loading font: ' + fontFace);
    if(Smm.fonts[fontFace]['loaded'] === false){
        console.log('Font not loaded... Getting css file now');
        
        Smm.fonts[fontFace]['loaded'] = true;
        
        WebFont.load({
            custom: {
                families: [fontFace],
                urls: [ Smm.fontDirectory + Smm.fonts[fontFace]['cssFile'] ]
            },
            loading: loading,
            active: active
        });
		console.log('Font not loaded... Getting css file now' + Smm.fontDirectory + Smm.fonts[fontFace]['cssFile']);
    }
    else {
        console.log('Font already loaded, using file.' +  Smm.fontDirectory + Smm.fonts[fontFace]['loaded']);
        active();
    }
};

Smm.init = function(divId){
    $j('#tool_font_family').css('position','relative').append('<div id="font-selector"></div>');
    var selector = $j('#'+Smm.selectorId);
    $j.each(Smm.fonts, function(index,value){
		selector.append('<div class="font-item" font-name="' + index + '">' + index + '</div>');
    });
    
    $j("#close-selector").click(Smm.hideSelector);
    $j(".font-item").click(Smm.selectFont);
    $j('#font_family_dropdown button').unbind('mousedown').bind('mousedown',function(event){
        if (Smm.visible === false) {
            Smm.showSelector();
        } else {
            Smm.hideSelector();
        }
    });
    $j(window).mouseup(function(evt) {
        if(!Smm.visible === true) {
            Smm.hideSelector();
        }
        Smm.visible = false;
    });
    $j('#'+Smm.selectorId).mouseup(function(){
        Smm.showSelector();
    });
};

Smm.showSelector = function(){
    $j('#'+Smm.selectorId).show();
    Smm.visible = true;
};

Smm.hideSelector = function(){
    $j('#'+Smm.selectorId).hide();
    Smm.visible = false;
};

Smm.selectFont = function(){
    var font = $j(this).attr('font-name');
    var active = function(){
        svgCanvas.setFontFamily(font);
    };
	$j('#font_family').val(font);
    var loading = function(){};
    Smm.loadFont(font, active, loading);
    Smm.hideSelector();
}