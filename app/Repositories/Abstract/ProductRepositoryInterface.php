<?php
namespace App\Repositories\Abstract;

interface ProductRepositoryInterface
{
    public function all($type, $category, $query);
    public function getType();
    public function getCategoryByType($type);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

