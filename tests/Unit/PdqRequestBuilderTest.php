<?php

namespace Versatecnologia\Cadsus\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Versatecnologia\Cadsus\DTO\CadsusSearchRequest;
use Versatecnologia\Cadsus\HL7\Oid;
use Versatecnologia\Cadsus\HL7\PdqRequestBuilder;

final class PdqRequestBuilderTest extends TestCase
{
    public function testItBuildsAnXsdValidCpfRequest(): void
    {
        $xml = (new PdqRequestBuilder())
            ->build(CadsusSearchRequest::byCpf('000.000.000-00'), 'correlation');

        self::assertStringContainsString('root="' . Oid::CPF . '"', $xml);
        self::assertStringContainsString('extension="00000000000"', $xml);
    }

    public function testItEscapesXmlInput(): void
    {
        $xml = (new PdqRequestBuilder())
            ->build(CadsusSearchRequest::byDemographics(name: 'A & B', motherName: 'C < D'), 'correlation');

        self::assertStringContainsString('A &amp; B', $xml);
        self::assertStringNotContainsString('< D', $xml);
    }
}
