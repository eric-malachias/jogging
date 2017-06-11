<?php

namespace App\Repositories;

use Tests\TestCase;

class RoleRepositoryTest extends TestCase
{
    protected function getRepository()
    {
        return app(RoleRepository::class);
    }

    public function testFindRegularShouldReturnCorrectEntry()
    {
        $this->assertTrue($this->getRepository()->findRegular()->isRegular());
    }
}
