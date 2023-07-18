<?php

namespace Tests\Unit;

use Core\Teste;
use PHPUnit\Framework\TestCase;

class TesteUnitTest extends TestCase
{
    public function testCallMethodFoo()
    {
        $teste = new Teste();

        $this->assertEquals('foo', $teste->foo());
    }
}
