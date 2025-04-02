<?php

namespace Modules\Membership\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Membership\Dto\CreateMemberDto;
use Modules\Membership\Dto\UpdateMemberDto;
use Modules\Membership\Http\Requests\JoinGroupRequest;
use Modules\Membership\Http\Requests\ListMembersRequest;
use Modules\Membership\Http\Requests\UpdateMemberRequest;
use Modules\Membership\Services\MembershipService;

class MembershipController extends Controller
{
    protected $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    public function join(JoinGroupRequest $request): JsonResponse
    {
        $dto = CreateMemberDto::fromRequest($request);
        $membership = $this->membershipService->joinGroup($dto);

        return response()->json(['membership' => $membership], 201);
    }

    public function members(ListMembersRequest $request): JsonResponse
    {
        $membership = $this->membershipService->findMember($request->get('group_id'), $request->user()->id);
        $this->authorize('see', $membership);

        $members = $this->membershipService->listMembers($request->get('group_id'));

        return response()->json(['memberships' => $members]);
    }

    public function promote(UpdateMemberRequest $request): JsonResponse
    {
        $dto = UpdateMemberDto::fromRequest($request);

        $membership = $this->membershipService->findMember($dto->groupId, $dto->userId);
        $this->authorize('promote', $membership);

        $updatedMembership = $this->membershipService->promoteMember($dto);

        return response()->json(['membership' => $updatedMembership]);
    }

    public function demote(UpdateMemberRequest $request): JsonResponse
    {
        $dto = UpdateMemberDto::fromRequest($request);

        $membership = $this->membershipService->findMember($dto->groupId, $dto->userId);
        $this->authorize('demote', $membership);

        $membership = $this->membershipService->demoteMember($dto);

        return response()->json(['membership' => $membership]);
    }

    public function leave(UpdateMemberRequest $request): JsonResponse
    {
        $dto = UpdateMemberDto::fromRequest($request);

        $membership = $this->membershipService->findMember($dto->groupId, $dto->userId);
        $this->authorize('leave', $membership);

        $this->membershipService->leaveGroup($dto);

        return response()->json(['message' => 'Left group successfully.']);
    }
}
