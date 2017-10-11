<?php

class LogUtilComponent extends Component {
    
    var $components = array('Auth');        
    /**
     * The name of the model that represents log datasource.  Defaults to 'User'.
     *
     * @var string
     * @access public
     */
    var $logModel = 'ActivityLog';
        
    /**
     * Parameter data from Auth::$user
     *
     * @var array
     * @access public
     */
    var $user = array();
    
    /**
     * Parameter data from Controller::$params
     *
     * @var array
     * @access public
     */
    var $params = array();
    
    function startup(Controller $controller){        
        $this->params = $controller->params;
        $this->user=$this->Auth->user('id');
    }
    
    /**
     * get parameters from <code>$this->params['pass']</code>
     *
     * @return string, parameter which pass from url.
     */
    function __getParameters(){
        $parameters='';
        if (!empty($this->params['pass'])){
            foreach ($this->params['pass'] as $param){
                $parameters.=$param.'/';
            }
        }
        return $parameters;
    }
    /**
     * Add one log to the log table.
     *
     * @param string $action
     * @param string $controller
     */
    function userLog($action=null,$controller=null) {
        $action = (!empty($action)) ? $action :  $this->params['action'];
        $controller = (!empty($controller)) ? $controller :  $this->params['controller'];
        
        $data['ActivityLog']['user_id']= (!empty($this->user) ? $this->user : 0);
        $data['ActivityLog']['user_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['ActivityLog']['browser'] = $_SERVER['HTTP_USER_AGENT']; 
        $data['ActivityLog']['controller']= $controller;        
        $data['ActivityLog']['action']= $action;        
        $data['ActivityLog']['url']= $_SERVER['REQUEST_URI'];
        
        $logModel= & $this->__getModel();
        $logModel->create();
        $logModel->save($data);
    }
    
    /**
     * Returns a reference to the model object specified, and attempts
     * to load it if it is not found.
     *
     * @param string $name Model name (defaults to LogUtilComponent::$logModel)
     * @return object A reference to a model object
     * @access public
     */
    private function &__getModel($name = null) {
        $model = null;
        if (!$name) {
            $name = $this->logModel;
        }
        $model = ClassRegistry::init($name);
        if (empty($model)) {
            trigger_error(__('Log::getModel() - Model is not set or could not be found', true), E_USER_WARNING);
            return null;
        }
        return $model;
    }    
}
