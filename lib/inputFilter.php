<?php

namespace dash\lib;

trait InputFilter
{
    public function filterInt($input): ?int
    {
        $filtered = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        $filtered = is_numeric($filtered) ? (int)$filtered : null;
        return ($filtered !== null && $filtered >= 0) ? $filtered : null;
    }

    public function filterFloat($input): ?float
    {
        $filtered = filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $filtered = is_numeric($filtered) ? (float)$filtered : null;
        return ($filtered !== null && $filtered > 0) ? $filtered : null;
    }

    public function filterString(string $input, int $minLength = 1, int $maxLength = 255): ?string
    {
        $input = trim($input);
        $filtered = htmlentities(strip_tags($input), ENT_QUOTES, 'UTF-8');
        $length = strlen($filtered);
        if ($length < $minLength || $length > $maxLength) {
            return null;
        }
        return $filtered;
    }

    public function filterEmail(string $input): ?string
    {
        $input = trim($input);
        $filtered = filter_var($input, FILTER_SANITIZE_EMAIL);
        return filter_var($filtered, FILTER_VALIDATE_EMAIL) ? $filtered : null;
    }

    public function filterUrl(string $input): ?string
    {
        $input = trim($input);
        $filtered = filter_var($input, FILTER_SANITIZE_URL);
        return filter_var($filtered, FILTER_VALIDATE_URL) ? $filtered : null;
    }

    public function filterBoolean($input): bool
    {
        return filter_var($input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null;
    }

    public function filterIntArray(array $inputArray): ?array
    {
        $filteredArray = array_filter(
            array_map([$this, 'filterInt'], $inputArray),
            function ($value) {
                return $value !== null;
            }
        );
        return count($filteredArray) === count($inputArray) ? $filteredArray : null;
    }

    public function filterFloatArray(array $inputArray): ?array
    {
        $filteredArray = array_filter(
            array_map([$this, 'filterFloat'], $inputArray),
            function ($value) {
                return $value !== null;
            }
        );
        return count($filteredArray) === count($inputArray) ? $filteredArray : null;
    }
}
