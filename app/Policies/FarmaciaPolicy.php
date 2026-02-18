<?php

namespace App\Policies;

use App\Models\Farmacia;
use App\Models\User;

class FarmaciaPolicy
{
    /**
     * Determinar se usuário pode ver a farmacia
     */
    public function view(User $user, Farmacia $farmacia): bool
    {
        return $user->isAdmin() || $user->id === $farmacia->user_id;
    }

    /**
     * Determinar se usuário pode criar farmacia
     */
    public function create(User $user): bool
    {
        return true; // Usuários autenticados podem criar
    }

    /**
     * Determinar se usuário pode atualizar farmacia
     */
    public function update(User $user, Farmacia $farmacia): bool
    {
        return $user->isAdmin() || $user->id === $farmacia->user_id;
    }

    /**
     * Determinar se usuário pode deletar farmacia
     */
    public function delete(User $user, Farmacia $farmacia): bool
    {
        return $user->isAdmin() || $user->id === $farmacia->user_id;
    }
}
