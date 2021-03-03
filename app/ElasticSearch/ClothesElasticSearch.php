<?php


namespace Elasticsearch;

/**
 * 衣服es库
 * Class ClothesElasticSearch
 * @package Elasticsearch
 */
class ClothesElasticSearch extends BaseElasticSearch
{
    public function __construct()
    {
        $config = \Phalcon\Di::getDefault()->get('config');
        $esIndex = $config['elastic']['data']['clothes']['index'];
        $esType = $config['elastic']['data']['clothes']['type'];
        parent::__construct($esIndex, $esType);
    }
}
