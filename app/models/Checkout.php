<?php

namespace Rodeliza\MiniFrameworkStore\Models;

use Rodeliza\MiniFrameworkStore\Includes\Database;
use Carbon\Carbon;

class Checkout extends Database
{

    private $db;

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor to establish the connection
        $this->db = $this->getConnection(); // Get the connection instance
    }

    public function getRevenueByCategory() {
        $sql = "
            SELECT pc.category_name, SUM(od.subtotal) AS revenue
            FROM order_details od
            JOIN products p ON od.product_id = p.id
            JOIN product_categories pc ON p.category_id = pc.id
            GROUP BY pc.category_name
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function userCheckout($data)
    {
        $sql = "INSERT INTO orders (customer_id, landmark_address, total, created_at, updated_at)
                VALUES (:customer_id, :landmark_address, :total, :created_at, :updated_at)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'customer_id' => $data['user_id'],
            'landmark_address' => $data['landmark'],
            'total' => $data['total'],
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila')
        ]);

        return $this->db->lastInsertId();
    }

    public function saveOrderDetails($data)
    {
        $sql = "INSERT INTO order_details (order_id, product_id, quantity, price, subtotal) VALUES (:order_id, :product_id, :quantity, :price, :subtotal)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'order_id' => $data['order_id'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'subtotal' => $data['subtotal']
        ]);
    }

    public function getAllOrders()
    {
        $sql = "SELECT 
                    o.id, 
                    u.name AS user_name, 
                    p.name AS product_name, 
                    od.quantity, 
                    od.price AS total_price, 
                    o.created_at AS order_date,
                    od.order_status
                FROM orders o
                LEFT JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_details od ON o.id = od.order_id
                LEFT JOIN products p ON od.product_id = p.id
                ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


}