<?php

namespace Elasticsearch;

/**
 * ElasticSearch接口
 * Interface Elastic
 * @package Elasticsearch
 */
interface Elastic
{
    public function save($body);

    public function delete($id);

    public function find($params);
}
