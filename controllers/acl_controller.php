<?php
/**
 * Controller das permissões de acesso - 'ACL'
 *
 * Compatível com PHP 4 e 5
 *
 * Licenciado pela Creative Commons 3.0
 *
 * @filesource
 * @copyright   Copyright 2011, Pedro Elsner (http://pedroelsner.com/)
 * @author      Pedro Elsner <pedro.elsner@gmail.com>
 * @license    	Creative Commons 3.0 (http://creativecommons.org/licenses/by/3.0/br/)
 * @since       v 1.0
 */


/**
 * Acl Controller
 *
 * @use         AppController
 * @package    	acl_caching
 * @subpackage 	acl_caching.acl_controller
 * @link        http://www.github.com/pedroelsner/acl_caching
 */
class AclController extends AppController
{

	/**
	 * Nome do Controller
	 *
	 * @var string
	 * @access public
	 */
	var $name = 'Acl';

	/**
	 * Model que o Controller utiliza
	 *
	 * @var array
	 * @access public
	 */
	var $uses = array();
	
	
	/**
	 * Admin Index
	 *
	 * @access public
	 */
	function admin_index()
	{
		
		// Carrega Model
		$this->loadModel($this->AclCaching->getAroModel());
		
		// Seleciona todos os grupos de usuários
		$roles = $this->{$this->AclCaching->getAroModel()}->find('all',
			array(
				'order' => array(
					sprintf('%s.%s', $this->AclCaching->getAroModel(), $this->AclCaching->getAroDisplayField()) => 'ASC'
				)
			)
		);
		
		
		// Seleciona todas as actions do sistema
		$actions = $this->AclCaching->get_all_actions();
		
		
		/**
		 * Carrega as permissões
		 */
		$permissions = array();
	    $methods     = array();
		
		foreach($actions as $full_action)
    	{
	    	$url = String::tokenize($full_action, '/');
	    	
			if (count($url) == 2)
			{
				$plugin_name     = null;
				$controller_name = $url[0];
				$action          = $url[1];
			}
			elseif(count($url) == 3)
			{				
				$plugin_name     = $url[0];
				$controller_name = $url[1];
				$action          = $url[2];
			}
    		
			
		    foreach($roles as $role)
	    	{
	    	    $aro_node = $this->AclCaching->Aro->node($role);
	            if(!empty($aro_node))
	            {
	            	$aco_node = $this->AclCaching->Aco->node($full_action);
	        	    if(!empty($aco_node))
	        	    {
	        	    	$authorized = $this->AclCaching->checkDB($role, $full_action);
	        	    	$permissions[$role[$this->AclCaching->getAroModel()][$this->AclCaching->getAroPrimaryKey()]] = $authorized ? 1 : 0 ;
					}
	            }
	    		else
        	    {
        	        /*
        	         * Não conseguiu verificar a permissão
        	         */
        	        $permissions[$role[$this->AclCaching->getAroModel()][$this->AclCaching->getAroPrimaryKey()]] = -1;
        	    }
    		}
			
    		if(isset($plugin_name))
            {
            	$methods['plugin'][$plugin_name][$controller_name][] = array('name' => $action, 'permissions' => $permissions);
            }
            else
            {
        	    $methods['app'][$controller_name][] = array('name' => $action, 'permissions' => $permissions);
            }
    	}
		
		
		
		// Envia variáveis para a View
		$this->set(compact(array('roles', 'methods')));
		
	}
	
	
	/**
	 * Admin Allow
	 *
	 * Libera permissão para o ARO
	 *
	 * @param int $role_id
	 * @access public
	 */
	function admin_allow($role_id)
	{
	    if ( !($this->RequestHandler->isAjax()) )
		{
			exit();
		}
		
		
		// Carrega Model
		$this->loadModel($this->AclCaching->getAroModel());
		
		$role =& $this->{$this->AclCaching->getAroModel()};
        $role->id = $role_id;
        
        $aco_path = $this->AclCaching->getPassedAcoPath();
        
        /**
         * Verifica se já existe o ARO na tabela
         */
        $aro_node = $this->Acl->Aro->node($role);
        if( !(empty($aro_node)) )
        {
            if( !($this->AclCaching->allow($role, $aco_path)) )
            {
                $this->set('acl_error', true);
            }
        }
        else
        {
            $this->set('acl_error', true);
            $this->set('acl_error_aro', true);
        }
        
        $this->set('role_id', $role_id);
        $this->AclCaching->setAcoVariables();
        
        $this->render('ajax_allow');
        
	}
	
	
	/**
	 * Admin Deny
	 * 
	 * Nega permissão para ARO
	 * 
	 * @param int $role_id
	 * @access public
	 */
	function admin_deny($role_id)
	{
	    
		if ( !($this->RequestHandler->isAjax()) )
		{
			exit();
		}
		
		
		// Carrega Model
		$this->loadModel($this->AclCaching->getAroModel());
		
		$role =& $this->{$this->AclCaching->getAroModel()};
        $role->id = $role_id;
        
        $aco_path = $this->AclCaching->getPassedAcoPath();
		
		/**
         * Verifica se já existe o ARO na tabela
         */
        $aro_node = $this->Acl->Aro->node($role);
        if( !(empty($aro_node)) )
        {
			if( !($this->AclCaching->deny($role, $aco_path)) )
            {
                $this->set('acl_error', true);
            }
        }
        else
        {
        	$this->set('acl_error', true);
        }
        
        $this->set('role_id', $role_id);
        $this->AclCaching->setAcoVariables();
        
        $this->render('ajax_deny');
        
	}
	
	
}
