<?php 

class Base_View_Helper_LoadSkin extends Zend_View_Helper_Abstract
{
    public function loadSkin ($skin)
    {
        
        //load the skin config file
        $skinData = new Zend_Config_Xml(  './public/skins/' . $skin . '/skin.xml');
        
        $stylesheet = $skinData->stylesheets->stylesheet;
        
        // append each style sheet
        if(is_array($stylesheet)) {
        	$stylesheets = $skinData->stylesheets->stylesheet->toArray();
            foreach ($stylesheets as $stylesheet) {
            	if(!empty($stylesheet)){
                	$this->view->headLink()->appendStylesheet( $this->view->baseUrl() . '/public/skins/' . $skin . 
                        '/css/' . $stylesheet);
            	}
            }
            
        } else {
        	if(!empty($stylesheet)){
        		$this->view->headLink()->appendStylesheet( $this->view->baseUrl() . '/public/skins/' . $skin .
        				'/css/' . $stylesheet);
        	}
        }
        
    }}