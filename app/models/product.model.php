<?php

class ProductModel {

    private $db;

    //Abro la conexion
    function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpe;charset=utf8', 'root', '');
    }

    //Traigo todos los productos de la bbdd
    function getAll() {
        $query = $this->db->prepare("SELECT * FROM products");
        $query->execute();

        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
        
        return $products;
    }

    function get($id){
        $query = $this->db->prepare('SELECT * FROM products WHERE id = ?');
        $query->execute([$id]);
        $product = $query->fetch(PDO::FETCH_OBJ);
        
        return $product;
    }

    function insert($name, $id_brand, $description, $price, $sale) {
        $query = $this->db->prepare("INSERT INTO products (name, id_brand, description, price, sale) VALUES (?, ?, ?, ?, ?)");
        $query->execute([$name, $id_brand, $description, $price, $sale]);

        return $this->db->lastInsertId();
    }
 
    function delete($id) {
        $query = $this->db->prepare('DELETE FROM products WHERE id = ?');
        $query->execute([$id]);
    }

    function edit($name, $id_brand, $description, $price, $sale, $id){
        $query = $this->db->prepare('UPDATE `products` SET name = ? , id_brand = ? , description = ?, price = ?, sale = ? WHERE id = ?');
        $query->execute([$name, $id_brand, $description, $price, $sale, $id]);
        return $this->db->lastInsertId();
    }
    
}