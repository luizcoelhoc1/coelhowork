<?php

namespace App\model;

use Coelho\Model;

class TestModel extends Model {

    public function __construct(?\PDO $connection) {
        parent::__construct($connection);
    }

    public function test() {
        $this->queryBuilder->from("test");
        $this->queryBuilder->where("name", "=", "test");
        return $this->queryBuilder->run();
    }

}