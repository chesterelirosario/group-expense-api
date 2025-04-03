<?php

namespace Modules\Membership\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception): JsonResponse
    {
        if ($exception instanceof MemberAlreadyExistsException) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }

        if ($exception instanceof MemberAlreadyAMemberException) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }

        if ($exception instanceof MemberAlreadyAnAdminOrOwnerException) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }

        return parent::render($request, $exception);
    }
}
