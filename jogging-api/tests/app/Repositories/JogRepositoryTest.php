<?php

namespace App\Repositories;

use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Jog;

class JogRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected function getRepository()
    {
        return app(JogRepository::class);
    }

    public function weeklyReportProvider()
    {
        return [
            [1, 1000, 0, 1000, 0],
            [20, 1000, 10, 20000, 200],
        ];
    }
    /**
     * @dataProvider weeklyReportProvider
     */
    public function testShouldGetWeeklyReport($count, $distance, $duration, $totalDistance, $totalDuration)
    {
        $user = factory(User::class)->create();
        $jogs = factory(Jog::class, $count)->create([
            'started_at' => Carbon::now(),
            'ended_at' => Carbon::now()->addMinutes($duration),
            'distance' => $distance,
            'owner_id' => $user->id,
        ]);

        $result = $this->getRepository()->getWeeklyReport()->where('owner_id', $user->id)->first();

        $this->assertEquals($totalDistance, $result->total_distance);
        $this->assertEquals($totalDuration, $result->total_duration);
    }
}
