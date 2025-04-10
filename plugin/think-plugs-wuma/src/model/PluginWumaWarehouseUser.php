<?php

// +----------------------------------------------------------------------
// | Wuma Plugin for ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2025 ThinkAdmin [ thinkadmin.top ]
// +----------------------------------------------------------------------
// | 官方网站: https://thinkadmin.top
// +----------------------------------------------------------------------
// | 免责声明 ( https://thinkadmin.top/disclaimer )
// | 收费插件 ( https://thinkadmin.top/fee-introduce.html )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/think-plugs-wuma
// | github 代码仓库：https://github.com/zoujingli/think-plugs-wuma
// +----------------------------------------------------------------------

declare (strict_types=1);

namespace plugin\wuma\model;

/**
 * 仓库用户模型
 *
 * @property int $deleted 删除状态(0未删,1已删)
 * @property int $id
 * @property int $login_num 登录测试
 * @property int $sort 排序权重
 * @property int $status 记录状态(0无效,1有效)
 * @property string $create_time 创建时间
 * @property string $login_ip 登录地址
 * @property string $login_time 登录时间
 * @property string $login_vars 登录参数
 * @property string $nickname 用户昵称
 * @property string $password 登录密码
 * @property string $remark 物码描述
 * @property string $token 接口令牌
 * @property string $update_time 更新时间
 * @property string $username 用户账号
 * @class PluginWumaWarehouseUser
 * @package plugin\wuma\model
 */
class PluginWumaWarehouseUser extends AbstractPrivate
{
}