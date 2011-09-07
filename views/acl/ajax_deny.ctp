<?php

echo '<span id="right_' . $plugin . '_' . $role_id . '_' . $controller_name . '_' . $action . '">';

    if(isset($acl_error))
    {
        $alt = isset($acl_error_aro) ? __('Erro: ARO não exista mais.', true) : __('Erro: ACO não exista mais.', true);
        echo $this->Html->image(
			'/acl_caching/img/important.png',
			array(
				'alt'   => $alt,
				'style' => $this->Html->style(
					array(
						'cursor' => 'pointer'
					)
				)
			)
		);
    }
    else
    {
        echo $this->Html->image(
			'/acl_caching/img/cross.png',
			array(
				'alt'   => __('Bloqueado', true),
				'style' => $this->Html->style(
					array(
						'cursor' => 'pointer'
					)
				)
			)
		);
    }
    
echo '</span>';
?>