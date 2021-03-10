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
    protected $config;


    public function __construct($index)
    {
        $this->di = \Phalcon\Di::getDefault();
        $this->client =$this->di->get('es');
        $this->config = $this->di->get('config');

        $this->index = $this->config['elastic']['data'][$index];
        $this->params = ['index' => $this->index];

        if (!$this->checkIndex()) {
            $this->params['body'] = $this->config['elastic']['body'];
            $this->client->indices()->create($this->params);
            unset($this->params['body']);
        }

        $this->params['type'] = '_doc';
    }

    /**
     * @Notes:索引检查是否存在
     * @return bool
     * @User: Hans
     * @Date: 2021/3/9
     * @Time: 下午3:48
     */
    private function checkIndex()
    {
        $params = [
            'index' => [$this->index],
            'client' =>['ignore' => [404]],
        ];
        $response = $this->client->indices()->getSettings($params);
        if (isset($response['status']) && $response['status'] == 404) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @Notes:save方法实例化，生成一个文档
     * @param $data
     * @return mixed
     * @User: Hans
     * @Date: 2021/3/2
     * @Time: 下午5:25
     */
    public function save($data)
    {
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
    public function delete($id)
    {
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
    public function find($params)
    {
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
    public function findById($id)
    {
        $this->params['body']['query']['match'] = [
            'id' => $id
        ];
        return self::find($this->params);
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
     * @Notes:根据条件查询elastic中数据
     * @param $params
     * @return array|mixed
     * @User: Hans
     * @Date: 2021/3/10
     * @Time: 上午10:16
     */
    protected function findByQuery($params)
    {
        if (isset($params['must']) && !empty($params['must'])) {
            $this->params['body']['query']['bool']['must'] = $params['must'];
        }
        if (isset($params['must_not']) && !empty($params['must_not'])) {
            $this->params['body']['query']['bool']['must_not'] = $params['must_not'];
        }
        if (isset($params['should']) && !empty($params['should'])) {
            $this->params['body']['query']['bool']['should'] = $params['should'];
        }
        $return = [];
        $res = self::find($this->params);
        if ($res) {
            $res = $res['hits'];
            if (!empty($res) && $res['total']['value'] > 0) {
                $return = array_column($res['hits'], '_source');
            }
        }
        if (count($return) == 1) {
            return current($return);
        } elseif (count($return) == 0) {
            return [];
        } else {
            return $return;
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