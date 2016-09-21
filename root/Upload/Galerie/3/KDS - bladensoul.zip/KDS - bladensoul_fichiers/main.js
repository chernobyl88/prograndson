/**
 * Created by J Moreau
 */
var WB_js =
(function ($) {
	var self = {};
	var lbDebug = true;
	/*
	 Private methods
	 */
	var debug = function(thing, otherThing)
	{
		if (lbDebug == true)
		{
			if (typeof otherThing == 'undefined') console.log(thing);
			else console.log(thing,otherThing);
		}
	};



	/*
	 public methods
	 */
	self.init = function () {
		debug('main.js - init');
		initWbFp();
		initCaptcha();
	};
	
	var initCaptcha = function()
	{
		setTimeout(function(){ generateCaptcha();}, Math.floor((Math.random() * 1000) + 1000));
		jQuery('#redoCaptcha').click(function(){generateCaptcha()});
	}

	var initWbFp = function()
	{
		if (typeof(Fingerprint) == 'undefined')
		{
			
		}
		else
		{
			var fp1 = new Fingerprint();
		    var fp2 = new Fingerprint({canvas: true});
		    var fp3 = new Fingerprint({ie_activex: true});
		    var fp4 = new Fingerprint({screen_resolution: true});
		    jQuery('#wbfp1').val(fp1.get());
		    jQuery('#wbfp2').val(fp2.get());
		    jQuery('#wbfp3').val(fp3.get());
		    jQuery('#wbfp4').val(fp4.get());	
		}
	}

	var generateCaptcha =  function()
	{
		jQuery('#redoCaptcha, #captchaInput').hide();
		var src = '/img/captcha/img.php?k='+Math.floor((Math.random() * 10000));
		jQuery('#captcha').fadeOut(300, function(){jQuery('#captchaInput').val('');jQuery('#captcha').attr('src', src); jQuery('#captcha, #redoCaptcha, #captchaInput').fadeIn(1000);});
	}
	
	self.debug = function (thing, otherthing)
	{
		debug(thing, otherthing);
	};
	return self;
})();

$("document").ready(function () {
	WB_js.init();
});
