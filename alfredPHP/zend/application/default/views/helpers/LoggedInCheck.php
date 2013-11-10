<?php 
	class Base_View_Helper_LoggedInCheck extends Zend_View_Helper_Abstract 
{
    public function loggedInCheck ()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $userid = strtoupper($auth->getIdentity()->user_id);

            $logoutUrl = $this->view->url(array('module' => 'default', 'controller'=>'login',
                'action'=>'logout'), null, true);
            return '<p>Logged in as: <span class="loginName"> ' . $userid .  '</span>
            <input type="button" class="btn" value="Logout" 
            	onclick="location.href=\''. $logoutUrl . '\'" />
            </p>';
        } else 
        {

            return '<p>Welcome, Please login</p>';
        
        } 


    }
}