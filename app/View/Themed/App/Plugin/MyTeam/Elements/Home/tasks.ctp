<?php if (!empty($tasks)) { ?>
<div class="patient_details" id="task_list">
        <div class="media">                            
            <div class="media-body">
                <h4>Tasks</h4>                                
            </div>
        </div>        

        <?php echo $this->element('Home/task_list'); ?>

        
    </div>
<?php } ?>

<script type="text/javascript">
    
    /*
     * load all tasks when user click view all
     */
    $(document).on('click','.paginator-link', function(){
        
        $.ajax({
            url: '/myteam/api/getAllTasksFromToday',
            cache: false,
            type: 'POST',
            data: {
                'teamId' : <?php echo $teamId; ?> ,
                'offset' : $(this).data('offset') ,
                'todayOffset': $('#todayOffset').val()
            },
            success : function(result){
                
                $('#task_list .view_all').remove(); //Remove view all link
                $('#task_list .task_detail').remove(); // Remove already listed tasks
                $('#task_list').append(result); // add new list
            }
        });
    });
</script>