<?php

namespace App\Interfaces\Repositories;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * Get team members with inquiry stats
     *
     * @param array $filters
     * @ @return LengthAwarePaginator | Collection
     */
    public function getTeamMembers(array $filters): LengthAwarePaginator | Collection;

    /**
     * Store a new user in storage
     *
     * @param array $userData
     * @return \Exception | User
     */
    public function storeUser(array $userData): User|\Exception;

    /**
     * Update the specified user resource
     *
     * @param User $user
     * @param array $userData
     * @return \Exception | User
     */
    public function updateUser(User $user, array $userData): \Exception | User;
}
