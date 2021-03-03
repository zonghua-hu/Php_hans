<?php

namespace Elasticsearch;

use Qcloud\Cos\Md5Listener;

/**
 * 商品es库
 * Class ShopElasticSearch
 * @package Elasticsearch
 */
class ShopElasticSearch extends BaseElasticSearch
{
    private $config;
    private $defaultType;
    /**
     * 有效
     * @var int
     */
    private $valid = 0;
    /**
     * 无效
     * @var int
     */
    private $invalid = 1;

    public function __construct()
    {
        $this->config = \Phalcon\Di::getDefault()->get('config');
        $esIndex = $this->config['elastic']['data']['shop']['index'];
        $this->defaultType = $this->config['elastic']['data']['shop']['type'];
        parent::__construct($esIndex, $this->defaultType);
    }

    /**
     * @Notes:商品库保存方法重写
     * @param $data
     * @param string $type
     * @return mixed
     * @User: Hans
     * @Date: 2021/3/3
     * @Time: 上午9:30
     */
    public function save($data, $type = '')
    {
        $this->params['id'] = $data['id'];
        return parent::save($data, $this->adapterType($type));
    }

    /**
     * @Notes:查询一条记录根据id
     * @param $id
     * @return mixed
     * @User: Hans
     * @Date: 2021/3/3
     * @Time: 上午9:09
     */
    public function findById($id, $type = '')
    {
        $res = parent::findById($id, $this->adapterType($type));
        if ($res) {
            $res = $res['hits'];
            if (!empty($res) && $res['total']['value'] == 1) {
                $res = current($res['hits']);
                return $res['_source'];
            }
        }
        return [];
    }

    /**
     * @Notes:多条件查询
     * @param $params
     * @param string $type
     * @return array
     * @User: Hans
     * @Date: 2021/3/3
     * @Time: 上午10:43
     */
    public function queryByWhere($params, $type = '')
    {
        $must = $mustNot = $should = [];
        if (isset($params['merchant_id']) && !empty($params['merchant_id'])) {
            if (is_array($params['merchant_id'])) {
                // in查询
                $must[] = ['terms' => ['merchant_id' => $params['merchant_id']]];
            } else {
                // =查询
                $must[] = ['match' => ['merchant_id' => $params['merchant_id']]];
            }
        }

        if (isset($params['merchant_type']) && !empty($params['merchant_type'])) {
            array_push($must, ['match' => ['merchant_type' => $params['merchant_type']]]);
        }

        //if (isset($params['keyword']) && !empty($params['keyword'])) {
            //todo 还未实现模糊查询
//            $this->params['body']['query']['match'] = [
//                'name' => $params['keyword']
//            ];
//            array_push($must, ['match' =>['name' => $params['keyword']]]);
        //}

        if (isset($params['status']) && !empty($params['status'])) {
            array_push($must, ['match' => ['status' => $params['status']]]);
        }

        if (isset($params['cate']) && !empty($params['cate'])) {
            array_push($must, ['match' => ['cate' => $params['cate']]]);
        }

        //爆品查询
        if (isset($params['is_hot']) && is_numeric($params['is_hot'])) {
            array_push($must, ['match' => ['is_hot' => $params['is_hot']]]);
        }
        //商品品类查询
        if (isset($params['category_id'])) {
            if (is_array($params['category_id'])) {
                $must[] = ['terms' => ['category_id' => $params['category_id']]];
            } elseif (is_numeric($params['category_id'])) {
                array_push($must, ['match' => ['category_id' => $params['category_id']]]);
            }
        }
        //获取有效数据
        array_push($must, ['match' => ['is_delete' => $this->valid]]);
        //默认分页为10
        if (isset($params['page']) && !empty($params['page'])) {
            $this->params['from'] = ($params['page']-1)*$params['page_size'];
            $this->params['size'] = $params['page_size'] ?? 10;
        }
        //组装查询语句
        if (!empty($must)) {
            $this->params['body']['query']['bool']['must'] = $must;
        }
        if (!empty($mustNot)) {
            $this->params['body']['query']['bool']['must_not'] = $mustNot;
        }
        if (!empty($should)) {
            $this->params['body']['query']['bool']['should'] = $should;
        }
        $return = [];
        $res = parent::find($this->params, $type);
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
     * @Notes:获取es相应的文档类型
     * @param $type
     * @return mixed
     * @User: Hans
     * @Date: 2021/3/3
     * @Time: 上午9:49
     */
    private function adapterType($type)
    {
        if ($type) {
            return $this->config['elastic']['data']['shop'][$type];
        }
        return '';
    }

}
