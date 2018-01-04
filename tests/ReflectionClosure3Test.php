<?php
/* ===========================================================================
 * Copyright 2014-2017 The Opis Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Closure\Test;

use Closure;
use Opis\Closure\ReflectionClosure;

// Fake
use Foo\{Bar, Baz as Qux};
use function Foo\f1;
use function Bar\{b1, b2 as b3};

class ReflectionClosure3Test extends \PHPUnit_Framework_TestCase
{
    protected function c(Closure $closure)
    {
        $r = new ReflectionClosure($closure);
        return $r->getCode();
    }

    public function testResolveArguments()
    {
        $f1 = function (?Bar $p){};
        $e1 = 'function (?\Foo\Bar $p){}';

        $f2 = function (?Bar\Test $p){};
        $e2 = 'function (?\Foo\Bar\Test $p){}';

        $f3 = function (?Qux $p){};
        $e3 = 'function (?\Foo\Baz $p){}';

        $f4 = function (?Qux\Test $p){};
        $e4 = 'function (?\Foo\Baz\Test $p){}';

        $f5 = function (?array $p, ?string $x){};
        $e5 = 'function (?array $p, ?string $x){}';


        $this->assertEquals($e1, $this->c($f1));
        $this->assertEquals($e2, $this->c($f2));
        $this->assertEquals($e3, $this->c($f3));
        $this->assertEquals($e4, $this->c($f4));
        $this->assertEquals($e5, $this->c($f5));
    }

    public function testResolveReturnType()
    {
        $f1 = function (): ?Bar{};
        $e1 = 'function (): ?\Foo\Bar{}';

        $f2 = function (): ?Bar\Test{};
        $e2 = 'function (): ?\Foo\Bar\Test{}';

        $f3 = function (): ?Qux{};
        $e3 = 'function (): ?\Foo\Baz{}';

        $f4 = function (): ?Qux\Test{};
        $e4 = 'function (): ?\Foo\Baz\Test{}';

        $f5 = function (): ?\Foo{};
        $e5 = 'function (): ?\Foo{}';

        $f6 = function (): ?Foo{};
        $e6 = 'function (): ?\\' . __NAMESPACE__. '\Foo{}';

        $f7 = function (): ?array{};
        $e7 = 'function (): ?array{}';

        $f8 = function (): ?string{};
        $e8 = 'function (): ?string{}';

        $f9 = function (): void{};
        $e9 = 'function (): void{}';

        $this->assertEquals($e1, $this->c($f1));
        $this->assertEquals($e2, $this->c($f2));
        $this->assertEquals($e3, $this->c($f3));
        $this->assertEquals($e4, $this->c($f4));
        $this->assertEquals($e5, $this->c($f5));
        $this->assertEquals($e6, $this->c($f6));
        $this->assertEquals($e7, $this->c($f7));
        $this->assertEquals($e8, $this->c($f8));
        $this->assertEquals($e9, $this->c($f9));
    }
}