<?php

namespace App\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class RepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected function getRepository()
    {
        return Mockery::mock(Repository::class)->makePartial();
    }

    public function testFindOrFail()
    {
        $id = 123;
        $repository = $this->getRepository();
        $repository
            ->shouldReceive('findByOrFail')
            ->with('id', $id)
            ->once()
            ->andReturn($id);

        $this->assertEquals($id, $repository->findOrFail($id));
    }
}
