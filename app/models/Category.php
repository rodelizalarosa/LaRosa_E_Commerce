<?php

namespace Rodeliza\MiniFrameworkStore\Models;

use Rodeliza\MiniFrameworkStore\Includes\Database;

class Category extends Database
{

    private $db;

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor to establish the connection
        $this->db = $this->getConnection(); // Get the connection instance
    }

    public function getAll()
    {
        $sql = "SELECT * FROM product_categories";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByName($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM product_categories WHERE category_name = :name LIMIT 1");
        $stmt->execute(['name' => $name]);
        return $stmt->fetch();
    }


}