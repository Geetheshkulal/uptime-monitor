<?php
namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UnreadCommentsComposer
{
    public function compose(View $view)
    {
        $user = Auth::user();
        $unreadComments = 0;

        if ($user->hasRole('superadmin')) {
            $unreadComments = Comment::where('is_read', false)
                ->whereHas('user.roles', function ($q) {
                    $q->whereIn('name', ['user', 'support']);
                })
                ->count();
        } elseif ($user->hasRole('user')) {
            $unreadComments = Comment::where('is_read', false)
                ->whereHas('user.roles', function ($q) {
                    $q->whereIn('name', ['superadmin', 'support']);
                })
                ->count();
        } elseif ($user->hasRole('support')) {
            $unreadComments = Comment::where('is_read', false)
                ->whereHas('user.roles', function ($q) {
                    $q->whereIn('name', ['superadmin', 'user']);
                })
                ->count();
        }

        $view->with('unreadComments', $unreadComments);
    }
}
