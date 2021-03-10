<?php


namespace Elasticsearch;


class GoodsAttrSearch extends BaseElasticSearch
{
    private $index_search = 'attr';

    public function __construct()
    {
        parent::__construct($this->index_search);
    }


}