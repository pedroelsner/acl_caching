# ACL Caching

Plugin that extends the functionality of AclComponente by caching all the access permissions at login, and provide special functions and a helper for the treatment of links.

## Compatibility

Compatible with CakePHP v 1.3

## Disadvantage

*   Loads all permissions at login, and logout if necessary to make new definitions wanted access to take effect. However, this disadvantage can be "reversed" by the special function `checkDB()` in this plugin.

## Advantages

*   Loads all permissions at login, avoiding excesses of consultations in the data base;
*   Can be called by the controller or view;
*   It has special features like: `checkIfOne()`, `checkIfAll()` and `checkDB()`;
*   It has Acl_HTML helper that displays links to you only for users with permission.

# Installation

If you want to start a new project with ACL_Caching, see this tutorial: http://pedroelsner.com/2011/07/controle-de-acesso-a-nivel-grupo-usuario-no-cakephp/ 

## Download

Download the plugin and place its contents inside `/app/plugins/acl_caching` or other directory plugins for CakePHP.

## Configuration

Edit the file __/app/app_controller.php__:

<pre>
var $components = array(
    'Auth',          
    'Session',       
    'RequestHandler',
    'AclCaching.AclCaching' => array(
        'use' => array(
            'contain' => false
        ),
            'aro' => array(
            'model' 	   => 'Group',
            'primaryKey'   => 'id',
            'displayField' => 'name',
            'foreignKey'   => 'group_id'
        )
    )
);

var $helpers = array(
    'Session',
    'AclCaching.AclHtml'
);
</pre>

Settings parameters:

*   __contain:__ If you set the recursive models of all to -1, set this to TRUE;
*   __model:__ Enter the model name of the groups;
*   __primaryKey:__ Enter the field name;
*   __displayField:__ Enter the field name;
*   __foreignKey:__ Enter the field name that appears into table users.

## Access settings

To set the rules of permissions for the user groups, access `http://seusite.com/admin/acl_caching/acl/`

# Using the Component

You can call the functions in the plugin controllers using `$ this->Acl` or `$this-> AclCaching`, you also can call them in views through the variables `$Acl` and `$AclCaching`.

# Special Functions

## check()

This function checks if the logged in user may access to URL.

<pre>
/**
 * Controller
 */
if ($this->AclCaching->check(null, array('controller' => 'usuarios', 'action' => 'admin_index')))
{
    // Has permission
}

/**
 * View
 */
if ($AclCaching->check(null, array('controller' => 'usuarios', 'action' => 'admin_index')))
{
   // Has permission
}
</pre>

## checkIfOne()

This function checks if the logged in user may access to at least a URL.

<pre>
$urls = array(
    array(
        'controller' => 'groups',
        'action'     => 'admin_add'
    ),
    array(
        'controller' => 'acl',
        'action'     => 'admin_index',
        'plugion'    => 'acl_caching'
    )
);

/**
 * Controller
 */
if ($this->AclCaching->checkIfOne(null, $urls))
{
    // Has permission
}

/**
 * View
 */
if ($AclCaching->checkIfOne(null, $urls))
{
    // Has permission
}
</pre>

## checkIfAll()

This function checks if the logged in user may access to ALL urls.

<pre>
$urls = array(
    array(
        'controller' => 'groups',
        'action'     => 'admin_add'
    ),
    array(
        'action' => 'admin_delete'
    )
);

/**
 * Controller
 */
if ($this->AclCaching->checkIfAll(null, $urls))
{
    // Has permission
}

/**
 * View
 */
if ($AclCaching->checkIfAll(null, $urls))
{
    // Has permission
}
</pre>

## checkDB()

This function is used to check the access permission for a particular user to a specific URL, checking directly in the database.

Thus, the plugin writes the access permissions in a session variable on login, you can use it to force the system to use the database to check the access permission.

<pre>
/**
 * Controller
 */
if ($this->AclCaching->checkDB(array('Model' => 'Usuario', 'foreignKey' => 2), array('action' => 'admin_index')))
{
    // Has permission
}

/**
 * View
 */
if ($AclCaching->checkDB(array('Model' => 'Usuario', 'foreignKey' => 2), array('action' => 'admin_index')))
{
    // Has permission
}
</pre>

## forceAllow()

When using Auth and ACL, in order to allow access to all actions of the system we use the function `$ this-> Auth-> allow ("*")`. Now, using the plugin will use ACL_Caching __$this->AclCaching->forceAllow()__.

Calling this function, turn off all the permit system, freeing up access to all actions of the system and displaying all the helper Acl_HTML links:

<pre>
// Allow ALL
$this->AclCaching->forceAllow();
</pre>

## flushCache()

Deletes the session that holds the access permissions. The plugin automatically loads all the permissions check when the function `check()` is requested.

<pre>
// Controller
$this->AclCaching->flushCache();

// View
$AclCaching->flushCache();
</pre>

# Using the Helper

This helper is designed simply to hide links that the user does not have permission to access.

Suppose we have the link `Add New Post` and we want to show it only for users with permission to enroll, then we use the helper `Acl_Html` instead of `Html`.

<pre>
// Link only appears if user has permission
$this->AclHtml->link(
    __('Add New Post', true),
    array(
        'controller' => 'posts',
        'action'     => 'add',
        'admin'      => true
    )
);
</pre>

There will be a situation that you want to display only the text `Add New Post` if you do not have permission to access (instead of not displaying anything). For this we set __show__ = __true__.

<pre>
// If the user has permission displays the link, if not, displays only the text
$this->AclHtml->link(
    __('Add New Post', true),
    array(
        'controller' => 'posts',
        'action'     => 'add',
        'admin'      => true
    ),
    array(
        'show' => true // display text
    )
);
</pre>

# Copyright e License

Copyright 2011, Pedro Elsner (http://pedroelsner.com/)

Licensed under Creative Commons 3.0 (http://creativecommons.org/licenses/by/3.0/)