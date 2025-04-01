<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Group\Http\Requests\SaveGroupRequest;
use Modules\Group\Models\Group;

class GroupController extends Controller
{
    public function index(): JsonResponse
    {
        $groups = Group::all()->toArray(); // Filter to only groups that the user is a member

        return response()->json([
            'data' => $groups,
        ]);
    }

    public function store(SaveGroupRequest $request): JsonResponse
    {
        $group = $request->handle(new Group());

        return response()->json([
            'data' => $group,
        ]);
    }

    public function update(SaveGroupRequest $request, Group $group): JsonResponse
    {
        $group = $request->handle($group);

        return response()->json([
            'data' => $group,
        ]);
    }

    public function destroy(Group $group): JsonResponse
    {
        $group->delete();

        return response()->json([
            'data' => [],
        ]);
    }
}
