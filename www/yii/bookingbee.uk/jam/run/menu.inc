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

<link href="/jam/sys/extern/chardinjs/chardinjs.css" rel="stylesheet">
<script src="/jam/sys/extern/chardinjs/chardinjs.js"></script>

<script>
function callChardinJs() {
	$('body').chardinJs('start');
}
</script>
