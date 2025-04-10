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

namespace plugin\wuma\command;

use think\admin\Command;
use think\admin\extend\CodeExtend;
use think\admin\Library;
use think\admin\service\ProcessService as Process;
use think\Exception;

/**
 * 同步物码规则
 * @class Create
 * @package plugin\wuma\command
 */
class Create extends Command
{
    /**
     * 物码批次号
     * @var string
     */
    protected $batch;

    /**
     * 指令配置
     * @return void
     */
    protected function configure()
    {
        $this->setName('xdata:wuma:create');
        $this->addArgument('batch', null, '待处理的物码批次号');
        $this->setDescription('创建物码压缩文件');
    }

    /**
     * 任务执行
     * @return void
     * @throws \think\admin\Exception
     */
    public function handle()
    {
        $this->batch = $this->input->getArgument('batch');
        if (empty($this->batch)) $this->setQueueError("批次号不能为空！");
        // 物码服务生成文件
        [$status, $message] = $this->_create($this->batch);
        empty($status) ? $this->setQueueError($message) : $this->setQueueSuccess($message);
    }

    /**
     * 创建物码规则
     * @param string $batch 物码批次号
     * @return array [state, info, result]
     */
    private function _create(string $batch): array
    {
        try {
            $data = $this->_exec("create {$batch}");
            if (isset($data['code']) && isset($data['data']['file'])) {
                return [true, $data['info'], $data['data']];
            } else {
                return [false, $data['info'], ''];
            }
        } catch (\Exception $exception) {
            return [false, $exception->getMessage()];
        }
    }

    /**
     * 执行操作指令
     * @param string $params
     * @return array
     * @throws \think\Exception
     * @throws \think\admin\Exception
     */
    private function _exec(string $params): array
    {
        $auth = CodeExtend::random(20);
        $this->app->cache->set("create_auth_{$this->batch}", $auth, 360);
        $token = base64_encode(json_encode([
            'auth' => $auth, 'host' => sysconf('site_host'), 'target' => syspath('safefile/code/')
        ]));
        $binary = dirname(__DIR__, 2) . '/stc/bin/' . (Process::iswin() ? 'coder.exe' : 'coder');
        // 赋予文件可执行权限
        if (!Process::iswin() && file_exists($binary) && !is_executable($binary)) {
            Process::exec("/usr/bin/chmod +x {$binary}");
        }
        // 检查是否具有可执行权限
        if (!is_executable($binary)) {
            $filename = substr($binary, strlen(Library::$sapp->getRootPath()));
            throw new Exception("生码工具[./{$filename}]没有可执行权限！");
        }
        // 通过二进制执行物码操作
        $result = Process::exec("{$binary} {$token} {$params}");
        if ($this->app->isDebug()) {
            $this->app->log->notice("Execute: {$binary} {$token} {$params}");
            $this->app->log->notice("Results: {$result}");
        }
        return json_decode(trim($result) ?: '[]', true);
    }
}