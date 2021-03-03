<?php
/**
 * @desc 服务配置文件
 *
 * @author --
 * @copyright 2014-2018
 */

use Phalcon\Di,
    Phalcon\Di\FactoryDefault,
    Phalcon\Mvc\View,
    Phalcon\Mvc\Url as UrlResolver,
    Phalcon\Mvc\Router\Annotations as RouterAnnotations,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
    Phalcon\Mvc\Model\Manager as ModelManager,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine,
    ShopIZ\Mvc\View\Engine\Simple as SimpleEngine,
    Phalcon\Mvc\Model\Metadata\Memcache as MemcacheMetaDataAdapter,
    Phalcon\Mvc\Model\Metadata\Libmemcached as MemcachedMetaDataAdapter,
    Phalcon\Mvc\Model\Metadata\Memory as MemoryMetaDataAdapter,
    Phalcon\Mvc\Model\Metadata\Apc as ApcMetaDataAdapter,
    Phalcon\Session\Adapter\Files as SessionAdapter,
    Phalcon\Flash\Session as FlashSession,
    Phalcon\Cache\Frontend\Data as FrontendCache,
    Phalcon\Cache\Backend\Memcache,
    Phalcon\Cache\Backend\Libmemcached as Libmemcached,
    WPLib\Caching\Redis as Redis,
    WPLib\MessageQueue\Factory as MQFactory,
    WPLib\Logger\Multiple as MultipleStreamLogger,
    WPLib\Logger\File as FileLoggerAdapter,
    WPLib\Logger\Queue as QueueLoggerAdapter,
    WPLib\Logger\Custom as CustomLoggerAdapter,
    WPLib\Mvc\Model\MetaData\Strategy\Introspection,
    Elasticsearch\ClientBuilder;

/**
 * 调试设置
 */
if ($config->application->showErrors) {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    if (DEBUG_LEVEL & 8 == 8 && (!IN_CLI)) {
        new Whoops\Provider\Phalcon\WhoopsServiceProvider($di);
    }
}

/**
 * 服务依赖
 */
if (($di instanceof Phalcon\DI\FactoryDefault) === false) {
    if (IN_CLI) {
        $di = new Phalcon\DI\FactoryDefault\CLI();
    } else {
        $di = new Phalcon\DI\FactoryDefault();
    }
}

$di->set('profiler', function(){
    return new \Phalcon\Db\Profiler();
}, true);

/**
 * 设置日志服务
 */
$di->set('logger', function() use($config) {
    $logger_dir = dirname($config['logger']['filename']);
    if (!file_exists($logger_dir)) {
        @mkdir($logger_dir, 0755, true);
    }

    $multi_logger = new MultipleStreamLogger();
    if (in_array(ENVIRON, ['develop', 'test', 'preview','production'])) {
        $logger = new FileLoggerAdapter($config['logger']['filename']);
    } else {
        $logger = new CustomLoggerAdapter($config['logger']['filename']);
    }
    $multi_logger->push($logger);
    $multi_logger->setLogLevel(Phalcon\Logger::DEBUG);

    return $multi_logger;
}, true);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
}, true);


if (!IN_CLI) {
    /**
     * 设置视图
     */
    $di->set('view', function() use ($config) {

        $view = new View();

        // 设置模版目录
        $view->setBasePath($config->view->baseDir);
        $view->setViewsDir($config->view->themeDir);

        // 设置布局
        $view->setMainView($config->view->mainView);
        $view->setLayoutsDir($config->view->layoutDir);
        // $view->setLayout($config->view->layoutFile);

        // 注册模版解析引擎
        $view->registerEngines(array(
                                   // '.html' => 'Phalcon\Mvc\View\Engine\Php',
                                   '.php' => function($view, $di) use ($config) {

                                       $volt = new VoltEngine($view, $di);

                                       if (!file_exists($config->view->compiledDir)) {
                                           mkdir($config->view->compiledDir, 0755, true);
                                       }

                                       $volt->setOptions(array(
                                                             'compileAlways'     => $config->view->compileAlways,
                                                             'compiledPath'      => $config->view->compiledDir,
                                                             'compiledSeparator' => '_',
                                                         ));

                                       return $volt;
                                   },
                               ));

        return $view;
    }, true);

    /**
     * 设置flash服务
     */
    $di->set('flash', function(){
        $flash = new FlashSession([
                                      'error'   => 'alert alert-danger',
                                      'success' => 'alert alert-success',
                                      'notice'  => 'alert alert-info',
                                      'warning' => 'alert alert-warning',
                                  ]);

        return $flash;
    }, true);

    /**
     * 设置Session服务
     */
    $di->set('session', function() {
        $session = new SessionAdapter();
        $session->start();

        return $session;
    }, true);

    /**
     * 设置路由服务
     */
    $di->set('router', function(){

        $router = new \Phalcon\Mvc\Router();

        $router->setDefaultController('default');

        include 'router.php';

        return $router;
    }, true);
}

/**
 * 设置缓存服务
 */
$di->setShared('cache', function() use($config) {
    $frontendcache = new FrontendCache($config['cache']['frontend']->toArray());

    $cache_config = $config['cache']['backend']->toArray();

    if (isset($cache_config['prefix'])) {
        $cache_config['client'][Memcached::OPT_PREFIX_KEY] = $cache_config['prefix'];
        unset($cache_config['prefix']);
    }
    $cache_config['client'][Memcached::OPT_PREFIX_KEY] = $cache_config['prefix'];

    $cache_config['client'] += [
        // 超时设置
        Memcached::OPT_CONNECT_TIMEOUT => 200,
        Memcached::OPT_RETRY_TIMEOUT => 200,
        Memcached::OPT_POLL_TIMEOUT => 200,

        //
        Memcached::OPT_DISTRIBUTION => Memcached::DISTRIBUTION_CONSISTENT,
        Memcached::OPT_BINARY_PROTOCOL => true,
        Memcached::OPT_LIBKETAMA_COMPATIBLE => true,

        //自动failover配置
        Memcached::OPT_SERVER_FAILURE_LIMIT => 1,
        Memcached::OPT_RETRY_TIMEOUT => 30,
        Memcached::OPT_AUTO_EJECT_HOSTS => true,
    ];

    $cache = new Libmemcached($frontendcache, $cache_config);

    return $cache;
});

/**
 * 注入es数据库
 */
$di->set('es', function ()use($config){
    return ClientBuilder::create()->setHosts([$config['elastic']['host']])->build();
}, true);

/**
 * 设置Redis服务 - 队列
 */
$di->setShared('redis', function() use($config) {
    $frontendcache = new FrontendCache($config['cache']['frontend']->toArray());

    $config = $config['cache']['redis'];

    $redis_config = $config['servers'][mt_rand(0, count($config['servers']) - 1)];

    if (!isset($redis_config['auth']) && isset($config['auth'])) {
        $redis_config['auth'] = $config['auth'];
    }
    if (!isset($redis_config['prefix']) && isset($config['prefix'])) {
        $redis_config['prefix'] = $config['prefix'];
    }

    if(!isset($redis_config['statsKey']) && isset($config['prefix'])){
        $redis_config['statsKey'] = $config['statsKey'];
    }

    $redis = new Redis($frontendcache, $redis_config->toArray());

    return $redis;
});

/**
 * 设置Beanstalk服务 - 队列
 */
$di->setShared('beanstalk', function() use($config) {

    $beanstalk = new Beanstalk();

    $beanstalk_config = $config['beanstalk'];

    foreach ($beanstalk_config['servers'] as $k => $v) {
        $beanstalk->addServer($v->host, $v->port, $v->weight);
    }

    return $beanstalk;
});

$di->set('collectionManager', function(){
    return new \Phalcon\Mvc\Collection\Manager();
});

/**
 * 设置MongoDB
 */
$di->set('mongo', function () use ($config, $di) {
    $mongo_config = $config['mongo'];
    $servers = $mongo_config['servers'];
    $server = "mongodb://";
    foreach ($servers as $k => $v) {
        $server .= "{$v['host']}:{$v['port']},";
    }
    $server = rtrim($server, ',');
    $options = [
        'connect' => true,
    ];
    if (isset($mongo_config['username']) && isset($mongo_config['password'])) {
        $options['username'] = $mongo_config['username'];
        $options['password'] = $mongo_config['password'];
    }
    try {
        $mongo = new MongoClient($server, $options);
        return $mongo->selectDB($mongo_config['dbname']);
    } catch (Exception $e) {
        //
    }

    return $mongo;
}, true);

/**
 * 消息队列服务
 */
$di->set('mq', function () use ($config, $di) {
    if (isset($config['mq'])) {

        $mq_config = $config['mq']->toArray();

        $mq = MQFactory::inst($mq_config);

    } else {
        Logger::error('请配置消息队列！');
        throw new Exception('请配置消息队列！');
    }

    return $mq;
}, true);

/**
 * 事件管理器
 */
$eventsManager = new \Phalcon\Events\Manager();

/**
 * 设置数据库服务
 */
$di->set('db', function() use ($config, $di) {
    // 事件管理器
    $eventsManager = $di->getShared('eventsManager');

    // 创建一个数据库侦听
    $dbListener    = new \WPLib\Events\DbListener();

    //监听所有的db事件
    $eventsManager->attach('db', $dbListener);
    $connection = new DbAdapter(array(
                                    'host' => $config->database->host,
                                    'port' => $config->database->port,
                                    'username' => $config->database->username,
                                    'password' => $config->database->password,
                                    'dbname' => $config->database->dbname,
                                    'charset' => $config->database->charset,
                                ));
    $connection->setEventsManager($eventsManager);

    return $connection;
}, true);

$di->set('db_business', function () use ($config, $di) {
    // 事件管理器
    $eventsManager = $di->getShared('eventsManager');
    // 创建一个数据库侦听
    $dbListener    = new \WPLib\Events\DbListener();
    //监听所有的db事件
    $eventsManager->attach('db', $dbListener);
    $connection = new DbAdapter($config->db_business->toArray());
    $connection->setEventsManager($eventsManager);
    return $connection;
});

//$di->set('db', function () use ($config) {
//    return new Phalcon\Db\Adapter\Pdo\Postgresql(array(
//        'host'     => $config->database->host,
//        'username' => $config->database->username,
//        'password' => $config->database->password,
//        'dbname'   => $config->database->dbname,
//    ));
//});

/**
 * 设置models管理器
 */
$di->set('modelsManager', function() {
    return new ModelManager();
}, true);

/**
 * 设置models元数据服务
 */
$di->set('modelsMetadata', function() use($config) {
    if (extension_loaded('apc')) {
        $apc_config = [
            'prefix' => (string)$config['cache']['metadata']['prefix'],
            'lifetime' => (int)$config['cache']['metadata']['lifetime'] ?: 1800,
        ];
        $metadata = new ApcMetaDataAdapter($apc_config);
    } else {
        $metadata = new MemoryMetaDataAdapter();
    }
    $metadata->setStrategy(
        new Introspection()
    );
    return $metadata;
}, true);

WPLib\WPApi::setAppInfo($config->application->app_id, $config->application->secret);

/**
 * 注入config配置
 */
$di->set('config', $config);


return $di;
