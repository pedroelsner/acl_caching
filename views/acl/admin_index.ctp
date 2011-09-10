<?php
echo $this->Html->script('/acl_caching/js/jquery');
echo $this->Html->script('/acl_caching/js/jquery-ui');
echo $this->Html->script('/acl_caching/js/functions');

echo $this->Html->css('/acl_caching/css/style');
?>

<table id="acl_right">
    <thead>
        <tr>
            <?php
                $column_count = 1;
                $headers = array(' ');
                foreach($roles as $role)
                {
                    $headers[] = $role[$AclCaching->getAroModel()][$AclCaching->getAroDisplayField()];
                    $column_count++;
                }
                echo $this->Html->tableHeaders($headers);
            ?>
        </tr>
    </thead>
    
    <tbody>
        <?php
            $previous_ctrl_name = '';
            $i = 0;
            
            /** 
             * APP
             */
            if(isset($methods['app']) && is_array($methods['app']))
            {
                foreach($methods['app'] as $controller_name => $ctrl_infos)
                {
                    if($previous_ctrl_name != $controller_name)
                    {
                        $previous_ctrl_name = $controller_name;
                        $color = ($i % 2 == 0) ? 'acl_bgcolor1' : 'acl_bgcolor2';
                    }
                    
                    foreach($ctrl_infos as $ctrl_info)
                    {
                        echo '<tr>';
                        printf('<td class="%s">%s->%s</td>', $color, $controller_name, $ctrl_info['name']);
                        
                        foreach($roles as $role)
                        {
                            printf('<td class="cell %s">', $color);
                            echo '<span id="right__' . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . '_' . $controller_name . '_' . $ctrl_info['name'] . '">';
                        
                            if(isset($ctrl_info['permissions'][$role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()]]))
                            {
                                if($ctrl_info['permissions'][$role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()]] == 1)
                                {
                                    
                                    if ($AclCaching->check(null, array('plugin' => 'acl_caching', 'controller' => 'acl', 'action' => 'allow')))
                                    {
                                        echo $this->Html->image(
                                            '/acl_caching/img/tick.png',
                                            array(
                                                'alt'     => __('Liberado', true),
                                                'class'   => 'pointer',
                                                'onClick' => "return acl_toggle_right(true, '" . $this->Html->url('/') . "', 'right__" . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . "_" . $controller_name . "_" . $ctrl_info['name'] . "', '" . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . "', '', '" . $controller_name . "', '" . $ctrl_info['name'] . "')",
                                                'escape'  => false
                                            )
                                        );
                                    }
                                    else
                                    {
                                        echo $this->Html->image(
                                            '/acl_caching/img/tick.png',
                                            array(
                                                'alt'   => __('Liberado', true),
                                            )
                                        );
                                    }
                                    
                                }
                                else
                                {
                                    
                                    if ($AclCaching->check(null, array('plugin' => 'acl_caching', 'controller' => 'acl', 'action' => 'deny')))
                                    {
                                        echo $this->Html->image(
                                            '/acl_caching/img/cross.png',
                                            array(
                                                'alt'     => __('Bloqueado', true),
                                                'class'   => 'pointer',
                                                'onClick' => "return acl_toggle_right(false, '" . $this->Html->url('/') . "', 'right__" . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . "_" . $controller_name . "_" . $ctrl_info['name'] . "', '" . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . "', '', '" . $controller_name . "', '" . $ctrl_info['name'] . "')",
                                                'escape'  => false
                                            )
                                        );
                                    }
                                    else
                                    {
                                        echo $this->Html->image(
                                            '/acl_caching/img/cross.png',
                                            array(
                                                'alt'   => __('Bloqueado', true),
                                            )
                                        );
                                    }
                                    
                                }
                            }
                            else
                            {
                                /**
                                 * Se action não foi encontrada na tabela 'acos'
                                 */
                                echo $this->Html->image(
                                    '/acl_caching/img/important.png',
                                    array(
                                        'title' => __('Este nó ACO não foi encontrado. Por favor, atualize a listagem das ACO.', true)
                                    )
                                );
                            }
                            
                            echo '</span>';
                            
                            echo ' ';
                            echo $this->Html->image(
                                '/acl_caching/img/loading.gif',
                                array(
                                    'id'    => 'right__' . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . '_' . $controller_name . '_' . $ctrl_info['name'] . '_spinner',
                                    'class' => 'hidden'
                                )
                            );
            
                            echo '</td>';
                        }
                        
                        echo '</tr>';
                    }
            
                    $i++;
                }    
            }
            
            
            /** 
             * PLUGINS
             */
            if(isset($methods['plugin']) && is_array($methods['plugin']))
            {
                foreach($methods['plugin'] as $plugin_name => $plugin_ctrler_infos)
                {
                    $color = null;
                    echo '<tr class="title"><td colspan="' . $column_count . '" class="acl_bgcolor_plugin">' . __('Plugin', true) . ' ' . $plugin_name . '</td></tr>';
                    $i = 0;
                    foreach($plugin_ctrler_infos as $plugin_ctrler_name => $plugin_methods)
                    {
                        
                        if($previous_ctrl_name != $plugin_ctrler_name)
                        {
                            $previous_ctrl_name = $plugin_ctrler_name;
                            $color = ($i % 2 == 0) ? 'acl_bgcolor1' : 'acl_bgcolor2';
                        }
          
                        foreach($plugin_methods as $method)
                        {
                            
                            echo '<tr>';
                            printf('<td class="%s">%s->%s</td>', $color, $plugin_ctrler_name, $method['name']);
                            
                            foreach($roles as $role)
                            {
                                printf('<td class="cell %s">', $color);
                                echo '<span id="right_' . $plugin_name . '_' . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . '_' . $plugin_ctrler_name . '_' . $method['name'] . '">';
                            
                                if(isset($method['permissions'][$role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()]]))
                                {
                                    
                                    if($method['permissions'][$role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()]] == 1)
                                    {
                                        
                                        
                                        if ($AclCaching->check(null, array('plugin' => 'acl_caching', 'controller' => 'acl', 'action' => 'allow')))
                                        {
                                            echo $this->Html->image(
                                                '/acl_caching/img/tick.png',
                                                array(
                                                    'alt'     => __('Liberado', true),
                                                    'class'   => 'pointer',
                                                    'onClick' => "return acl_toggle_right(true, '" . $this->Html->url('/') . "', 'right_" . $plugin_name . "_" . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . "_" . $plugin_ctrler_name . "_" . $method['name'] . "', '" . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . "', '" . $plugin_name . "', '" . $plugin_ctrler_name . "', '" . $method['name'] . "')",
                                                    'escape'  => false
                                                )
                                            );
                                        }
                                        else
                                        {
                                            echo $this->Html->image(
                                                '/acl_caching/img/tick.png',
                                                array(
                                                    'alt'   => __('Liberado', true),
                                                )
                                            );
                                        }
                                        
                                    }
                                    else
                                    {
                                        
                                        
                                        if ($AclCaching->check(null, array('plugin' => 'acl_caching', 'controller' => 'acl', 'action' => 'deny')))
                                        {
                                            echo $this->Html->image(
                                                '/acl_caching/img/cross.png',
                                                array(
                                                    'alt'     => __('Bloqueado', true),
                                                    'class'   => 'pointer',
                                                    'onClick' => "return acl_toggle_right(false, '" . $this->Html->url('/') . "', 'right_" . $plugin_name . "_" . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . "_" . $plugin_ctrler_name . "_" . $method['name'] . "', '" . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . "', '" . $plugin_name . "', '" . $plugin_ctrler_name . "', '" . $method['name'] . "')",
                                                    'escape'  => false
                                                )
                                            );
                                        }
                                        else
                                        {
                                            echo $this->Html->image(
                                                '/acl_caching/img/cross.png',
                                                array(
                                                    'alt'   => __('Bloqueado', true),
                                                )
                                            );
                                        }
                                        
                                    }
                                    
                                }
                                else
                                {
                                    /**
                                     * Se action não foi encontrada na tabela 'acos'
                                     */
                                    echo $this->Html->image(
                                        '/acl_caching/img/important.png',
                                        array(
                                            'title' => __('Este nó ACO não foi encontrado. Por favor, atualize a listagem das ACO.', true)
                                        )
                                    );
                                }
                                
                                echo '</span>';
                                
                                echo ' ';
                                echo $this->Html->image(
                                    '/acl_caching/img/loading.gif',
                                    array(
                                        'id'    => 'right_' . $plugin_name . '_' . $role[$AclCaching->getAroModel()][$AclCaching->getAroPrimaryKey()] . '_' . $plugin_ctrler_name . '_' . $method['name'] . '_spinner',
                                        'class' => 'hidden'
                                    )
                                );
                
                                echo '</td>';
                            }
                            
                            
                            echo '</tr>';
                        }
                                
                        $i++;
                    }
                }
            }
        ?>        
    </tbody>
</table>