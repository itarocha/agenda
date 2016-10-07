<?php

namespace App\Util;

use Validator;

class ModelValidator
{
    private $errors;

    public function validate($data, $rules)
    {
        $v = Validator::make($data, $rules);
        if ($v->fails())
        {
            $this->errors = $v->errors()->all();
            return false;
        }
        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

}
