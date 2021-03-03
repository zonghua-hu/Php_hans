<?php

namespace Elasticsearch;

/**
 * ElasticSearch接口
 * Interface Elastic
 * @package Elasticsearch
 */
interface  Elastic
{
    public function save($body, $type = '');

    public function delete($id, $type = '');

    public function find($params, $type = '');

}
