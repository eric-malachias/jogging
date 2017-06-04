<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Repositories\JoggingTimeRepository;
use App\Models\JoggingTime;
use App\Http\Requests\JoggingTimeRequest;

class JoggingTimeController extends Controller
{
    public function deleteOne(JoggingTime $joggingTime)
    {
        $joggingTime->delete();

        return response()->ok();
    }
    public function getOne(JoggingTime $joggingTime)
    {
        return response()->ok($joggingTime);
    }
    public function postOne(JoggingTimeRequest $request, JoggingTimeRepository $joggingTimeRepository)
    {
        $joggingTime = $joggingTimeRepository->create($request->only([
            'started_at',
            'ended_at',
            'distance',
        ]));

        return response()->ok($joggingTime);
    }
    public function putOne(JoggingTimeRequest $request, JoggingTime $joggingTime)
    {
        $joggingTime->fill($request->only([
            'started_at',
            'ended_at',
            'distance',
        ]));
        $joggingTime->save();

        return response()->ok($joggingTime);
    }
}
