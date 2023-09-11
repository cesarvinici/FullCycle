<?php

namespace Core\Domain\Validation;


use Core\Domain\Exception\EntityValidationException;

class DomainValidation
{

    public static function notNull(string $value, string $exceptionMessage = null): void
    {
        if (empty($value)) {
            throw new EntityValidationException($exceptionMessage ?? "Value should not be null or empty");
        }
    }

    public static function strMaxLength(string $value, int $maxLength = 255, string $exceptionMessage = null): void
    {
        if (strlen($value) > $maxLength) {
            throw new EntityValidationException($exceptionMessage ?? "Value should not be greater than $maxLength");
        }
    }

    public static function strMinLength(string $value, int $minLength = 2, string $exceptionMessage = null): void
    {
        if (strlen($value) < $minLength) {
            throw new EntityValidationException($exceptionMessage ?? "Value should not be less than $minLength");
        }
    }

    public static function StrNullOrMaxLength(string $value, int $maxLength = 255, string $exceptionMessage = null): void
    {
        if (! empty($value) && strlen($value) > $maxLength) {
            throw new EntityValidationException($exceptionMessage ?? "Value should not be greater than $maxLength");
        }
    }


}
