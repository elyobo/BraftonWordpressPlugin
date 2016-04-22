<?php
header("Content-type: text/css; charset: UTF-8");
$ops = new BraftonOptions();
$static = $ops->getAll();
$braftonPauseColor = $static['braftonPauseColor'];
$braftonEndBackgroundcolor = $static['braftonEndBackgroundcolor'];
$braftonEndTitleColor = $static['braftonEndTitleColor'];
$braftonEndTitleAlign = $static['braftonEndTitleAlign'];
$braftonEndSubTitleColor = $static['braftonEndSubTitleColor'];
$braftonEndSubTitleBackground = $static['braftonEndSubTitleBackground'];
$braftonEndSubTitleAlign = $static['braftonEndSubTitleAlign'];
$braftonEndButtonBackgroundColor = $static['braftonEndButtonBackgroundColor'];
$braftonEndButtonTextColor = $static['braftonEndButtonTextColor'];
$braftonEndButtonBackgroundColorHover = $static['braftonEndButtonBackgroundColorHover'];
$braftonEndButtonTextColorHover = $static['braftonEndButtonTextColorHover'];
$braftonEndTitleBackground = $static['braftonEndTitleBackground'];

?>
/* Effects the puase cta background color */
span.video-pause-call-to-action, span.ajs-video-annotation{
    background-color:;
}
/* effects the pause cta text color */
span.video-pause-call-to-action a:link, span.video-pause-call-to-action a:visited{
    color: <?php echo $braftonPauseColor;  ?>;
}
/* effects the end of video background color *Note: has no effect if a background image is selected */
div.ajs-end-of-video-call-to-action-container{
    background-color:<?php echo $braftonEndBackgroundcolor; ?>;
}
/* effects the end of video title tag */
div.ajs-end-of-video-call-to-action-container h2{
    background:<?php echo $braftonEndTitleBackground; ?>;
    color:<?php echo $braftonEndTitleColor; ?>;
    text-align:<?php echo $braftonEndTitleAlign; ?>;
}
/* effects the end of video subtitle tags */
div.ajs-end-of-video-call-to-action-container p{
    background:<?php echo $braftonEndSubTitleBackground; ?>;
    color:<?php echo $braftonEndSubTitleColor; ?>;
    text-align:<?php echo $braftonEndSubTitleAlign; ?>;
}
/* effects the end of video button *Note: has no effect if button image is selected */
a.ajs-call-to-action-button{
     background-color:<?php echo $braftonEndButtonBackgroundColor;   ?>;
    color:<?php echo $braftonEndButtonTextColor; ?>;
}
/* effects the end of video button on hover and  *Note: has no effects if button image is selected */
a.ajs-call-to-action-button:hover, a.ajs-call-to-action-button:visited{
    background-color:<?php echo $braftonEndButtonBackgroundColorHover; ?>;
    color:<?php echo $braftonEndButtonTextColorHover; ?>;
}