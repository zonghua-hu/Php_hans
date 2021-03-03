<?php

namespace Elasticsearch;

/**
 * es基类库
 * Class BaseElasticSearch
 * @package Elasticsearch
 */
class BaseElasticSearch implements Elastic
{
    protected $client;
    protected $index;
    protected $type;
    protected $id;
    protected $di;
    protected $params = [];

    public function __construct($index, $type)
    {
        $this->di = \Phalcon\Di::getDefault();
        $this->client =$this->di->get('es');
        $this->index = $index;
        $this->type = $type;
        $this->params = [
            'index' => $index,
            'type' => $type
        ];
    }

    /**
     * @Notes:save方法实例化，生成一个文档
     * @param $data
     * @return mixed
     * @User: Hans
     * @Date: 2021/3/2
     * @Time: 下午5:25
     */
    public function save($data, $type = '')
    {
        $this->alterType($type);
        $body = $this->formatBody($data);
        $this->params['body'] = $body;
        return  $this->client->index($this->params);
    }

    /**
     * @Notes:删除一条记录
     * @param $id
     * @return bool
     * @User: Hans
     * @Date: 2021/3/2
     * @Time: 下午5:33
     */
    public function delete($id, $type = '')
    {
        $this->alterType($type);
        if (!$id) {
            return false;
        }
        $this->params['id'] = $id;
        if ($this->checkIndexExists()) {
            return $this->client->delete($this->params);
        }
        return false;
    }

    /**
     * @Notes:查询
     * @param $params
     * @return mixed
     * @User: Hans
     * @Date: 2021/3/3
     * @Time: 上午9:00
     */
    public function find($params, $type = '')
    {
        $this->alterType($type);
        return $this->client->search($this->params);
    }

    /**
     * @Notes:根据id查询一条记录
     * @param $id
     * @return mixed
     * @User: Hans
     * @Date: 2021/3/3
     * @Time: 上午9:09
     */
    public function findById($id, $type = '')
    {
        $this->params['body']['query']['match'] = [
            'id' => $id
        ];
        return self::find($this->params, $type);
    }

    /**
     * @Notes:检查是否存在index
     * @return mixed
     * @User: Hans
     * @Date: 2021/3/2
     * @Time: 下午5:28
     */
    protected function checkIndexExists()
    {
        return $this->client->exists($this->params);
    }

    /**
     * @Notes:调整文档type
     * @param $type
     * @User: Hans
     * @Date: 2021/3/3
     * @Time: 上午9:44
     */
    protected function alterType($type)
    {
        if (!empty($type)) {
            $this->type = $type;
            $this->params['type'] = $type;
        }
    }

    /**
     * @Notes:格式化数据体
     * @param $data
     * @return false|string
     * @User: Hans
     * @Date: 2021/3/2
     * @Time: 下午4:53
     */
    protected function formatBody($data)
    {
        $return = '';
        if (!empty($data)) {
            if (is_array($data)) {
                $return = json_encode($data);
            } elseif (is_object($data)) {
                $return = self::objectToArray($data);
            }
        }
        return $return;
    }

    /**
     * @Notes:对象转数组
     * @param $array
     * @return array
     * @User: Hans
     * @Date: 2021/3/2
     * @Time: 下午4:55
     */
    protected function objectToArray($array)
    {
        if(is_object($array)) {
            $array = (array)$array;
        }
        if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = self::objectToArray($value);
            }
        }
        return $array;
    }

}