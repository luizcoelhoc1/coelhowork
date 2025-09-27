<?php

namespace Coelho;

use Coelho\database\QueryBuilder;
use Coelho\database\Transaction;

class Model {
    public $builder;

    function __construct(?\PDO $connection) {
        $this->queryBuilder = new QueryBuilder($connection);
    }
}