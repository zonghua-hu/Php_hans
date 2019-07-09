<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/1
 * Time: 16:57
 */
namespace Swoft\server;

use Swoft\App;

abstract class AbstractServer implements IServer
{
    public $tcpSetting = [];
    public $httpSetting = [];
    public $serverSetting = [];
    public $setting = [];
    protected $server;
    protected $scriptFile;
    protected $workerLock;

    public function __construct()
    {
        App::$server = $this;
        $settings = App::getAppProperties()->get('server');
        $this->initSettings($settings);
    }
    private function initSettings(array $settings)
    {
        if (!isset($settings['tcp'])) {
            throw new \InvalidArgumentException("tcp startup parameter is not config ,settings=".\json_encode($settings['tcp']));
        }
        if (!isset($settings['http'])) {
            throw new \InvalidArgumentException('http startup parameter is not config,settings = '.json_encode($settings['http']));
        }
        if (!isset($settings['server'])) {
            throw new \InvalidArgumentException('server startup parameter is not config,settings='.json_encode($settings['server']));
        }
        if (!isset($settings['setting'])) {
            throw new \InvalidArgumentException('"setting" parameter is not config,setting='.json_encode($settings['setting']));
        }
        foreach ($settings['setting'] as $key => $value) {
            if ($value && \is_string($value) && $value[0] === '@') {
                $settings['setting'][$key] = App::getAlias($value);
            }
        }
        $this->setting = $settings['setting'];
        $this->tcpSetting = $settings['tcp'];
        $this->httpSetting = $settings['http'];
        $this->serverSetting = $settings['server'];

        if (isset($this->setting['task_ipc_mode'])) {
            $this->setting['task_ipc_mode'] = (int)$this->setting['task_ipc_mode'];
        }
        if (isset($this->setting['message_queue_key'])) {
            $this->setting['message_queue_key'] = (int)$this->setting['message_queue_key'];
        }
    }
    protected function getListenTcpSetting():array
    {
        $listenTcpSetting = $this->tcpSetting;
        unset($listenTcpSetting['host'],$listenTcpSetting['port'],$listenTcpSetting['mode'],$listenTcpSetting['type']);
        return $listenTcpSetting;
    }
    public function setDaemonize()
    {
        // TODO: Implement setDaemonize() method.
        $this->setting['daemonize'] = 1;
        return $this;
    }
    public function isDaemonize():bool
    {
        return (int)$this->setting['daemonize'] === 1;
    }

    public function getPname():string
    {
        return $this->serverSetting['pname'];
    }
    public function getServerType():string
    {
        return $this->serverSetting['server_type']??'unknown';
    }
}