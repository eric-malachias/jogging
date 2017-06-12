<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Repositories\JogRepository;
use App\Models\Jog;
use App\Http\Requests\JogRequest;

class JogController extends Controller
{
    public function deleteOne(Jog $jog)
    {
        $jog->delete();

        return response()->ok();
    }
    public function getOne(Jog $jog)
    {
        return response()->ok($jog);
    }
    public function postOne(JogRequest $request, JogRepository $jogRepository)
    {
        $jog = $jogRepository->create($request->only([
            'started_at',
            'ended_at',
            'distance',
        ]));

        return response()->created($jog);
    }
    public function putOne(JogRequest $request, Jog $jog)
    {
        $jog->fill($request->only([
            'started_at',
            'ended_at',
            'distance',
        ]));
        $jog->save();

        return response()->ok($jog);
    }
    public function getAll(JogRepository $jogRepository, Request $request)
    {
        $jogs = $jogRepository
            ->with('owner')
            ->between($request->before, $request->after)
            ->latestFirst()
            ->paginate(10);

        return response()->ok($jogs);
    }
}
