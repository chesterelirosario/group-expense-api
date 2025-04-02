<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Group\Dto\CreateGroupDto;
use Modules\Group\Dto\UpdateGroupDto;
use Modules\Group\Http\Requests\SaveGroupRequest;
use Modules\Group\Models\Group;
use Modules\Group\Services\GroupService;

class GroupController extends Controller
{
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index(): JsonResponse
    {
        $groups = $this->groupService->getAllGroups();

        return response()->json(['groups' => $groups]);
    }

    public function store(SaveGroupRequest $request): JsonResponse
    {
        $dto = CreateGroupDto::fromRequest($request);
        $group = $this->groupService->createGroup($dto);

        return response()->json(['group' => $group], 201);
    }

    public function update(SaveGroupRequest $request, Group $group): JsonResponse
    {
        $dto = UpdateGroupDto::fromRequest($request);
        $group = $this->groupService->updateGroup($group, $dto);

        return response()->json(['group' => $group]);
    }

    public function destroy(Group $group): JsonResponse
    {
        $this->groupService->deleteGroup($group);

        return response()->json([], 204);
    }
}
