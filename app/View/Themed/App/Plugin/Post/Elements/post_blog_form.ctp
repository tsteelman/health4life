<div id="posting_blog_form" class="form-group posting_options_form">
    <div>
        <?php echo $this->Form->input('title', array('class' => 'blog_title_input', 'placeholder' => 'Title')); ?>
    </div>
    
    <div style="margin-top: 10px;background-color: #F3F5F8;">
        <?php echo $this->Form->textarea('description', array('class' => '', 'placeholder' => 'Description')); ?>
    </div>
</div>