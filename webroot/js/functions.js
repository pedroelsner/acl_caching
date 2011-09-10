/**
 * Principal função do plugin
 *
 * @author  Pedro Elsner <pedro.elsner@gmail.com>
 * @license http://creativecommons.org/licenses/by/3.0/br/ Creative Commons 3.0
 * @link     http://www.github.com/pedroelsner/acl_caching
 */

function acl_toggle_right(start_granted, app_root_url, span_id, role_id, plugin, controller, action)
{
    
    if(start_granted)
    {
        var url1 = app_root_url + "admin/acl_caching/acl/deny/" + role_id + "/plugin:" + plugin + "/controller:" + controller + "/action:" + action;
        var url2 = app_root_url + "admin/acl_caching/acl/allow/" + role_id + "/plugin:" + plugin + "/controller:" + controller + "/action:" + action;
    }
    else
    {
        var url1 = app_root_url + "admin/acl_caching/acl/allow/" + role_id + "/plugin:" + plugin + "/controller:" + controller + "/action:" + action;
        var url2 = app_root_url + "admin/acl_caching/acl/deny/" + role_id + "/plugin:" + plugin + "/controller:" + controller + "/action:" + action;
    }
    
    
    
    $("#" + span_id).toggle(
        function()
        {
            $("#right_" + plugin + "_" + role_id + "_" + controller + "_" + action).hide();
            $("#right_" + plugin + "_" + role_id + "_" + controller + "_" + action + "_spinner").show();
                                
            $.ajax({
                url: url1,
                dataType: "html", 
                cache: false,
                success: function (data, textStatus) 
                {
                    $("#right_" + plugin + "_" + role_id + "_" + controller + "_" + action).html(data);
                },
                complete: function()
                {
                    $("#right_" + plugin + "_" + role_id + "_" + controller + "_" + action + "_spinner").hide();
                    $("#right_" + plugin + "_" + role_id + "_" + controller + "_" + action).show();
                }
            });
        }, 
        function()
        {
            $("#right_" + plugin + "_" + role_id + "_" + controller + "_" + action).hide();
            $("#right_" + plugin + "_" + role_id + "_" + controller + "_" + action + "_spinner").show();
            
            $.ajax({
                url: url2,
                dataType: "html", 
                cache: false,
                success: function (data, textStatus) 
                {
                    $("#right_" + plugin + "_" + role_id + "_" + controller + "_" + action).html(data);
                },
                complete: function()
                {
                    $("#right_" + plugin + "_" + role_id + "_" + controller + "_" + action + "_spinner").hide();
                    $("#right_" + plugin + "_" + role_id + "_" + controller + "_" + action).show();
                }
            });
        }
    ); 
}