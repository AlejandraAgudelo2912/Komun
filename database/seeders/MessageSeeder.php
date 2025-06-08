<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $requests = RequestModel::where('status', 'in_progress')->get();
        $users = User::all();

        if ($requests->isEmpty() || $users->isEmpty()) {
            return;
        }

        foreach ($requests as $request) {
            $user = User::find($request->user_id);
            $assistant = User::find($request->assistant_id);

            if (! $user || ! $assistant) {
                continue;
            }

            Message::factory()
                ->count(3)
                ->recent()
                ->create([
                    'request_model_id' => $request->id,
                    'user_id' => $user->id,
                    'receiver_id' => $assistant->id,
                ]);

            Message::factory()
                ->count(3)
                ->recent()
                ->create([
                    'request_model_id' => $request->id,
                    'user_id' => $assistant->id,
                    'receiver_id' => $user->id,
                ]);
        }

        Message::factory()
            ->count(5)
            ->old()
            ->create();
    }
}
