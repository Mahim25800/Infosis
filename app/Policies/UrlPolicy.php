<?php

namespace App\Policies;

use App\Models\Url;
use App\Models\User;

class UrlPolicy
{
    /**
     * A link can only be read by the person who created it.
     */
    public function view(User $user, Url $url): bool
    {
        return $user->id === $url->user_id;
    }

    /**
     * The same ownership rule applies when deleting a link.
     */
    public function delete(User $user, Url $url): bool
    {
        return $user->id === $url->user_id;
    }
}
