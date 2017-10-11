<?php

/**
 * NewsletterTemplatesController class file.
 * 
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

/**
 * Newsletter Template handling for the admin
 * 
 * EmailTemplate Controller is used for admin to edit and create email templates
 *
 * @package 	Admin
 * @category	Controllers 
 */
class NewsletterTemplatesController extends AdminAppController {

     public $uses = array(
        'NewsletterTemplate'        
    );
     
    /**
     * Admin EmailTemplate home
     */
    function index() {
        $this->paginate = array(
            'limit' => 10
        );
        $newsletterTemplate = $this->paginate('NewsletterTemplate');
	$title_for_layout = 'Newsletters';
		
        $this->set(compact('newsletterTemplate', 'title_for_layout'));
    }

    /**
     * Add new template
     */
    function add() {
        $url = Router::url('/');
        $this->set('site_url', $url);
        $username = $this->Auth->user('username');
        $user_id = $this->Auth->user('id');
	$title_for_layout = 'Add Newsletter Template';
        $this->set(compact('username', 'title_for_layout'));
        $this->JQValidator->addValidation('NewsletterTemplate', $this->NewsletterTemplate->validate, 'AddNewsletterTemplateForm');
       
        if (!empty($this->data)) {
         
            $this->request->data['NewsletterTemplate']['created_by'] = $user_id;
            $this->request->data['NewsletterTemplate']['modified_by'] = $user_id;
            $this->NewsletterTemplate->create();
            if ($this->NewsletterTemplate->save($this->data)) {
                $this->Session->setFlash(__('The newsletter template has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The newsletter template could not be saved. Please, try again.', true));
            }
        }
    }

    /**
     * Delete template
     */
    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Newsletter', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->NewsletterTemplate->deleteAll(array('NewsletterTemplate.id' => $id))) {
            $this->Session->setFlash(__('Newsletter template deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Newsletter template was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * View template
     */
    function view($id = null) {

        $this->layout = 'newslettertemplate';
        if (isset($this->params['url']['preview'])) {
            $arg = $this->params['url']['preview'];
            if ($arg === true) {
                $this->set('preview', true);
            }
        } else {
            $this->set('preview', false);
        }
        if (!$id && $arg != true) {
            $this->Session->setFlash(__('Invalid Newsletter', true));
            $this->redirect(array('action' => 'index'));
        }
        $newsletterTemplate = $this->NewsletterTemplate->read(null, $id);
        $this->set(compact('newsletterTemplate'));
    }

    /**
     * Edit template
     */
    function edit($id = null) {
	
	$title_for_layout = 'Edit Newsletter Template';
	if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid newsletter template', true));
            $this->redirect(array('action' => 'index'));
        }
	
        $this->JQValidator->addValidation('NewsletterTemplate', $this->NewsletterTemplate->validate, 'EditTemplateForm');
        if (!empty($this->data)) {
            $user_id = $this->Auth->user('id');	    
            $this->set(compact('username','title_for_layout'));
            $this->request->data['NewsletterTemplate']['modified_by'] = $user_id;
            if ($this->NewsletterTemplate->save($this->data)) {
                $this->Session->setFlash(__('Newsletter template has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Newsletter template could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {	
            $this->data = $this->NewsletterTemplate->read(null, $id);	
	    $this->set(compact('title_for_layout'));
        }
    }

    function search() {
	$title_for_layout = 'Newsletters';
        if (isset($this->request->data['NewsletterTemplate']['template_name'])) {
            $keyword = $this->request->data['NewsletterTemplate']['template_name'];
            $this->paginate = array(
                'conditions' => array('NewsletterTemplate.template_name LIKE' => '%' . $keyword . '%'),
                'limit' => 10
            );
        } else {
            $this->paginate = array(
                'limit' => 10
            );
            $newsletterTemplate = $this->paginate('NewsletterTemplate');
        }
        $newsletterTemplate = $this->paginate('NewsletterTemplate');
        $this->set(compact('newsletterTemplate','title_for_layout'));
        if (sizeof($newsletterTemplate) == 0) {
            $this->Session->setFlash(__('No templates found.', true));
        }
        $this->render('index');
    }
}