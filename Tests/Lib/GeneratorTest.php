<?php
namespace Werkspot\Bundle\SitemapBundle\Tests\Lib;

use Mockery;
use Werkspot\Bundle\SitemapBundle\Lib\Provider\ProviderInterface;
use Werkspot\Bundle\SitemapBundle\Lib\Generator;
use Werkspot\Bundle\SitemapBundle\Lib\Sitemap\SitemapSection;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Generator
     */
    private $generator;

    protected function setUp()
    {
        $this->generator = new Generator;
    }

    public function testGenerateIndexWithEmptyCount()
    {
        $mockProvider = Mockery::mock(ProviderInterface::class);

        $mockProvider->shouldReceive('getNumberOfPages')->andReturn(0);
        $mockProvider->shouldReceive('getSectionName')->andReturn('mock-empty-section');

        $this->generator->addProvider($mockProvider);
        $index = $this->generator->generateIndex();

        $this->assertEmpty($index->getSections());
    }

    public function testGenerateIndex()
    {
        $mockCount = 1234;
        $mockSection = Mockery::mock(SitemapSection::class);
        $mockSection->shouldReceive('setPageCount')->with($mockCount);
        $mockProvider = Mockery::mock(ProviderInterface::class);

        $mockProvider->shouldReceive('getNumberOfPages')->andReturn($mockCount);
        $mockProvider->shouldReceive('getSectionName')->andReturn('mock-empty-section');
        $mockProvider->shouldReceive('getSection')->andReturn($mockSection);

        $this->generator->addProvider($mockProvider);
        $index = $this->generator->generateIndex();

        $this->assertEquals([$mockSection], $index->getSections());
    }
}
