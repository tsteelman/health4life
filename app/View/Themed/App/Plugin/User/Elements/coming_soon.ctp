   <!-- Modal -->
<div class="modal fade modal-centre" id="comingSoon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centre-aligned">
        <div class="modal-content">
            <div class="modal-body" style="background-color: #f1f1f1;">
				<div class="row health_status_editor">
                    <div style="text-align: center;">
                           <img src="/theme/App/img/comingsoon.png" alt="">
						   <h3 style="color: #959595;">Coming Soon</h3>
						   <a href="/dashboard"><h6>< Return Dashboard</h6></a>
					</div>
				</div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$('#comingSoon').modal({
    backdrop: 'static',
    keyboard: false  
})

function adjustModalMaxHeightAndPosition(){
  $('.modal-centre').each(function(){
    if($(this).hasClass('in') == false){
      $(this).show();
    };
    var contentHeight = $(window).height() - 60;
    var headerHeight = $(this).find('.modal-header').outerHeight() || 2;
    var footerHeight = $(this).find('.modal-footer').outerHeight() || 2;

    $(this).find('.modal-content').css({
      'max-height': function () {
        return contentHeight;
      }
    });

    $(this).find('.modal-body').css({
      'max-height': function () {
        return (contentHeight - (headerHeight + footerHeight));
      }
    });

    $(this).find('.modal-dialog').css({
      'margin-top': function () {
        return -($(this).outerHeight() / 2);
      },
      'margin-left': function () {
        return -($(this).outerWidth() / 2);
      }
    });
    if($(this).hasClass('in') == false){
      $(this).hide();
    };
  });
};

$(window).resize(adjustModalMaxHeightAndPosition).trigger("resize");
</script>

