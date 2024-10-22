<?php

namespace dash\lib;

trait InputFilter
{
    public function filterInt($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    public function filterFloat($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public function filterString($input)
    {
        return htmlentities(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }

    public function filterEmail($input)
    {
        return filter_var($input, FILTER_SANITIZE_EMAIL);
    }

    public function filterUrl($input)
    {
        return filter_var($input, FILTER_SANITIZE_URL);
    }

    public function filterBoolean($input)
    {
        return filter_var($input, FILTER_VALIDATE_BOOLEAN);
    }
}
