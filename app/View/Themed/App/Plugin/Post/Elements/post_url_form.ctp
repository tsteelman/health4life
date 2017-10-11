<div id="posting_url_form" class="posting_options_form" style="display:none;">
    <div class="form-group" id="link_input_section">
        <?php echo $this->Form->input('link', array('placeholder' => __('Enter or paste a link'), 'class' => 'pull-left form-control')); ?>
        <button type="button" id="grab_url_btn" class="btn btn_add pull-right" disabled="disabled" value="Add">Add</button>       
    </div>
    <div id="preview" class="block posting_url_review" style="display: none;">
        <div id="previewImages" class="pull-left">
            <div id="previewImage"></div>
            <div id="previewImagesNav" style="display: none;">
                <span class="pull-right"><a class="next"></a></span>
                <span class="pull-right"><a class="prev"></a></span>
            </div>
        </div>
        <div id="closePreview" title="Remove" class="pull-right">
            <button class="close" type="button">×</button>
        </div>
        <div id="previewContent" class="pull-left" >
            <div id="previewTitle" class="owner"></div>
            <div id="previewUrl"></div>
            <div id="previewDescription"></div>
        </div>
    </div>
</div>