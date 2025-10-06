<?php

namespace App\Services;

use App\Repositories\ClientRepositoryInterface;
use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

class ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getAllClients(): Collection
    {
        return $this->clientRepository->all();
    }

    public function getClientById(int $id): ?Client
    {
        return $this->clientRepository->find($id);
    }

    public function createClient(array $data): Client
    {
        return $this->clientRepository->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'nit' => $data['nit']
        ]);
    }

    public function updateClient(int $id, array $data): ?Client
    {
        return $this->clientRepository->update($id, [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'nit' => $data['nit']
        ]);
    }

    public function deleteClient(int $id): bool
    {
        return $this->clientRepository->delete($id);
    }
}