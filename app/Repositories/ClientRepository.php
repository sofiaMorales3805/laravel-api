<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

class ClientRepository implements ClientRepositoryInterface
{
    public function all(): Collection
    {
        return Client::all();
    }

    public function find(int $id): ?Client
    {
        return Client::find($id);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(int $id, array $data): ?Client
    {
        $client = Client::find($id);
        if ($client) {
            $client->update($data);
            return $client;
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $client = Client::find($id);
        if ($client) {
            return $client->delete();
        }
        return false;
    }
}