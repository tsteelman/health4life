<?php

App::uses('Component', 'Controller');

class EmailTemplateComponent extends Component {
   /**
    * email templates.
    */
    const RESET_PASSWORD_TEMPLATE = 1;
    const ACCOUNT_ACTIVATION_TEMPLATE = 2;
    const WELCOME_MAIL_TEMPLATE = 3;
    const EVENT_INVITES_TEMPLATE = 4;
    const MAIL_TO_EVENT_CREATOR_TEMPLATE = 5;
    const DELETE_EVENT_TEMPLATE = 6;
    const UPDATE_EVENT_TEMPLATE = 7;
    const DAILY_EVENT_REPORT_TEMPLATE = 8;
    const INVITE_COMMUNITY_MEMBER_TEMPLATE = 9;
    const DELETE_COMMUNITY_TEMPLATE = 10;
    const COMMUNITY_ADD_AS_ADMIN_TEMPLATE = 11;
    const COMMUNITY_REMOVE_FROM_ADMIN_TEMPLATE = 12;
    const ADD_FRIEND_TEMPLATE = 13;
    const APPROVE_FRIEND_INVITE_TEMPLATE = 14;
    const INVITE_NONMEMBER_FRIEND = 15;
    const DISEASE_DELETED_TEMPLATE = 16;
    const MESSAGE_NOTIFICATION_TEMPLATE = 18;
    const CONTACT_US_TEMPLATE = 19;
    const INVITATION_REMINDER_TEMPLATE = 20;
    const PENDING_REQUEST_REMINDER_TEMPLATE = 21;
    const HEALTH_STATUS_UPDATE_REMINDER_TEMPLATE = 22;
    const CHANGE_EMAIL_NOTIFICATION = 23;
    const COMMUNITY_JOIN_REQUEST_NOTIFICATION = 24;
    const POST_NOTIFICATION = 25;
    const POST_COMMENT_NOTIFICATION = 26;
    const POLL_VOTE_NOTIFICATION = 27;
	const EVENT_REMINDER_TEMPLATE = 28;
	const NEW_ADMIN_EMAIL_TEMPLATE = 29;
	const ADMIN_PASSWORD_CHANGED_EMAIL_TEMPLATE = 30;
	const SITE_WIDE_EVENT_NOTIFICATION_EMAIL_TEMPLATE = 31;
	const SITE_WIDE_COMMUNITY_NOTIFICATION_EMAIL_TEMPLATE = 32;
	const ADMIN_DEACTIVATED_EMAIL_TEMPLATE = 33;
	const ADMIN_ACTIVATED_EMAIL_TEMPLATE = 34;
	const TEAM_APPROVED_EMAIL_TEMPLATE = 35;
	const TEAM_DECLINED_EMAIL_TEMPLATE = 36;
	const TEAM_INVITATION_APPROVED_EMAIL_TEMPLATE = 37;
	const TEAM_INVITATION_DECLINED_EMAIL_TEMPLATE = 38;
	const TEAM_INVITATION_EMAIL_TEMPLATE = 39;
	const HEALTH_STATUS_CHANGED_EMAIL_TEMPLATE = 40;
	const CARE_REQUEST_EMAIL_TEMPLATE = 41;
	const CARE_REQUEST_CHANGED_EMAIL_TEMPLATE = 42;
	const TEAM_CREATED_EMAIL_TEMPLATE = 43;
	const REMOVED_FROM_TEAM_EMAIL_TEMPLATE = 44;
	const TEAM_INVITATION_REMINDER_EMAIL_TEMPLATE = 45;
	const TEAM_DELETE_NOTIFICATION_EMAIL_TEMPLATE = 46;
	const REMOVED_FROM_TEAM_WITH_REASON_EMAIL_TEMPLATE = 47;
	const CARE_CALENDAR_TASK_REMINDER_TEMPLATE = 48;
	const CARE_CALENDAR_DAILY_DIGEST_TEMPLATE = 49;
	const ROLE_APPROVED_NOTIFICATION_EMAIL_TEMPLATE = 50;
	const ROLE_DECLINED_NOTIFICATION_EMAIL_TEMPLATE = 51;
	const TEAM_ROLE_INVITATION_EMAIL_TEMPLATE = 52;
	const TEAM_PATIENT_APPROVAL_REMINDER_EMAIL_TEMPLATE = 53;
	const TEAM_MEMBER_ROLE_PROMOTION_REMINDER_EMAIL_TEMPLATE = 54;
	const DEMOTE_TEAM_ORGANIZER_NOTIFICATION_EMAIL_TEMPLATE = 55;
	const REPORT_ABUSE_REVIEW_EMAIL_TEMPLATE = 56;
	const OWNER_POST_ABUSE_REPORT_REJECTED = 57;
	const OWNER_COMMENT_ABUSE_REPORT_REJECTED = 58;
	const REPORTER_POST_ABUSE_REPORT_REJECTED = 59;
	const REPORTER_COMMENT_ABUSE_REPORT_REJECTED = 60;
	const OWNER_ABUSE_REPORT_POST_DELETED = 61;
	const OWNER_ABUSE_REPORT_COMMENT_DELETED = 62;
	const REPORTER_ABUSE_REPORT_POST_DELETED = 63;
	const REPORTER_ABUSE_REPORT_COMMENT_DELETED = 64;
	const ACCOUNT_ACTIVATION_REMINDER_TEMPLATE = 65;
	const TEAM_PRIVACY_CHANGE_EMAIL_TEMPLATE = 66;
	const TEAM_PRIVACY_CHANGE_REQUEST_EMAIL_TEMPLATE = 67;
	const TEAM_PRIVACY_CHANGE_REQUEST_REJECTED_EMAIL_TEMPLATE = 68;
	const TEAM_JOIN_REQUEST_EMAIL_TEMPLATE = 69;
	const TEAM_JOIN_REQUEST_ACCEPTED_EMAIL_TEMPLATE = 70;
	const TEAM_JOIN_REQUEST_DECLINED_EMAIL_TEMPLATE = 71;
	const FRIEND_RECOMMENDATION_EMAIL_TEMPLATE = 72;
	const MEDICATION_REMINDER_EMAIL_TEMPLATE = 73;
	const QUESTION_NOTIFICATION = 74;
	const QUESTION_ANSWER_NOTIFICATION = 75;
	
	/**
     * Functtion to retrieve email from database.
     */
    public function getEmailTemplate($id = NULL, $data = NULL) {

        /*
         * Loading UserModel
         */
        $EmailTemplate = ClassRegistry::init('EmailTemplate');

    


        $emailManagement = $EmailTemplate->read(null, $id);

        $site_name = Configure::read ( 'App.name' );
        $site_url = Router::url('/', true);

        $emailManagement['EmailTemplate']['template_body'] = str_replace("|@site-url@|", $site_url, $emailManagement['EmailTemplate']['template_body']);
        $emailManagement['EmailTemplate']['template_body'] = str_replace("|@site-name@|", $site_name, $emailManagement['EmailTemplate']['template_body']);
        $emailManagement['EmailTemplate']['template_subject'] = str_replace("|@site-name@|", $site_name, $emailManagement['EmailTemplate']['template_subject']);
        
        if (isset($data['username'])) {
            $emailManagement['EmailTemplate']['template_subject'] = str_replace("|@username@|", $data['username'], $emailManagement['EmailTemplate']['template_subject']);            
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@username@|", $data['username'], $emailManagement['EmailTemplate']['template_body']);            
        }
        if (isset($data['link'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@link@|", $data['link'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['name'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@name@|", $data['name'], $emailManagement['EmailTemplate']['template_body']);
			if (in_array($id, array(self::POST_NOTIFICATION, self::POST_COMMENT_NOTIFICATION, self::POLL_VOTE_NOTIFICATION, self::QUESTION_NOTIFICATION, self::QUESTION_ANSWER_NOTIFICATION))) {
				$emailManagement['EmailTemplate']['template_subject'] = str_replace("|@name@|", $data['name'], $emailManagement['EmailTemplate']['template_subject']);
			}
		}
        if (isset($data['email'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@email@|", $data['email'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['oldEmail'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@old-email@|", $data['oldEmail'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['status'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@status@|", $data['status'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['eventname'])) {
            $emailManagement['EmailTemplate']['template_subject'] = str_replace("|@event-name@|", $data['eventname'], $emailManagement['EmailTemplate']['template_subject']);
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@event-name@|", $data['eventname'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['daily_mail_data'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@daily-mail-data@|", $data['daily_mail_data'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['date'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@date@|", $data['date'], $emailManagement['EmailTemplate']['template_body']);
            $emailManagement['EmailTemplate']['template_subject'] = str_replace("|@date@|", $data['date'], $emailManagement['EmailTemplate']['template_subject']);
        }
        if (isset($data['event_creator_username'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@event-creator-username@|", $data['event_creator_username'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['communityname'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@community-name@|", $data['communityname'], $emailManagement['EmailTemplate']['template_body']);
            $emailManagement['EmailTemplate']['template_subject'] = str_replace("|@community-name@|", $data['communityname'], $emailManagement['EmailTemplate']['template_subject']);
        }
        if (isset($data['deletedBy'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@deleted-by@|", $data['deletedBy'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['friend_username'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@friend-username@|", $data['friend_username'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['accept_link'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@accept-link@|", $data['accept_link'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['reject_link'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@reject-link@|", $data['reject_link'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['disease_name'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@disease-name@|", $data['disease_name'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['replace_disease_name'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@replace-disease-name@|", $data['replace_disease_name'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['page_type'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@page-type@|", $data['page_type'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['page_name'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@page-name@|", $data['page_name'], $emailManagement['EmailTemplate']['template_body']);
        }        
        if (isset($data['sender_username'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@sender_username@|", $data['sender_username'], $emailManagement['EmailTemplate']['template_body']);
            $emailManagement['EmailTemplate']['template_subject'] = str_replace("|@sender_username@|", $data['sender_username'], $emailManagement['EmailTemplate']['template_subject']);           
        }        
        if (isset($data['message_link'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@message_link@|", $data['message_link'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['sender_message'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@sender_message@|", $data['sender_message'], $emailManagement['EmailTemplate']['template_body']);
            
        }
        if (isset($data['sender_profile_link'])) {
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@sender_profile_link@|", $data['sender_profile_link'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['invitation_reminder_body'])) {
        	$emailManagement['EmailTemplate']['template_body'] = str_replace("|@invitation-reminder-body@|", $data['invitation_reminder_body'], $emailManagement['EmailTemplate']['template_body']);
        	$emailManagement['EmailTemplate']['template_subject'] = str_replace("|@inviter-subject@|", $data['inviter_subject'], $emailManagement['EmailTemplate']['template_subject']);
        	$emailManagement['EmailTemplate']['template_body'] = str_replace("|@inviter-body@|", $data['inviter_body'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['auto_login_url'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@auto_login_url@|", $data['auto_login_url'], $emailManagement['EmailTemplate']['template_body']);
		}
        if (isset($data['content'])) {
                $emailManagement['EmailTemplate']['template_body'] = str_replace("|@content@|", $data['content'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['link_text'])) {
                $emailManagement['EmailTemplate']['template_body'] = str_replace("|@link_text@|", $data['link_text'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['post_link'])) {
                $emailManagement['EmailTemplate']['template_body'] = str_replace("|@post_link@|", $data['post_link'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['post_link_text'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@post_link_text@|", $data['post_link_text'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['post_type'])) {
			$emailManagement['EmailTemplate']['template_subject'] = str_replace("|@post_type@|", $data['post_type'], $emailManagement['EmailTemplate']['template_subject']);
		}
        if (isset($data['poll_user'])) {
                $emailManagement['EmailTemplate']['template_body'] = str_replace("|@poll_user@|", $data['poll_user'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['question'])) {
                $emailManagement['EmailTemplate']['template_body'] = str_replace("|@question@|", $data['question'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['answer'])) {
                $emailManagement['EmailTemplate']['template_body'] = str_replace("|@answer@|", $data['answer'], $emailManagement['EmailTemplate']['template_body']);
        }
        if (isset($data['comment'])) {
                $emailManagement['EmailTemplate']['template_body'] = str_replace("|@comment@|", h($data['comment']), $emailManagement['EmailTemplate']['template_body']);
		}		
        if (isset($data['event_datetime'])) {
			$emailManagement['EmailTemplate']['template_subject'] = str_replace("|@event_datetime@|", $data['event_datetime'], $emailManagement['EmailTemplate']['template_subject']);
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@event_datetime@|", $data['event_datetime'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['timezone_offset'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@timezone_offset@|", $data['timezone_offset'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['password'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@password@|", $data['password'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['team_name'])) {
            $emailManagement['EmailTemplate']['template_subject'] = str_replace("|@team-name@|", $data['team_name'], $emailManagement['EmailTemplate']['template_subject']);
            $emailManagement['EmailTemplate']['template_body'] = str_replace("|@team-name@|", h($data['team_name']), $emailManagement['EmailTemplate']['template_body']);
        }
		if (isset($data['team_text'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@team-text@|", $data['team_text'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['health_status_text'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@healthStatusText@|", $data['health_status_text'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['care_type'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@careType@|", $data['care_type'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['care_calendar_reminder_body'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@care-calendar-reminder-body@|", $data['care_calendar_reminder_body'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['team_task_list'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@team-task-list@|", $data['team_task_list'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['reason'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@reason@|", $data['reason'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['role'])) {
			$emailManagement['EmailTemplate']['template_subject'] = str_replace("|@role@|", $data['role'], $emailManagement['EmailTemplate']['template_subject']);
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@role@|", $data['role'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['task_name'])) {
			$emailManagement['EmailTemplate']['template_subject'] = str_replace("|@task-name@|", $data['task_name'], $emailManagement['EmailTemplate']['template_subject']);
		}
		if (isset($data['admin_comment'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@admin-comment@|", h($data['admin_comment']), $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['weekcount'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@weekcount@|", $data['weekcount'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['old_privacy'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@old-privacy@|", $data['old_privacy'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['new_privacy'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@new-privacy@|", $data['new_privacy'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['friend_recommendation_email_body'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@friend_recommendation_email_body@|", $data['friend_recommendation_email_body'], $emailManagement['EmailTemplate']['template_body']);
			$emailManagement['EmailTemplate']['template_subject'] = str_replace("|@recommended_names@|", $data['recommended_names'], $emailManagement['EmailTemplate']['template_subject']);
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@recommended_names@|", $data['recommended_names'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['medication_reminder_email_body'])) {
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@medication_reminder_email_body@|", $data['medication_reminder_email_body'], $emailManagement['EmailTemplate']['template_body']);
		}
		if (isset($data['time'])) {
			$emailManagement['EmailTemplate']['template_subject'] = str_replace("|@time@|", $data['time'], $emailManagement['EmailTemplate']['template_subject']);
			$emailManagement['EmailTemplate']['template_body'] = str_replace("|@time@|", $data['time'], $emailManagement['EmailTemplate']['template_body']);
		}
		
		return $emailManagement;
	}

}