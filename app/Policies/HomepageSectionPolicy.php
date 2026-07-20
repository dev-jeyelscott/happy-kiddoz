<?php

namespace App\Policies;

use App\Models\HomepageSection;
use App\Models\User;

class HomepageSectionPolicy
{
    /**
     * Allow authenticated panel users to view the homepage section list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Allow authenticated panel users to view a homepage section.
     */
    public function view(User $user, HomepageSection $homepageSection): bool
    {
        return true;
    }

    /**
     * Prevent creation because homepage sections are canonical seeded records.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Allow authenticated panel users to update homepage content.
     */
    public function update(User $user, HomepageSection $homepageSection): bool
    {
        return true;
    }

    /**
     * Prevent deletion of fixed homepage sections.
     */
    public function delete(User $user, HomepageSection $homepageSection): bool
    {
        return false;
    }

    /**
     * Prevent restoration because homepage sections do not use soft deletion.
     */
    public function restore(User $user, HomepageSection $homepageSection): bool
    {
        return false;
    }

    /**
     * Prevent permanent deletion of fixed homepage sections.
     */
    public function forceDelete(User $user, HomepageSection $homepageSection): bool
    {
        return false;
    }
}
