<?php

namespace Tests\Unit;

use App\Http\Controllers\Frontend\User\SRBController;
use PHPUnit\Framework\TestCase;

class SpiColourComputeTest extends TestCase
{
    protected $spi;

    protected function setUp(): void
    {
        $this->spi = new SRBController();
    }

    public function testNumberRange()
    {
        $this->assertTrue($this->spi->computeColourLtGt(166, 160, 'GT'));
        $this->assertFalse($this->spi->computeColourLtGt(17, 160, 'GT'));
        $this->assertTrue($this->spi->computeColourLtGt(17, 160, 'LT'));
        $this->assertFalse($this->spi->computeColourLtGt(180, 160, 'LT'));
        $this->asserttrue($this->spi->computeColourLtGt(180, 160, 'GTE'));
        $this->asserttrue($this->spi->computeColourLtGt(18, 160, 'LTE'));
        $this->assertFalse($this->spi->computeColourLtGt(18, 160, 'GTE'));
        $this->assertFalse($this->spi->computeColourLtGt(180, 160, 'LTE'));


        $this->assertTrue($this->spi->inRange(160, 160, 'GT'));
    }
}
