<?php

namespace App\Tests\Model\Response;

use App\Model\Response\ProblemDetailsDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProblemDetailsDto::class)]
final class ProblemDetailsDtoTest extends TestCase
{
    public function testSimpleProblem(): void
    {
        $expected = <<<ETX
{"title":"Test Error","type":"about:blank"}
ETX;
        $actual = json_encode(new ProblemDetailsDto('Test Error')) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testProblemWithDetail(): void
    {
        $problem = new ProblemDetailsDto('Test Error');
        $problem->detail = 'Here is a very detailed problem';
        $expected = <<<ETX
{"title":"Test Error","type":"about:blank","detail":"Here is a very detailed problem"}
ETX;
        $actual = json_encode($problem) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testProblemWithDetailAndInstance(): void
    {
        $problem = new ProblemDetailsDto('Test Error');
        $problem->detail = 'Here is a very detailed problem';
        $problem->instance = 'http://www.readthedocs.io/error/12345';
        $expected = <<<ETX
{"title":"Test Error","type":"about:blank","detail":"Here is a very detailed problem",
"instance":"http://www.readthedocs.io/error/12345"}
ETX;
        $actual = json_encode($problem) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testProblemWithDetailAndStatus(): void
    {
        $problem = new ProblemDetailsDto('Test Error');
        $problem->detail = 'Here is a very detailed problem';
        $problem->status = 400;
        $expected = <<<ETX
{"title":"Test Error","type":"about:blank","detail":"Here is a very detailed problem","status":400}
ETX;
        $actual = json_encode($problem) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testProblemWithDetailInstanceAndStatus(): void
    {
        $problem = new ProblemDetailsDto('Test Error');
        $problem->detail = 'Here is a very detailed problem';
        $problem->instance = 'http://www.readthedocs.io/error/12345';
        $problem->status = 400;
        $expected = <<<ETX
{"title":"Test Error","type":"about:blank","detail":"Here is a very detailed problem",
"instance":"http://www.readthedocs.io/error/12345","status":400}
ETX;
        $actual = json_encode($problem) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testProblemWithInstance(): void
    {
        $problem = new ProblemDetailsDto('Test Error');
        $problem->instance = 'http://www.readthedocs.io/error/12345';
        $expected = <<<ETX
{"title":"Test Error","type":"about:blank","instance":"http://www.readthedocs.io/error/12345"}
ETX;
        $actual = json_encode($problem) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testProblemWithInstanceAndStatus(): void
    {
        $problem = new ProblemDetailsDto('Test Error');
        $problem->instance = 'http://localhost/errors/generic/12345';
        $problem->status = 400;
        $expected = <<<ETX
{"title":"Test Error","type":"about:blank","instance":"http://localhost/errors/generic/12345","status":400}
ETX;
        $actual = json_encode($problem) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testProblemWithStatus(): void
    {
        $problem = new ProblemDetailsDto('Test Error');
        $problem->status = 400;
        $expected = <<<ETX
{"title":"Test Error","type":"about:blank","status":400}
ETX;
        $actual = json_encode($problem) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testProblemWithType(): void
    {
        $problem = new ProblemDetailsDto('Test Error');
        $problem->type = 'http://localhost/errors/generic';
        $expected = <<<ETX
{"title":"Test Error","type":"http://localhost/errors/generic"}
ETX;
        $actual = json_encode($problem) ?: '';
        self::assertJsonStringEqualsJsonString($expected, $actual);
    }
}
