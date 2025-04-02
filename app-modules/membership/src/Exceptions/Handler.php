<?php

namespace Modules\Membership\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception): JsonResponse
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }

        if ($exception instanceof UnauthorisedActionException) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }

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
