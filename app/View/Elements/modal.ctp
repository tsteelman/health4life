<!-- Modal -->
<div class="modal fade" id="<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $titleId; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="<?php echo $titleId; ?>"><?php echo $title; ?></h4>
            </div>
            <div class="modal-body">
                <?php echo $body; ?>   
            </div>
            <div class="modal-footer">                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->