<?php
/**
 * Helper que imprime link apenas se usuário tiver permiss�o para acessa-lo
 *
 * Compat�vel com PHP 4 e 5
 *
 * Licenciado pela Creative Commons 3.0
 *
 * @filesource
 * @copyright   Copyright 2011, Pedro Elsner (http://pedroelsner.com/)
 * @author      Pedro Elsner <pedro.elsner@gmail.com>
 * @license     Creative Commons 3.0 (http://creativecommons.org/licenses/by/3.0/br/)
 * @since       v 1.0
 */

App::import('Helper', 'Acl');


/**
 * Acl Html Helper
 *
 * @use         HtmlHelper
 * @package     acl_caching
 * @subpackage  acl_caching.acl_html
 * @link        http://www.github.com/pedroelsner/acl_caching
 */
class AclHtmlHelper extends HtmlHelper
{
    
/**
 * Helpers auxiliares
 *
 * @var array
 * @access public
 */
    var $helpers = array('Session');
    
    
/**
 * Has Permission
 *
 * Verifica permissão para a URL informada
 * 
 * @param $url Pode ser uma string ou um array
 * @return boolean
 * @access 
 */
    function _hasPermission($url)
    {
        
        // Se não houver permissões, libera
        if ( !($this->Session->check('Auth.Permissions')) )
        {
            return true;
        }
        
        if (!is_array($url)) {
            return true;
        }
       
        extract($url);
        
        if(isset($plugin))
        {
            $plugin = Inflector::camelize($plugin);
        }
        
        if (!isset($controller))
        {
            $controller = $this->params['controller'];
        }  
        $controller = Inflector::camelize($controller);
        
        if (!isset($action))
        {
            $action = $this->params['action'];
        }
        
        if (isset($this->params['prefix']))
        {
           $action = $this->params['prefix'] . '_' . $action;
        }
       
        if(isset($plugin) and !empty($plugin)) {
           $controller = $plugin.'/'.$controller;
        }
        
        $permission = 'controllers/'.$controller.'/'.$action;
       
        return in_array($permission, $this->Session->read('Auth.Permissions'));
     
    }

    
/**
 * Link
 *
 * Método personalizado que antes de retornar o código HTML
 * verificando se usuário possuí permissão para acessar
 *
 * @param string $title
 * @param mixed $url 
 * @param array $options
 * @param string $confirmMessage
 * @return string
 * @access public
 * @link http://book.cakephp.org/view/1442/link
 */
    function link($title, $url = null, $options = array(), $confirmMessage = false)
    {
        
        // Verifica permiss�o
        $permissao = $this->_hasPermission($url);
        
        
        if (isset($options['extern']))
        {
            $permissao = true;
        }
        
        
        if ($permissao)
        {
            return parent::link( $title, $url, $options, $confirmMessage);
        }
        else
        {
            if (isset($options['show']))
            {
                return $title;
            }
        }
        
    }

}