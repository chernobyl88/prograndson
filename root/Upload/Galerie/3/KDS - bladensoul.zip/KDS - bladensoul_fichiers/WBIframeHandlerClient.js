/**
 * Class WBIframeHandlerServer
 * @author joko@webedia
 * @version 1
 * @memberOf WBIframeHandler
 * @constructor
 */
function WBIframeHandlerClient() {
    this.baseUrl = '';
    this.debugMode = false;
    this.inIframe = window.self !== window.top;
}

/**
 * Generate console.logs depends on WBIframeHandlerClient.debugMode
 * @param thing
 */
WBIframeHandlerClient.prototype.debug = function(thing){
    if (this.debugMode === true) {
        if (typeof thing.method !== 'undefined'){
            thing.method = 'WBIframeHandlerClient-'+thing.method;
        }
        console.log(thing);
    }
};


/**
 * save include script attributes and search for pathes with scriptName
 * @param scriptName
 */
WBIframeHandlerClient.prototype.init = function(scriptName){
    if (this.inIframe === false) return;

    this.debug({'method':'init','data':{'scriptName':scriptName, 'inIframe':this.inIframe}});
    var scripts = document.getElementsByTagName("script");
    for (var i = 0; i < scripts.length; i++) {
        var curScript = scripts[i];
        var tmpA = document.createElement("a");
        tmpA.href = curScript.getAttribute('src');
        if (tmpA.pathname.indexOf(scriptName) !== -1)
        {
            this.baseUrl = tmpA.protocol + "//" + tmpA.host + (tmpA.port? ":"+tmpA.port : "");
            break;
        }
    }
};

/**
 * Ask to add a css file by its url to the parent window
 * @param src
 */
WBIframeHandlerClient.prototype.injectCss = function(src)
{
    this.debug({'method':'injectCss','data':{'src':src}});
    var fullUrl = this.baseUrl + src;
    var message = {'handler' :  'handleMessageAddCss', 'params' : fullUrl};
    window.parent.postMessage(JSON.stringify(message), '*');
};

/**
 * Ask to add a js file by its url to the parent window
 * @param scriptName
 */
WBIframeHandlerClient.prototype.injectJs = function(src)
{
    if (this.inIframe === false) return;

    this.debug({'method':'injectJs','data':{'src':src}});
    var fullUrl = this.baseUrl + src;
    var message = {'handler' :  'handleMessageAddJs', 'params' : fullUrl};
    window.parent.postMessage(JSON.stringify(message), '*');
};

/**
 * Change Iframe Height
 * @param height
 */
WBIframeHandlerClient.prototype.changeHeight = function(height)
{
    if (this.inIframe === false) return;

    this.debug({'method':'changeHeight','data':{'height':height}});
    var message = {'handler' :  'handleChangeHeight', 'params' : height};
    window.parent.postMessage(JSON.stringify(message), '*');
};


/**
 * Change Iframe Width
 * @param width
 */
WBIframeHandlerClient.prototype.changeWidth = function(width)
{
    if (this.inIframe === false) return;

    this.debug({'method':'changeWidth','data':{'width':width}});
    var message = {'handler' :  'handleChangeWidth', 'params' : width};
    window.parent.postMessage(JSON.stringify(message), '*');
};


var WBIHC = new WBIframeHandlerClient();
WBIHC.init('WBIframeHandlerClient.js');
WBIHC.injectCss('/css/wbih_jv.css');
$(document).ready(function(){
    if (WBIHC.inIframe == true)
    {
        $('body').addClass('iframed');
		WBIHC.changeHeight($('#wrapper').height());
    }
});