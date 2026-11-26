<?php

use App\Models\User;
use Illuminate\Support\Str;

test('users use UUIDs and support soft deletes and active status', function () {
    $user = User::factory()->create();

    expect(Str::isUuid($user->id))->toBeTrue()
        ->and($user->is_active)->toBeTrue();

    $user->delete();

    expect(User::find($user->id))->toBeNull()
        ->and(User::withTrashed()->find($user->id))->not->toBeNull();
});
