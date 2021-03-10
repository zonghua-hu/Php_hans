<?php


namespace Elasticsearch;


class CategorySearch extends BaseElasticSearch
{
    private $index_search = 'category';

    public function __construct()
    {
        parent::__construct($this->index_search);
    }

}