<?php
class filterTest extends PHPUnit_Framework_TestCase
{
    public function testFilterStripsSlashesDeeply()
    {
        $this->assertEquals(
            array("f'oo", "b'ar", array("fo'o", "b'ar")),
            filter::stripslashes_deep(array("f\\'oo", "b\\'ar", array("fo\\'o", "b\\'ar")))
        );
    }

    public function testFilterMakesSizesHumanlyReadable()
    {
        $this->assertEquals('1 B', filter::size_humanreadable(1));
        $this->assertEquals('1 000 B', filter::size_humanreadable(1000));
        $this->assertEquals('1.00 kiB', filter::size_humanreadable(1024));
        $this->assertEquals('1.21 kiB', filter::size_humanreadable(1234));
        $exponent = 1024;
        $this->assertEquals('1 000.00 kiB', filter::size_humanreadable(1000 * $exponent));
        $this->assertEquals('1.00 MiB', filter::size_humanreadable(1024 * $exponent));
        $this->assertEquals('1.21 MiB', filter::size_humanreadable(1234 * $exponent));
        $exponent *= 1024;
        $this->assertEquals('1 000.00 MiB', filter::size_humanreadable(1000 * $exponent));
        $this->assertEquals('1.00 GiB', filter::size_humanreadable(1024 * $exponent));
        $this->assertEquals('1.21 GiB', filter::size_humanreadable(1234 * $exponent));
        $exponent *= 1024;
        $this->assertEquals('1 000.00 GiB', filter::size_humanreadable(1000 * $exponent));
        $this->assertEquals('1.00 TiB', filter::size_humanreadable(1024 * $exponent));
        $this->assertEquals('1.21 TiB', filter::size_humanreadable(1234 * $exponent));
        $exponent *= 1024;
        $this->assertEquals('1 000.00 TiB', filter::size_humanreadable(1000 * $exponent));
        $this->assertEquals('1.00 PiB', filter::size_humanreadable(1024 * $exponent));
        $this->assertEquals('1.21 PiB', filter::size_humanreadable(1234 * $exponent));
        $exponent *= 1024;
        $this->assertEquals('1 000.00 PiB', filter::size_humanreadable(1000 * $exponent));
        $this->assertEquals('1.00 EiB', filter::size_humanreadable(1024 * $exponent));
        $this->assertEquals('1.21 EiB', filter::size_humanreadable(1234 * $exponent));
        $exponent *= 1024;
        $this->assertEquals('1 000.00 EiB', filter::size_humanreadable(1000 * $exponent));
        $this->assertEquals('1.00 ZiB', filter::size_humanreadable(1024 * $exponent));
        $this->assertEquals('1.21 ZiB', filter::size_humanreadable(1234 * $exponent));
        $exponent *= 1024;
        $this->assertEquals('1 000.00 ZiB', filter::size_humanreadable(1000 * $exponent));
        $this->assertEquals('1.00 YiB', filter::size_humanreadable(1024 * $exponent));
        $this->assertEquals('1.21 YiB', filter::size_humanreadable(1234 * $exponent));
    }

    public function testPasteIdValidation()
    {
        $this->assertTrue(filter::is_valid_paste_id('a242ab7bdfb2581a'), 'valid paste id');
        $this->assertFalse(filter::is_valid_paste_id('foo'), 'invalid hex values');
        $this->assertFalse(filter::is_valid_paste_id('../bar/baz'), 'path attack');
    }

    public function testSlowEquals()
    {
        $this->assertTrue(filter::slow_equals('foo', 'foo'), 'same string');
        $this->assertFalse(filter::slow_equals('foo', true), 'string and boolean');
        $this->assertFalse(filter::slow_equals('foo', 0), 'string and integer');
        $this->assertFalse(filter::slow_equals('123foo', 123), 'string and integer');
        $this->assertFalse(filter::slow_equals('123foo', '123'), 'different strings');
        $this->assertFalse(filter::slow_equals('6', ' 6'), 'strings with space');
        $this->assertFalse(filter::slow_equals('4.2', '4.20'), 'floats as strings');
        $this->assertFalse(filter::slow_equals('1e3', '1000'), 'integers as strings');
        $this->assertFalse(filter::slow_equals('9223372036854775807', '9223372036854775808'), 'large integers as strings');
        $this->assertFalse(filter::slow_equals('61529519452809720693702583126814', '61529519452809720000000000000000'), 'larger integers as strings');
    }
}