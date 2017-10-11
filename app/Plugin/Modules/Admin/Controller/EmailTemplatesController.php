<?php

/**
 * EmailTemplateController class file.
 *
 * @author    Varun Ashok <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

/**
 * Email Template handling for the admin
 * 
 * EmailTemplate Controller is used for admin to edit and create email templates
 *
 * @author 	Varun Ashok
 * @package 	Admin
 * @category	Controllers 
 */
class EmailTemplatesController extends AdminAppController {

    /**
     * Admin EmailTemplate home
     */
    function index() {
        $this->paginate = array(
            'limit' => 10
        );
        $emailManagements = $this->paginate('EmailTemplate');
        $this->set(compact('emailManagements'));
    }

    /**
     * Add new template
     */
    function add() {
        $url = Router::url('/');
        $this->set('site_url', $url);
        $username = $this->Auth->user('username');
        $user_id = $this->Auth->user('id');
        $this->set(compact('username'));
        $this->JQValidator->addValidation('AddTemplateForm', $this->EmailTemplate->validate, 'AddTemplateForm');
        if (!empty($this->data)) {
            $this->request->data['EmailTemplate']['created_by'] = $user_id;
            $this->request->data['EmailTemplate']['modified_by'] = $user_id;
            $this->EmailTemplate->create();
            if ($this->EmailTemplate->save($this->data)) {
                $this->Session->setFlash(__('The email management has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The email management could not be saved. Please, try again.', true));
            }
        }
    }

    /**
     * Delete template
     */
    function delete($id = null) {
        $this->loadModel('EmailTemplate');
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for email management', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->EmailTemplate->deleteAll(array('EmailTemplate.id' => $id))) {
            $this->Session->setFlash(__('Email template deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Email template was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * View template
     */
    function view($id = null) {
		
        $this->layout = 'mailtemplate';
        if (isset($this->params['url']['preview'])) {
            $arg = $this->params['url']['preview'];
            if ($arg == true) {
                $this->set('preview', true);
            }
        } else {
            $this->set('preview', false);
        }
        if (!$id && $arg != true) {
            $this->Session->setFlash(__('Invalid email management', true));
            $this->redirect(array('action' => 'index'));
        }
        $emailManagement = $this->EmailTemplate->read(null, $id);
		$emailManagement['EmailTemplate']['template_body'] = str_replace("|@site-url@|", Router::url('/', true) , $emailManagement['EmailTemplate']['template_body']);
		$emailManagement['EmailTemplate']['template_body'] = str_replace("|@site-name@|", Configure::read ( 'App.name') , $emailManagement['EmailTemplate']['template_body']);
		$emailManagement['EmailTemplate']['template_subject'] = str_replace("|@site-name@|", Configure::read ( 'App.name'), $emailManagement['EmailTemplate']['template_subject']);
        $this->set(compact('emailManagement'));
    }

    /**
     * Edit template
     */
    function edit($id = null) {

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid email management', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->JQValidator->addValidation('EditTemplateForm', $this->EmailTemplate->validate, 'EditTemplateForm');
        if (!empty($this->data)) {
            $user_id = $this->Auth->user('id');
            $this->set(compact('username'));
            $this->request->data['EmailTemplate']['modified_by'] = $user_id;
            if ($this->EmailTemplate->save($this->data)) {
                $this->Session->setFlash(__('Email template has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The email management could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->EmailTemplate->read(null, $id);
        }
    }

    function search() {
//        if (isset($this->request->data['EmailTemplate']['template_name'])) {
//            $keyword = $this->request->data['EmailTemplate']['template_name'];
//            $this->paginate = array(
//                'conditions' => array('EmailTemplate.template_name LIKE' => '%' . $keyword . '%'),
//                'limit' => 10
//            );
//        } else {
//            $this->paginate = array(
//                'limit' => 10
//            );
//            $emailManagements = $this->paginate('EmailTemplate');
//        }
        $keyword = NULL;
        if (isset($this->request->query['template_name'])) {
            $keyword = $this->request->query['template_name'];
            $this->paginate = array(
                'conditions' => array('EmailTemplate.template_name LIKE' => '%' . $keyword . '%'),
                'limit' => 10
            );
        } else {
            $this->paginate = array(
                'limit' => 10
            );
            $emailManagements = $this->paginate('EmailTemplate');
        }
        $emailManagements = $this->paginate('EmailTemplate');
        $this->set(compact('emailManagements', 'keyword'));
        if (sizeof($emailManagements) == 0) {
            $this->Session->setFlash(__('No templates found.', true));
        }
        $this->render('index');
    }

}