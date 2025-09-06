<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Blog;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_blogs::blog');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Blog $blog): bool
    {
        return $user->can('view_blogs::blog');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_blogs::blog');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Blog $blog): bool
    {
        return $user->can('update_blogs::blog');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Blog $blog): bool
    {
        return $user->can('delete_blogs::blog');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_blogs::blog');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Blog $blog): bool
    {
        return $user->can('force_delete_blogs::blog');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_blogs::blog');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Blog $blog): bool
    {
        return $user->can('restore_blogs::blog');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_blogs::blog');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Blog $blog): bool
    {
        return $user->can('replicate_blogs::blog');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_blogs::blog');
    }
}
