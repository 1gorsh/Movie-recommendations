<?php
declare(strict_types = 1);

namespace App\Tests\Repository;

use App\Model\Movie;
use App\Repository\MovieHydrator;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

class MovieHydratorTest extends TestCase
{
    private $logger;

    protected function setUp()
    {
        $this->logger = $this->prophesize(LoggerInterface::class);
    }

    /**
     * @test
     */
    public function itHydratesCorrectly()
    {
        $data = json_decode('{
                "name": "Zootopia",
                "rating": 92,
                "genres": [
                    "Action & Adventure",
                    "Animation",
                    "Comedy"
                ],
                "showings": [
                    "19:00:00+11:00",
                    "21:00:00+11:00"
                ]
            }', true);

        /** @var Movie $object */
        $object = (new MovieHydrator($this->logger->reveal()))->hydrate($data);

        $this->assertInstanceOf(Movie::class, $object);
        $this->assertEquals("Zootopia", $object->name());
        $this->assertEquals(92, $object->rating());
    }

    /**
     * @test
     */
    public function itReturnsNullWhenNameIsNotCorrectAndLogsErrorMessage()
    {
        $data = ['name' => ''];
        $this->logger->info('Not valid movie data: {"name":""}')->shouldBeCalled();

        $result = (new MovieHydrator($this->logger->reveal()))->hydrate($data);

        $this->assertNull($result);
    }
}
