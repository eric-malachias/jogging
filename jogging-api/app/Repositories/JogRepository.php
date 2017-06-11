<?php

namespace App\Repositories;

use App\Models\Jog;
use DB;

class JogRepository extends Repository
{
    protected $model = Jog::class;

    public function getWeeklyReport()
    {
        return app(Jog::class)
            ->selectRaw(
                'sum(distance) as total_distance,
                sum(timestampdiff(minute, started_at, ended_at)) as total_duration,
                yearweek(started_at) as year_week'
            )
            ->groupBy(DB::raw('yearweek(started_at)'))
            ->orderByDesc('year_week');
    }
}
