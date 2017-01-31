// Additional JS functions here
Facebook = {
    authorized: null,
    authorize: function facebookAuthorize() {
        var def = jQuery.Deferred();
        if (Facebook.authorized) { def.resolve(); return def; }
        FB.login(function (response) {
            if (response.authResponse) def.resolve();
            else { 
				trace('cancel');
				def.reject();
				hideLoader();
			}
        }, { scope: "user_photos" });
		return def;
    }
};
window.fbAsyncInit = function () {
    FB.init({
        appId: facebookAppId, // App ID
        channelUrl: 'fb-channel.html', // Channel File
        status: true, // check login status
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true  // parse XFBML
    });
	/* $j('#import_fb').on('mouseup', function () {
		if (!$j('#import_fb').hasClass('disabled')) FacebookImport.startImport();
	}); */
    //345321608904055(testing)
    //190788961074816(production)

    // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
    // for any authentication related change, such as login, logout or session refresh. This means that
    // whenever someone who was previously logged out tries to log in again, the correct case below 
    // will be handled. 
    FB.Event.subscribe('auth.authResponseChange', function(response) {
        // Here we specify what we do with the response anytime this event occurs. 
        if (response.status === 'connected') {
            Facebook.authorized = true;
        } else if (response.status === 'not_authorized') {
            Facebook.authorized = false;
        } else {
            Facebook.authorized = null;
        }
    });
};
// Load the SDK asynchronously
(function (d) {
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) { return; }
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));