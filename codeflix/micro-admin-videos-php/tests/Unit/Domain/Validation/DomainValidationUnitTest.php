<?php

namespace Tests\Unit\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;
use Throwable;

class DomainValidationUnitTest extends TestCase
{
    public function testNotNull()
    {
        try{
            $value = '';
            DomainValidation::notNull($value);

            $this->assertTrue(false);
        } catch (Throwable $throwable) {
            $this->assertInstanceOf(EntityValidationException::class, $throwable);
        }
    }

    public function testNotNullCustomExceptionMessage()
    {
        try{
            $expectedMessage = "Custom message";
            $value = '';
            DomainValidation::notNull($value, $expectedMessage);

            $this->assertTrue(false);
        } catch (Throwable $throwable) {
            $this->assertInstanceOf(EntityValidationException::class, $throwable, $expectedMessage);
        }
    }

    public function testStrMaxLength()
    {
        try{
            $expectedMessage = "Value should not be greater than 5";
            $value = 'String bigger than 5 caracters';
            DomainValidation::strMaxLength($value, 5, $expectedMessage);

            $this->assertTrue(false);
        } catch (Throwable $throwable) {
            $this->assertInstanceOf(EntityValidationException::class, $throwable, $expectedMessage);
        }
    }

    public function testStrMinLength()
    {
        try{
            $expectedMessage = "Value should not be less than 10";
            $value = 'Teste';
            DomainValidation::strMinLength($value, 10, $expectedMessage);

            $this->assertTrue(false);
        } catch (Throwable $throwable) {
            $this->assertInstanceOf(EntityValidationException::class, $throwable, $expectedMessage);
        }
    }

    public function testStrNullOrMaxLength()
    {
        try{
            $expectedMessage = "Value should not be greater than 10";
            $value = "String bigger than 10 caracters";
            DomainValidation::StrNullOrMaxLength($value, 10, $expectedMessage);

            $this->assertTrue(false);
        } catch (Throwable $throwable) {
            $this->assertInstanceOf(EntityValidationException::class, $throwable, $expectedMessage);
        }
    }
}
