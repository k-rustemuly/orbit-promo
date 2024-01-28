<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class VoucherFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function phoneNumber($phone_number)
    {
        return $this->related('user', 'phone_number', '=', $phone_number);
    }
}
