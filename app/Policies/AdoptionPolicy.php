<?php

namespace App\Policies;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/*
|-----------------------------------------------------------------------
| Task 1 Authorization.
| You can use to policy for authorize adoptions
|-----------------------------------------------------------------------
*/

class AdoptionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Adoption $adoption): Response
    {
        return $adoption->listedBy->id === $user->id
            ? Response::deny('You cannot adopt your own pet.')
            : Response::allow();
    }

}
