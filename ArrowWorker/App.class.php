<?php
/**
 * User: Arrow
 * Date: 2016/8/1
 * Time: 19:47
 */

namespace ArrowWorker;

class App
{
    //控制器
    private static $controller;
    //方法
    private static $method;
    //控制器和方法映射表
    private static $userCam;
    //app实例
    private static $appInstance;
    //应用命名空间
    private static $appCtlNamespace;

    //单例模式自启动构造函数
    private function __construct($appConfig)
    {
        if(!self::$userCam)
        {
            self::$userCam  = require APP_PATH.DIRECTORY_SEPARATOR.APP_CONFIG_FOLDER.DIRECTORY_SEPARATOR.APP_ALIAS.'.php';
        }
        self::$appCtlNamespace = '\\'.$appConfig['app'].'\\'.$appConfig['controller'].'\\';
    }

    //初始化app
    static function initApp($userCam)
    {
        if (!self::$appInstance)
        {
            self::$appInstance = new self($userCam);
        }
        return self::$appInstance;
    }

    //运行控制器
    public function runApp()
    {
        if(APP_TYPE=='cli')
        {
            $this->CliApp();
        }
        else
        {
            $this->WebApp();
        }
        $this -> isDefaultCm();

        $controller = self::$appCtlNamespace.self::$controller;
        $method     = self::$method;
        $ctlObject  = new $controller;
        $ctlObject -> $method();
    }

    //web应用
    private function WebApp()
    {
        @self::$controller = $_REQUEST['c'];
        @self::$method     = $_REQUEST['m'];

    }

    //常驻服务
    private function CliApp()
    {
        $inputs = getopt('c:m:');
        @self::$controller = $inputs['c'];
        @self::$method     = $inputs['m'];
    }

    //判断是否要应用默认控制器和方法
    private function isDefaultCm()
    {
        self::$controller = is_null(self::$controller) ? DEFAULT_CONTROLLER : self::$controller;
        self::$method     = is_null(self::$method) ? DEFAULT_METHOD : self::$method;
    }

}
