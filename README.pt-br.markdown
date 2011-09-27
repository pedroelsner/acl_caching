# ACL Caching

Plugin que extende as funcionabilidades do AclComponente fazendo cache de todas as permissões de acesso no login, além de disponíbilizar funções especiais e um helper para tratamento de links.

## Compatibilidade

Compatível com CakePHP v 1.3

## Desvantagem

*   Carrega todas as permissões no login, sendo necessario realizar logout caso queria que novas definições de acesso entrem em vigor. Contudo, esta desvantagem pode ser “revertida” pela função especial `checkDB()` presente no plugin.

## Vantagens

*   Carrega todas as permissões no login, evitando excessos de consultas no banco;
*   Pode ser chamado pelo controller ou view;
*   Conta com funções especiais como: `checkIfOne()`, `checkIfAll()` e `checkDB()`;
*   Conta com o helper Acl_HTML que exibe links apenas para os usuários com permissão.

# Instalação

Se você deseja começar um novo projeto com o ACL_Caching, acesse este tutorial: http://pedroelsner.com/2011/07/controle-de-acesso-a-nivel-grupo-usuario-no-cakephp/ 

## Download

Faça o download do plugin e coloque seu conteúdo dentro de `/app/plugins/acl_caching` ou em outro diretório para plugins do CakePHP.

## Configurações

Edite o arquivo __/app/app_controller.php__:

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

Parâmetros de configurações do componente:

*   __contain:__ Se você defini o recursive de todos os models como -1, defina está opção como TRUE;
*   __model:__ Informe o model responsável pelos grupos dos usuário;
*   __primaryKey:__ Informe o nome do campo;
*   __displayField:__ Informe o nome do campo;
*   __foreignKey:__ Informe o nome do campo que consta na tabela de usuários.

## Definições de acesso

Para configurar as regras de permissões para os grupos de usuários, acesse `http://seusite.com/admin/acl_caching/acl/`

# Utilização do Componente

Você pode chamar as funções do plugin nos controllers utilizando `$this->Acl` ou `$this->AclCaching`, pode também chama-las nas views através das variáveis `$Acl` e `$AclCaching`.

# Funções especiais

## check()

Esta função verifica se o usuário logado tem permissão de acesso para a url informada.

<pre>
/**
 * Utilizando no Controller
 */
if ($this->AclCaching->check(null, array('controller' => 'usuarios', 'action' => 'admin_index')))
{
   // Tem permissão
}

/**
 * Utilizando na View
 */
if ($AclCaching->check(null, array('controller' => 'usuarios', 'action' => 'admin_index')))
{
   // Tem permissão
}
</pre>

## checkIfOne()

Esta função verifica se o usuário logado tem permissão de acesso em pelo menos UMA url.

<pre>
$urls = array(
    array(
        'controller' => 'grupos',
        'action'     => 'admin_adicionar'
    ),
    array(
        'controller' => 'acl',
        'action'     => 'admin_index',
        'plugion'    => 'acl_caching'
    )
);

/**
 * Utilizando no Controller
 */
if ($this->AclCaching->checkIfOne(null, $urls))
{
    // Tem permissão
}

/**
 * Utilizando na View
 */
if ($AclCaching->checkIfOne(null, $urls))
{
    // Tem permissão
}
</pre>

## checkIfAll()

Esta função verifica se o usuário logado tem permissão de acesso em TODAS as urls.

<pre>
$urls = array(
    array(
        'controller' => 'grupos',
        'action'     => 'admin_adicionar'
    ),
    array(
        'action' => 'admin_excluir'
    )
);

/**
 * Utilizando no Controller
 */
if ($this->AclCaching->checkIfAll(null, $urls))
{
    // Tem permissão
}

/**
 * Utilizando na View
 */
if ($AclCaching->checkIfAll(null, $urls))
{
    // Tem permissão
}
</pre>

## checkDB()

Está função é utilizada para verificar a permissão de acesso de um determinado usuário a uma url específica, verificando diretamente no banco de dados.

Sendo assim, como o plugin grava as permissões de acesso em uma variável de sessão no login, você pode utiliza-la para forçar o sistema a utilizar o banco de dados para verificar a permissão de acesso.

<pre>
/**
 * Utilizando no Controller
 */
if ($this->AclCaching->checkDB(array('Model' => 'Usuario', 'foreignKey' => 2), array('action' => 'admin_index')))
{
    // Tem permissão
}

/**
 * Utilizando na View
 */
if ($AclCaching->checkDB(array('Model' => 'Usuario', 'foreignKey' => 2), array('action' => 'admin_index')))
{
    // Tem permissão
}
</pre>

## forceAllow()

Quando utilizamos Auth ou ACL, para podermos liberar o acesso a todas as actions do sistema usamos a função `$this->Auth->allow("*")`. Agora, utilizando o plugin ACL_Caching usaremos __$this->AclCaching->forceAllow()__.

Chamando está função, desativamos todo o sistema de permissão, liberando acesso a todas as actions do sistema e exibindo todos os links do helper Acl_HTML:

<pre>
// Liberando GERAL =D
$this->AclCaching->forceAllow();
</pre>

## flushCache()

Apaga a sessão que armazena as permissões de acesso. O plugin automáticamente carrega todas as permissões quando a função `check()` for solicitada.

<pre>
// Utilizando no Controller
$this->AclCaching->flushCache();

// Utilizando na View
$AclCaching->flushCache();
</pre>

# Utilização do Helper

Este helper foi desenvolvido simplesmente para ocultar links que os usuário não tenham permissão para acessar.

Vamos supor que temos o link `Adicionar novo Post` e desejamos mostra-lo apenas para usuários com permissão para cadastrar, então usaremos o helper `Acl_Html` ao invés do `Html`.

<pre>
// Link só será exibido se usuário tiver permissão
$this->AclHtml->link(
    __('Adicionar novo Post', true),
    array(
        'controller' => 'posts',
        'action'     => 'adicionar',
        'admin'      => true
    )
);
</pre>

Haverá situação que você deseja exibir apenas o texto `Adicionar novo Post` se o usuário não tiver permissão de acesso (ao inves de não exibir nada). Para isso, usamos a opção __show__ definida como __true__.

<pre>
// Se usuário tiver permissão exibe o link, caso não tenha, exibe apenas o texto
$this->AclHtml->link(
    __('Adicionar novo Post', true),
    array(
        'controller' => 'posts',
        'action'     => 'adicionar',
        'admin'      => true
    ),
    array(
        'show' => true // Força a exibição do texto
    )
);
</pre>

# Copyright e Licença

Copyright 2011, Pedro Elsner (http://pedroelsner.com/)

Licenciado pela Creative Commons 3.0 (http://creativecommons.org/licenses/by/3.0/br/)