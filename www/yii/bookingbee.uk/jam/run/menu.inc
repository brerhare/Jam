<!-- These are implemented in sm-blue css -->
{{@XXXtemplate FONTDOWNLOAD PT+Sans+Narrow:400,700}}
{{@XXXtemplate FONTFAMILY "PT Sans Narrow", "Arial Narrow", Arial, Helvetica, sans-serif}}
{{@template FONTDOWNLOAD Raleway:300,400,500,600,700}}
{{@template FONTFAMILY "Raleway", "sans-serif"}}
{{@template FONTWEIGHT normal}}
{{@template FONTSIZE 18}}
{{@template BACKCOLOR_FROM #c7d1e4}}
{{@template BACKCOLOR_TO #2c333b}}
{{@template ACTIVECOLOR_FROM #505863}}
{{@template ACTIVECOLOR_TO #2c333b}}
{{@template ARROWCOLOR #2c333b}}
{{@template CORNERPIXELS 2}}
{{@template MAINTEXTCOLOR #f6f6f6}}
{{@template SUBTEXTCOLOR #000000}}
// @@TODO These next two dont seem to affect anything at all
{{@template CURRENTCOLOR_FROM #80ff80}}
{{@template CURRENTCOLOR_TO #00cc00}}

<!-- Define any js:hooks here -->

<!-- ----------------------------------------------------------------------------------------------------------- -->

<!-- Include chardinjs (see https://github.com/heelhook/chardin.js) -->
<link href="/jam/sys/extern/chardinjs/chardinjs.css" rel="stylesheet">
<script src="/jam/sys/extern/chardinjs/chardinjs.js"></script>

<script>
function callChardinJs() {
	// Add the following to your elements
	// help-id="myHelpId"
	// then attach with: helpAdd("myHelpId", "Click here to start a new event", "right", "toggle"/blank);
	 $('body').chardinJs('start');
}

/*function helpAdd(id, text, pos, toggle) {
    if (toggle !== 'undefined') {
        $("[help-id='" + id + "']").attr("help-toggle", "chardinJS");
    }
    $("[help-id='" + id + "']").attr("help-text", text);
    $("[help-id='" + id + "']").attr("help-pos", pos);
} */
</script>

<!-- ----------------------------------------------------------------------------------------------------------- -->

<!-- Include IntroJs (see http://introjs.com/) -->
<link href="//cdnjs.cloudflare.com/ajax/libs/intro.js/2.5.0/introjs.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/intro.js/2.5.0/intro.min.js"></script>

<script>
function callIntroJs() {
	introJs().start();
}
</script>

<!-- ----------------------------------------------------------------------------------------------------------- -->

<!-- Include jQuery Popup Overlay (see http://dev.vast.com/jquery-popup-overlay/) -->
<script src="https://cdn.rawgit.com/vast-engineering/jquery-popup-overlay/1.7.13/jquery.popupoverlay.js"></script>

<script>
function callJqueryPopup() {
	$('.kpopup').popup();
}
</script>

<!-- ----------------------------------------------------------------------------------------------------------- -->

<!-- include Protip (see http://protip.rocks/) -->
<link rel="stylesheet" href="//min.gitcdn.xyz/repo/DoclerLabs/Protip/master/protip.min.css">
<script src="//min.gitcdn.xyz/repo/DoclerLabs/Protip/master/protip.min.js"></script>

<script>
function callProtip() {
	$.protip();
	var el = $('.protip');
	el.protipToggle();

/*	el.protipSet({
    	trigger: 'hover'
	}); */

}
</script>

<!-- ----------------------------------------------------------------------------------------------------------- -->

<!-- include Tipso (see http://tipso.object505.com/) -->
<link href="/jam/sys/extern/tipso/tipso.css" rel="stylesheet">
<script src="/jam/sys/extern/tipso/tipso.js"></script>
<link href="/jam/sys/extern/tipso/animate.css" rel="stylesheet">

<script>
var tipsoOnOff = 0;
function callTipso() {
	$('.tipso').tipso();
	if (tipsoOnOff)
		jQuery('.tipso_style').tipso('hide');
	else
		jQuery('.tipso_style').tipso('show');
	tipsoOnOff = !tipsoOnOff;
}
function helpAdd(id, text, pos) {
	$("[help-id='" + id + "']").addClass(pos);
	$("[help-id='" + id + "']").addClass("tipso_style");
	$("[help-id='" + id + "']").attr("data-tipso", text);
}
</script>

