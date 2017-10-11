<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($this->request->referer(1) === '/notification') {
	$this->Html->addCrumb('Notifications', '/notification');
} else {
	$this->Html->addCrumb('My Team', $module_url);
}
$this->Html->addCrumb($team['name'], $module_url . '/' . $team['id']);
$this->Html->addCrumb('Calendar', $module_url . '/' . $team['id'] . '/calendar');
$this->Html->addCrumb('Task : ' . h($task_details['Event']['name']));
?>

<div class="container">
    <div id="calendar_view" class="row team_discussion calendar_view">       
        <?php echo $this->element('lhs'); ?>

        <div class="col-lg-9">

            <div class="page-header">
                <h3><?php echo __('Care Calendar'); ?></h3>
            </div>

            <?php
            foreach ($histories as $history) {
                if (isset($history['assigned_to'])) {
                    $action_by = $history['action_by'];
                }
            }
            ?>


            <?php
            if ($isAssignee && $isOpen) {
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="approval_container">
                            <h4 class="pull-left"><?php echo __('%s has invited you to carry out this task', $action_by); ?></h4>
                            <div class="pull-right">
                                <?php echo $this->element('MyTeam.Task/approve_decline_task_buttons', array('taskId' => $task_details['Event']['id'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

            <div class="row">
                <div class="col-lg-12">
                    <?php echo $this->element('MyTeam.Task/detail'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $this->element('MyTeam.Task/update'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php
                    if (isset($task_details['CareCalendarEvent']['history'])) {
                        echo $this->element('MyTeam.Task/history');
                    }
                    ?>
                </div>
            </div>



        </div>



    </div>
</div>
<?php echo $this->jQValidator->validator(); ?>
<script>
    var assignee;

    $(document).ready(function() {
        assignee = $("#TaskUpdationAssignedTo").val();
    });
    function openUpdateDiv() {
        $("#update_button").addClass('hidden');
        $("#TaskUpdationAssignedTo").parent().parent().parent().show();
        $(".update_form").removeClass('hidden');
    }

    function disableAssigne() {
        if ($("#TaskUpdationCompleted").is(':checked')) {
            $("#TaskUpdationAssignedTo").attr('disabled', 'disabled');
            $("#TaskUpdationAssignedTo").parent().parent().parent().hide();
        } else {
            $("#TaskUpdationAssignedTo").parent().parent().parent().show();
            $("#TaskUpdationAssignedTo").removeAttr('disabled');
        }

    }

    function clearForm() {
        $('#careCalendarTaskUpdationForm').find('input[type=textarea]').val('')
        $("#TaskUpdationAssignedTo").removeAttr('disabled');
        $("#TaskUpdationAssignedTo").val(assignee);
        $("#TaskUpdationCompleted").attr('checked', false);
        $('.form-group').removeClass('error');
        $('.form-group span').remove();
        $(".update_form").addClass('hidden');
        $("#update_button").removeClass('hidden');
    }

    $(document).on('click', '.approve_task', function() {
        var task_id = $(this).data('task_id');
        $.ajax({
            url: '/myteam/<?php echo $team['id']; ?>/task/' + task_id + '/approveTask',
            success: function() {
                location.reload();
            }
        });
    });

    $(document).on('click', '.decline_task', function() {
        var task_id = $(this).data('task_id');
        $.ajax({
            url: '/myteam/<?php echo $team['id']; ?>/task/' + task_id + '/declineTask',
            success: function() {
                location.reload();
            }
        });
    });

</script>