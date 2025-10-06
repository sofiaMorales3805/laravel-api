<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

interface ClientRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Client;
    public function create(array $data): Client;
    public function update(int $id, array $data): ?Client;
    public function delete(int $id): bool;
}