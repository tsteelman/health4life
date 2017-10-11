<div class="patient_details">
    <div class="media">                            
        <div class="media-body">
            <h2>Task Update History</h2>                                
        </div>
    </div>

   <?php

        if ( $histories ) {
            
            echo '<div class="history-box">';
            $i = 0;
            foreach ($histories as $key => $history) {
                $message = NULL;
                if ( ( $i % 2 )  == 0 ) {
                    echo '<div class="task_detail task_odd">';
                } else {
                    echo '<div class="task_detail">';
                }
                switch ( $history['action'] ) {
                    case CareCalendarEvent::ACTION_CREATION:
                        echo '<div class="col-lg-12 task_date">';
                        echo __('Created by ');
                        echo Common::getUserProfileLink( $history['action_by']) ;
                        echo ' ' .CakeTime::timeAgoInWords($key);
                        echo '</div>';
                        
                        if ( isset( $history['assigned_to'] ) ) {
                            $message = '<b>Assignee</b> changed to <i>' . $history['assigned_to'] .'</i>';
                        }                        
                        break;
                    
                    case CareCalendarEvent::ACTION_ASSIGNING:
                        echo '<div class="col-lg-12 task_date">';
                        echo __('Updated by ');
                        echo Common::getUserProfileLink( $history['action_by'] );
                        echo ' ' .CakeTime::timeAgoInWords($key);
                        echo '</div>';
                        
                        if ( isset( $history['assigned_from'] ) ) {
                            
                            if ( isset ($history['assigned_to'] )) {
                                $assigned_to_name = $history['assigned_to'];
                            } else {
                                $assigned_to_name = 'None';
                            }
                            $message = '<b>Assignee</b> changed from '
                                            .'<i>'.  $history['assigned_from'] .'</i>'
                                                . ' to '
                                             .'<i>'.  $assigned_to_name.'</i>';
                        } else {
                            $message = '<b>Assignee</b> changed to <i>' .  $history['assigned_to'] .'</i>';
                        }
                        
                        break;
                        
                    case CareCalendarEvent::ACTION_UPDATION_ONLY:
                        echo '<div class="col-lg-12 task_date">';
                        echo __('Updated by ');
                        echo Common::getUserProfileLink( $history['action_by'] );
                        echo ' ' .CakeTime::timeAgoInWords($key);
                        echo '</div>';
                        break;
                    case CareCalendarEvent::ACTION_ACCEPT:
                        echo '<div class="col-lg-12 task_date">';
                        echo __('Approved by ');
                        echo Common::getUserProfileLink( $history['action_by'] );
                        echo ' ' .CakeTime::timeAgoInWords($key);
                        echo '</div>';
                       
                        break;
                    
                    case CareCalendarEvent::ACTION_DECLINE:
                        echo '<div class="col-lg-12 task_date">';
                        echo __('Declined by ');
                        echo Common::getUserProfileLink( $history['action_by'] );
                        echo ' ' .CakeTime::timeAgoInWords($key);
                        echo '</div>';
                        
                        break;
                    
                    case CareCalendarEvent::ACTION_COMPLETION:
                        echo '<div class="col-lg-12 task_date">';
                        echo __('Updated by ');
                        echo Common::getUserProfileLink( $history['action_by'] );
                        echo ' ' .CakeTime::timeAgoInWords($key);
                        echo '</div>';                       
                        $message = '<b>Status</b> changed to completed'; 
                       
                        break;
                    
                    case CareCalendarEvent::ACTION_EDITING:
                        echo '<div class="col-lg-12 task_date">';
                        echo __('Edited by ');
                        echo Common::getUserProfileLink( $history['action_by'] );
                        echo ' ' .CakeTime::timeAgoInWords($key);
                        echo '</div>';
                        
                        if ( isset( $history['assigned_from'] ) ) {
                            if ( isset ($history['assigned_to'] )) {
                                $assigned_to_name = $history['assigned_to'];
                            } else {
                                $assigned_to_name = 'None';
                            }
                            $message = '<b>Assignee</b> changed from '
                                            .'<i>'. $history['assigned_from'] .'</i>'
                                                . ' to '
                                             .'<i>'. $assigned_to_name .'</i>';
                            if ( $assigned_to_name == 'None' ) {
                                $message .= '<li><b>Status</b> changed to <i>Open</i></li>';
                            }
                        } else if ( isset( $history['assigned_to'] ) ) {
                            $message = '<b>Assignee</b> changed to <i>' . $history['assigned_to'] .'</i>';
                        }
                        
                        break;
                        
                    case CareCalendarEvent::ACTION_ASSIGNEE_LEFT: 
                        echo '<div class="col-lg-12 task_date">';
                        echo __('Updated ');                       
                        echo ' ' .CakeTime::timeAgoInWords($key);
                        echo '</div>';
                        
                       
                            $message = '<b>Status</b> changed to <i>Open</i>';
                        
                        $note =  '<i>'. $history['action_by'] .
                                "</i> (Assignee) is no longer a part of the team.";
                        break;
                }
                
                if ( isset( $message ) && !empty($message)) {
                    echo '<div class="message">';
                    echo '<ul><li>' . __( $message ) .'</li></ul>'; 
                    echo '</div>';
                }
                if ( isset( $history['note'] ) && !empty($history['note'])) {
                    echo '<div class="note">';
                    echo  __( nl2br( h($history['note'] )) ); 
                    echo '</div>';
                }
                
                if ( isset( $note ) && !empty( $note )) {
                    echo '<div class="note">';
                    echo $note;
                    echo '</div>';
                    $note = null;
                }
                
                echo '</div>';
                
                $i++;
            }
            
            echo '</div>';
        }
        ?>          
</div>