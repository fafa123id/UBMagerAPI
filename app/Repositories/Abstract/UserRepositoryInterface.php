<?php

namespace App\Repositories\Abstract;


interface UserRepositoryInterface
{
    public function self();
    public function find($id);
    public function update($id, array $data, string $oldEmail);
    public function delete($id);
}