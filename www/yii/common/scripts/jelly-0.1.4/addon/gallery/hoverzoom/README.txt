http://jmar.github.io/jquery-hoverZoom/

One day i stumbled across http://www.sohtanaka.com/web-design/fancy-thumbnail-hover-effect-w-jquery/ and i liked the effect of the zooming images. But it has one big downside: When a zoomed image covers another thumbnail you can´t zoom that thumbnail. So i took my own attemp to create a jquery plugin that fixes this issue.
Features

    No need to click. Just hover over the images to enlarge them
    Real Animation of the image. Not only animating a placeholder div as in most lightboxes
    Support for image captions via alt attribute
    Centering images in viewport
    Scale image to fit in viewport
    Options to control speed of animations
    Complete control of the style with css
    10 KB minified
    hoverIntent included as an option
    Debugging message
    Free and Open Source

Supported browsers

    Firefox 3.6+
    IE 6.0 +
    Safari (Tested in Version 5.0.3)
    Opera (Tested in version 11.0)

Please feel free to test it in other versions/browsers and notify me if it`s working or not.
Dependencies

jQuery 1.4+
Download

git clone git://github.com/jmar/jquery-hoverZoom.git

or download it from https://github.com/jmar/jquery-hoverZoom
Usage

                    <ul class="thumb">
                        <li><a href="fullSizeImage.jpg" /><img src="thumbnail.jpg" /></a></li>
                        <li><a href="fullSizeImage2.jpg" /><img src="thumbnail2.jpg" /></a></li>
                        <li><a href="fullSizeImage3.jpg" /><img src="thumbnail3.jpg" /></a></li>
                    </ul>

                    <script type="text/javascript">
                        $(document).ready(function(){
                                $('.thumb img').hoverZoom();
                        });
                    </script>
                

Options

speedView (600)
    A number determining how long the zoom in animation will run.
speedRemove(400)
    A number determining how long the zoom out animation will run.
showCaption (true | false)
    A boolean determining if captions should be displayed.
speedCaption (800)
    A number determining how long the zoom in animation for captions will run.
debug (true | false)
    A boolean determining if debugging messages should be send to a console.
loadingIndicator (ajax-loader.gif)
    Path to an loadingIndicator image.
loadingIndicatorPos (center | tl | tr | br | bl)
    Position to display the loadingIndicator (topleft, center, topright, etc.)
easing (swing | linear | easeOutQuint)A string indicating which easing function to use for the animation. Supports the easing plugin
captionHeight (32)
    A number determining the height of the caption
breathingSize (0)
    A number determining margins around the zoomed image 
hoverIntent (true | false)
    A boolean determining whether to use the integrated hoverIntent Plugin or not.
useBgImg(false)
    use a background image instead of zooming the real image


