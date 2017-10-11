<!--Sharing options to majour social networking sites -->
<div id="share-wrapper">
  <ul class="share-inner-wrp">
    <!-- Facebook -->
    <li class="facebook button-wrap" id="facebook">
      <div class="facebook-bg"><a href="#">Facebook</a></div>
    </li>

    <!-- LinkedIn -->
    <li class="linkedin button-wrap" id="linkedin">
      <div class="linkedin-bg"><a href="#">LinkedIn</a></div>
    </li>

    <!-- Twitter -->
    <li class="twitter button-wrap" id="twitter">
      <div class="twitter-bg"><a href="#">Tweet</a></div>
    </li>

    <!-- Twitter -->
    <li class="pinterest button-wrap" id="pinterest">
      <div class="pinterest-bg"><a href="#">Pinterest</a></div>
    </li>

    <!-- Google -->
    <li class="google button-wrap" id="google">
      <div class="google-bg"><a href="#">Google</a></div>
    </li>
    
    <!-- p4l -->
    <li class="p4l button-wrap" id="p4l">
      <div class="p4l-bg"><a href="#">H4L</a></div>
    </li>
  </ul>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    var pageTitle = $("meta[property='og:title']").attr("content");
    if(typeof pageTitle === 'undefined'){
        pageTitle = document.title; //HTML page title
    }
    var pageUrl = location.href; //Location of the page
    
    var pageImage = $("meta[property='og:image']").attr("content");
    
    var pageDesc = $("meta[property='og:description']").attr("content");
    
    if(typeof pageDesc === 'undefined'){
        pageDesc = $('meta[name=description]').attr("content");
    }
    
    //user hovers on the share button  
    $('#share-wrapper li').hover(function(event) {
      var hoverEl = $(this); //get element
      var style = hoverEl.attr('id');
      
      //browsers with width > 699 get button slide effect
      if ($(window).width() > 699) {
                 
        hoverEl.stop();
        if (event.type == 'mouseleave') {    
          
          hoverEl.children(":first").removeClass(style+'-hover');
          hoverEl.animate({"margin-left": "3px"}, "slow");
        } else {
          hoverEl.animate({"margin-left": "-83px"}, "slow");
          hoverEl.children(":first").addClass(style+'-hover');
        }
      }
    });

    //user clicks on a share button
    $('.button-wrap').click(function(event) {
      var shareName = $(this).attr('class').split(' ')[0]; //get the first class name of clicked element

      switch (shareName) //switch to different links based on different social name
      {
        case 'facebook':
          var openLink =   'https://www.facebook.com/dialog/feed?' +
                            'app_id= <?php echo Configure::read('API.Facebook.APP_ID'); ?>' +
                            '&display=popup&name=' + pageTitle +
                            '&link=' + encodeURIComponent(pageUrl) +
                            '&redirect_uri=https://www.facebook.com' + 
                            '&picture=' + encodeURIComponent(pageImage) +
                            '&description=' + pageDesc;          
          break;
        case 'twitter':
          var openLink = 'http://twitter.com/home?status=' + encodeURIComponent(pageTitle + ' ' + pageUrl);
          break;
        case 'pinterest':
          var openLink = 'https://pinterest.com/pin/create/button/?url=' + encodeURIComponent(pageUrl) + 
                            '&media=' + encodeURIComponent(pageImage) +
                            '&description=' + pageDesc;
          break;
        case 'google':
          var openLink = 'https://plus.google.com/share?url=' + encodeURIComponent(pageUrl) + '&amp;title=' + encodeURIComponent(pageTitle);
          break;
        case 'linkedin':
          var openLink = "https://www.linkedin.com/shareArticle?mini=true" + 
                            "&url=" + encodeURIComponent(pageUrl) + 
                            "&title=" + encodeURIComponent(pageTitle) + 
                            "&summary=" + pageDesc +
                            "&source=" +
                            '&image' + encodeURIComponent(pageImage);
          break;
      }
 
      //Parameters for the Popup window
      winWidth = 650;
      winHeight = 450;
      winLeft = ($(window).width() - winWidth) / 2,
      winTop = ($(window).height() - winHeight) / 2,
      winOptions = 'width=' + winWidth + ',height=' + winHeight + ',top=' + winTop + ',left=' + winLeft;

      //open Popup window and redirect user to share website.
      if (openLink) {
        window.open(openLink, 'Share', winOptions);
      }
      return false;
    });
  });

</script>
