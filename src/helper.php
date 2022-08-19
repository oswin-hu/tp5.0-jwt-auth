<?php
/**
 * Author: oswin
 * Time: 2022/8/19-15:03
 * Description:
 * Version: v1.0
 */
if (!function_exists('env')) {
    /**
     * 获取环境变量值
     * @access public
     * @param string $name    环境变量名（支持二级 .号分割）
     * @param string $default 默认值
     * @return mixed
     */
    function env($name = null, $default = null)
    {
        return \think\Env::get($name, $default);
    }
}
