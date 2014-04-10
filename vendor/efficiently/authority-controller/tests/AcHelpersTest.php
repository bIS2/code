<?php

class AcHelpersTest extends AcTestCase
{

    public function testArrayExtractOptions()
    {
        $args = ['foo' => 'bar', 'value'];
        $options = array_extract_options($args);
        $expect = ['foo' => 'bar'];
        $this->assertEquals($options, $expect);
    }

    public function testCompactProperty()
    {
        $expectName = "Best movie";
        $myProduct = new AcProduct;
        $myProduct->setName($expectName);
        $compactedArray = compact_property($myProduct, 'name');

        $this->assertArrayHasKey('name', $compactedArray);
        $this->assertEquals($expectName, $compactedArray['name']);
    }

    public function testCompactProperties()
    {
        $expectName = "Best movie";
        $expectPrice = "15";
        $myProduct = new AcProduct;
        $myProduct->setName($expectName);
        $myProduct->setPrice($expectPrice);
        $compactedArray = compact_property($myProduct, 'name', 'price');

        $this->assertArrayHasKey('name', $compactedArray);
        $this->assertArrayHasKey('price', $compactedArray);
        $this->assertEquals($expectName, $compactedArray['name']);
        $this->assertEquals($expectPrice, $compactedArray['price']);
    }

}
