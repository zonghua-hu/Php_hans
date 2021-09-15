<?php

namespace codeGenerator;

class CodeGenerator
{
    private $service;
    private $lowerService;
    private $classEnd = PHP_EOL . '}' . PHP_EOL;
    private static $actionMap = ['createProduct' => '创建产品'];

    public function genAllCode($service)
    {
        $this->service = $service;
        $this->lowerService = strtolower($service);
        $this->dictionary();
        $this->saveActionMap();
        $this->saveRequestBuilder();

    }
    private function saveRequestBuilder()
    {
        file_put_contents($this->getDictionary() . 'RequestBuilder.php',  $this->requestBuilder());
    }
    private function requestBuilderHeader()
    {
        $codeTemplate = <<<CODE
<?php

namespace app\module\warehouseService\service\provider\[SERVICE_NAME];

use app\module\warehouseService\service\base\RequestBuilderBase;
use app\module\warehouseService\service\base\StdParam;

class RequestBuilder extends RequestBuilderBase
{

CODE;
        return str_replace('[SERVICE_NAME]', strtolower($this->service), $codeTemplate);
    }

    private function getActionMap($service = '')
    {
        $service  = $service ?: $this->lowerService;
        $classStr = sprintf('%s\provider\%s\ActionMap', __NAMESPACE__, $service);
        return new $classStr();
    }

    private function requestBuilder()
    {
        $code = $this->requestBuilderHeader();

        $from = $this->getActionMap()->getAction();

        $codeTemplate = <<<CODE
    /**
     * [ACTION_TITLE]
     * @todo 修改模板代码 
     * @param array \$origin
     * @return \app\module\warehouseService\service\base\StdParam
     */
    public function [FUNCTION_NAME](\$origin = [])
    {
        \$param = [
            'pageSize' => \$origin['page_size'] ?? '100',//承运人类型: CUSTOMER-自发物流
        ];
        \$ext = [
            'pageSize' => \$origin['page_size'] ?? '100',//承运人类型: CUSTOMER-自发物流
        ];
        \$stdParam = new StdParam();
        \$stdParam->setData(\$param);
        \$stdParam->batchSetExt(\$ext);
        return \$stdParam;
    }
CODE;

        foreach ($from as $localAction => $remoteAction) {
            $code .= str_replace(['[FUNCTION_NAME]', '[ACTION_TITLE]'], [$localAction, self::$actionMap[$localAction]], $codeTemplate) . PHP_EOL;
        }
        $code .= $this->classEnd;
        return $code;
    }

    private function dictionary()
    {
        if (file_exists($this->getDictionary())) {
            echo '目录:' . $this->getDictionary() . '已存在, 为避免污染现有代码, 请删除整个目录再继续生成';
            exit;
        }
        return mkdir($this->getDictionary());
    }

    /**
     * 获取目录
     * @return string
     */
    private function getDictionary()
    {
        return sprintf('%s/provider/%s/', __DIR__, $this->lowerService);
    }
    private function saveActionMap()
    {
        file_put_contents($this->getDictionary() . 'ActionMap.php', $this->actionMap());
    }
    private function actionMap()
    {
        $code = $this->actionMapHeader();
        $code .= '    protected $map = ' . var_export(self::$actionMap, true) . ';';
        $code .= $this->classEnd;
        return $code;
    }

    private function actionMapHeader()
    {
        $codeTemplate = <<<CODE
<?php

namespace app\module\warehouseService\service\provider\[SERVICE_NAME];

use app\module\warehouseService\service\base\ActionMapBase;

class ActionMap extends ActionMapBase
{
   //@todo 自己格式化代码
CODE;
        return str_replace('[SERVICE_NAME]', $this->lowerService, $codeTemplate);
    }


}