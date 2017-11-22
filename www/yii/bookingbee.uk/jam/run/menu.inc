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

{{@template CURRENTCOLOR_FROM #80ff80}}
{{@template CURRENTCOLOR_TO #00cc00}}

<!-- Define any js:hooks here -->



<!-- include hint (see https://github.com/chinchang/hint.css ) -->
<!-- Demo here: https://kushagragour.in/lab/hint/ -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/hint.css/2.5.0/hint.css"/>

<script>
var helpArr = new Array;
var helpToggle = 0;
/*
 * Whatever you call your function, it must be mentioned as a 'js:'. See utilCreateUserMenu.jam
*/
function callHint() {
	//console.log('in callHint, helpToggle='+helpToggle);
	for (var i = 0; i < helpArr.length; i ++) {

/* classes -
Colors - hint--error, hint--info, hint--warning, hint--success
Sizes - hint--small, hint--medium, hint--large
hint--always
hint--rounded
hint--no-animate
hint--bounce
*/
var hColor = "hint--info";
var hRound = "hint--rounded";

		if (!helpToggle) {
			$("[help-id='" + helpArr[i].id + "']").addClass("hint--" + helpArr[i].pos);
			$("[help-id='" + helpArr[i].id + "']").addClass(hColor);
			$("[help-id='" + helpArr[i].id + "']").addClass(hRound);
			$("[help-id='" + helpArr[i].id + "']").addClass("hint--always");
			$("[help-id='" + helpArr[i].id + "']").attr("aria-label", helpArr[i].text);
		} else {
			$("[help-id='" + helpArr[i].id + "']").removeClass("hint--" + helpArr[i].pos);
			$("[help-id='" + helpArr[i].id + "']").removeClass(hColor);
			$("[help-id='" + helpArr[i].id + "']").removeClass(hRound);
			$("[help-id='" + helpArr[i].id + "']").removeClass("hint--always");
			$("[help-id='" + helpArr[i].id + "']").removeAttr("aria-label");
		}
	}
	if (!helpToggle)
		helpToggle = 2;
	else helpToggle = 0;
	//console.log('out callHint, helpToggle='+helpToggle);
}

function helpAdd(id, text, pos) {
	obj = new Object();
	obj.id = id;
	obj.text = text;
	obj.pos = pos;
	helpArr.push(obj);
}

$(document).on('click', function (e) {
	//console.log(e);
	if (helpToggle == 2) {
		helpToggle = 1;
		//console.log("EV toggle downed from 2 to 1");
	} else if (helpToggle) {
		//console.log("EV toggle s 1");
		callHint();
	}
	// Do whatever you want; the event that'd fire if the "special" element has been clicked on has been cancelled.
});
</script>

<!-- ----------------------------------------------------------------------------------------------------------- -->
