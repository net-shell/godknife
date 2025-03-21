<?php

namespace App\Livewire;

use App\Models\Notification;
use App\Models\User;
use Livewire\Component;

class Admin extends Component
{
    public function render()
    {
        return view('livewire.admin')->extends('layouts.app');
    }

    public function ban($userid)
    {
        $user = User::findOrFail($userid);
        if ($user->is_banned >= 3) {
            $user->update([
                'is_private' => 1,
                'is_banned' => 0,
                'banned_at' => null,
                'banned_to' => null,

            ]);
            Notification::create([
                'type' => 'Temporary Lock',
                'user_id' => $user->id,
                'message' => $user->username . ' You have been Temporary Lock.',
                'url' => 'logout',
            ]);
        } else {
            $user->update([
                'is_banned' => $user->is_banned + 1,
                'banned_at' => now('Europe/Sofia'),
                'banned_to' => now('Europe/Sofia')->addMinute(5),
            ]);

            Notification::create([
                'type' => 'Ban User',
                'user_id' => $user->id,
                'message' => $user->username . ' You have been banned from the platform.',
                'url' => '/profile/' . $user->username,
            ]);
        }

        return redirect()->route('all-users');
    }

    public function unban($userid)
    {
        $user = User::findOrFail($userid);
        $user->update([
            'banned_to' => now('Europe/Sofia'),
        ]);

        return redirect()->route('all-users');
    }

    public function unlock($userid)
    {
        $user = User::findOrFail($userid);
        $user->update([
            'is_private' => 0,
        ]);

        return redirect()->route('all-users');
    }
}
