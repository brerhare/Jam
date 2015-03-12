// ------------------------------------------------------------------------------------
// See http://beta.matizmo.net/plugin/code-examples/configuration.php.html for settings
// ------------------------------------------------------------------------------------

$(function() {
	var $carousel = $('#carousel'),
		$pager = $('#pager');
 
	function getCenterThumb() {
		var $visible = $pager.triggerHandler( 'currentVisible' ),
			center = Math.floor($visible.length / 2);
		
		return center;
	}
 
	$carousel.carouFredSel({	// Note you only set things here, the thumb slider follows suit (how?)
		responsive: true,
		items: {
			visible: 1,
			width: paramWidth,
			height: paramHeight,
			//width: 600,
			//height: (600/600*100) + '%'
		},
		auto: {
        	timeoutDuration : paramDuration,
		},
		scroll: {
			pauseOnHover:true,
			fx: 'crossfade',
			onBefore: function( data ) {
				var src = data.items.visible.first().attr( 'src' );
				src = src.split( '/large/' ).join( '/small/' );
 
				$pager.trigger( 'slideTo', [ 'img[src="'+ src +'"]', -getCenterThumb() ] );
				$pager.find( 'img' ).removeClass( 'selected' );
			},
			onAfter: function() {
				$pager.find( 'img' ).eq( getCenterThumb() ).addClass( 'selected' );
			}
		}
	});
	$pager.carouFredSel({
		width: '100%',
		auto: false,
		height: 120,
		items: {
			visible: 'odd'
		},
		onCreate: function() {
			var center = getCenterThumb();
			$pager.trigger( 'slideTo', [ -center, { duration: 0 } ] );
			$pager.find( 'img' ).eq( center ).addClass( 'selected' );
		}
	});
	$pager.find( 'img' ).click(function() {
		var src = $(this).attr( 'src' );
		src = src.split( '/small/' ).join( '/large/' );
		$carousel.trigger( 'slideTo', [ 'img[src="'+ src +'"]' ] );
	});
});

