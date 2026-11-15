<?php

namespace App\Modules\Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Core\Http\Requests\ApiLoginRequest;
use App\Modules\Core\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(ApiLoginRequest $request): JsonResponse
    {
        $user = User::query()->where('email', (string) $request->string('email'))->first();

        if (! $user instanceof User || ! $user->is_active || ! Hash::check((string) $request->string('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials are not valid.'],
            ]);
        }

        $device = (string) $request->string('device');
        $token = $user->createToken($device !== '' ? $device : 'api')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $this->payload($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($this->payload($request->user()));
    }

    public function branches(Request $request): JsonResponse
    {
        $user = $request->user();
        $branches = Branch::query()->whereIn('id', $user->accessibleBranchIds())->orderBy('code')->get(['id', 'code', 'name', 'name_bn', 'type']);

        return response()->json(['data' => $branches]);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'user_type' => $user->user_type->value,
            'locale' => $user->locale,
            'branch_id' => $user->branch_id,
        ];
    }
}
