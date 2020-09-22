<?php

declare(strict_types=1);

namespace izucken\asn1\tests;

use DateTime;
use DateTimeInterface;
use FG\ASN1\ExplicitlyTaggedObject;
use FG\ASN1\Identifier;
use FG\ASN1\Universal\Integer;
use FG\ASN1\Universal\NullObject;
use FG\ASN1\Universal\ObjectIdentifier;
use FG\ASN1\Universal\Sequence;
use FG\ASN1\Universal\UTCTime;
use izucken\asn1\Context;
use izucken\asn1\Modules\PKIX1Explicit88\AttributeTypeAndValue;
use izucken\asn1\StructuralError;
use izucken\asn1\Structures\Primitive;
use izucken\asn1\Structures\Sequence\Option;
use izucken\asn1\Structures\SequenceOf;
use PHPUnit\Framework\TestCase;

final class StructuralElementsTest extends TestCase
{
    public function testPrimitives(): void
    {
        $context = new Context();
        $this->assertEquals(null, $context->parse(
            new NullObject(),
            new Primitive(Identifier::NULL)
        ));
        $this->assertEquals(123456, $context->parse(
            new Integer(123456),
            Identifier::INTEGER
        ));
        $this->assertEquals("1.1.1.1", $context->parse(
            new ObjectIdentifier("1.1.1.1"),
            new Primitive(Identifier::OBJECT_IDENTIFIER)
        ));
        $this->assertInstanceOf(DateTimeInterface::class, $context->parse(
            new UTCTime(new DateTime()),
            Identifier::UTC_TIME
        ));
    }

    public function testSequence(): void
    {
        $context = new Context();
        $struct = new \izucken\asn1\Structures\Sequence([
            "a" => Identifier::INTEGER,
            "b" => new Option(Identifier::OBJECT_IDENTIFIER),
            "c" => Identifier::INTEGER,
        ]);
        $asn = new Sequence(
            new Integer(1),
            new Integer(2),
        );
        $this->assertEquals(["a" => 1, "c" => 2], $context->parse($asn, $struct));
        $asn = new Sequence(
            new Integer(1),
            new ObjectIdentifier("1.1.1"),
            new Integer(2),
        );
        $this->assertEquals(["a" => 1, "b" => "1.1.1", "c" => 2], $context->parse($asn, $struct));
        $this->expectException(StructuralError::class);
        $asn = new Sequence(
            new NullObject(),
            new ObjectIdentifier("1.1.1"),
            new Integer(2),
        );
        $context->parse($asn, $struct);
    }

    public function testSequenceOf(): void
    {
        $context = new Context();
        $struct = new \izucken\asn1\Structures\SequenceOf(Identifier::INTEGER);
        $asn = new Sequence(
            new Integer(1),
            new Integer(2),
            new Integer(3),
        );
        $this->assertEquals([1, 2, 3], $context->parse($asn, $struct));
        $asn = new Sequence(
            new Integer(1),
            new ObjectIdentifier("1.1.1"),
            new Integer(2),
        );
        $this->expectException(StructuralError::class);
        $context->parse($asn, $struct);
    }

    public function testChoice(): void
    {
        $context = new Context();
        $struct = new \izucken\asn1\Structures\Choice([
            'a' => Identifier::INTEGER,
            'b' => new \izucken\asn1\Structures\SequenceOf(Identifier::INTEGER),
        ]);
        $asn = new Integer(1);
        $this->assertEquals(['a' => 1], $context->parse($asn, $struct));
        $asn = new Sequence(
            new Integer(1),
            new Integer(2),
            new Integer(3)
        );
        $this->assertEquals(['b' => [1, 2, 3]], $context->parse($asn, $struct));
        $asn = new ObjectIdentifier("1.1.1");
        $this->expectException(StructuralError::class);
        $context->parse($asn, $struct);
    }

    public function testStruct(): void
    {
        $context = new Context();
        $struct = new \izucken\asn1\Structures\Struct(AttributeTypeAndValue::class);
        $asn = new Sequence(
            new ObjectIdentifier("1.1.1"),
            new Integer(1)
        );
        $sample = $context->parse($asn, $struct);
        $this->assertInstanceOf(AttributeTypeAndValue::class, $sample);
        $this->assertEquals("1.1.1", $sample->type);
        $this->assertEquals(1, $sample->value);
    }

    public function testExplicit(): void
    {
        $context = new Context();
        $struct = new \izucken\asn1\Structures\Explicit(1, new SequenceOf(Identifier::INTEGER));
        $asn = new ExplicitlyTaggedObject(1, new Sequence(
            new Integer(1),
            new Integer(2),
            new Integer(3)
        ));
        $this->assertEquals([1, 2, 3], $context->parse($asn, $struct));
        $asn = new ObjectIdentifier("1.1.1");
        $this->expectException(StructuralError::class);
        $context->parse($asn, $struct);
    }

    public function testImplicit(): void
    {
        $context = new Context();
        $struct = new \izucken\asn1\Structures\Implicit(1, new SequenceOf(Identifier::INTEGER));
        $asn = new ExplicitlyTaggedObject(1,
            new Integer(1),
            new Integer(2),
            new Integer(3),
        );
        $this->assertEquals([1, 2, 3], $context->parse($asn, $struct));
        $asn = new ObjectIdentifier("1.1.1");
        $this->expectException(StructuralError::class);
        $context->parse($asn, $struct);
    }
}
