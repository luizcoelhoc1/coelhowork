<?php

namespace App\middleware\guard;

use Coelho\Guard;

class GuardHome extends Guard {

    public function can(): bool {
        return true;
    }

}
