jQuery(document).ready(function($) {
  let scrollSpeed = 8; // Adjust the scrolling speed (higher values make it slower)
  let scrollingText = $("#scrolling-text-container p");
  
  function scrollText() {
    let containerWidth = $("#scrolling-text-container").width();
    let textWidth = scrollingText.width();

    scrollingText.css("marginLeft", containerWidth);
    scrollingText.animate({ marginLeft: -textWidth }, (textWidth + containerWidth) * scrollSpeed, "linear", function() {
      scrollingText.css("marginLeft", containerWidth);
      scrollText();
    });
  }

  scrollText();
});