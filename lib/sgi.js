var cbSizeRatio=.95;
var mouseEnabled = false;
var cb;
function popper(rel) {
  if (!rel)
    rel = 'gent';
    cb = $('a.gent').colorbox({
    'transition': 'none',
    'height': parseInt(window.innerHeight*cbSizeRatio),
    'width': parseInt(window.innerWidth*cbSizeRatio),
    'initialHeight': parseInt(window.innerHeight*cbSizeRatio),
    'initialWidth': parseInt(window.innerWidth*cbSizeRatio ),
    'scalePhotos': true,
    'scale': true,
    'slideshow': true,
    'slideshowAuto': false,
    'fixed': true,
    'opacity': 0,
    'open': true,
    'preloaing': false,
    'onOpen': popup,
    'onClosed': popdown,
    'rel': rel,
    'scrolling': false,
  });
  //cb.resize();
}
function mouseup() {
  if(mouseEnabled)
    $.colorbox.prev();
}
function mousedown() {
  if(mouseEnabled)
    $.colorbox.next();
}
function mousewheel(e) {
      if(e.originalEvent.wheelDelta /120 > 0) {
        mouseup();
      }
      else{
        mousedown();
      }
}
function popup() {
  mouseEnabled = true;
  $('body').bind('mousewheel', mousewheel);
  $('body').css('overflow','hidden');
}
function popdown() {
  mouseEnabled = false;
  $('body').unbind('mousewheel', mousewheel);
  $('body').css('overflow','auto');
}
var swipfn = {
  'left':  $.colorbox.next,
  'right': $.colorbox.prev
};
$(document).ready(function() {
  $('body').swipe({
    swipe:function(event, direction, distance, duration, fingerCount){
      if(mouseEnabled) {
        swipfn[direction]();
      }
    },
    fingers:'all'
  });

});