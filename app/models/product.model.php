<?php

class ProductModel {

    private $db;

    //Abro la conexion
    function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpe;charset=utf8', 'root', '');
    }
    
    //Devuelve la cantidad total de registros de la tabla de la bbdd
    function count(){
        $query = $this->db->prepare("SELECT count(*) FROM products");
        $query->execute();
        $total = $query->fetchColumn();
        return $total;
    }
    
    //Orden y paginacion
    function getAll($limit, $offset, $sort, $order){
        $query = $this->db->prepare("SELECT * FROM products ORDER BY $sort $order LIMIT $offset, $limit");
        $query->execute();
        
        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
        
        return $products;
    }
    
    //Orden, paginacion y filtro 
    function getAllFilter($field, $value, $limit, $offset, $sort, $order){
        $query = $this->db->prepare("SELECT * FROM products WHERE $field = $value  ORDER BY $sort $order LIMIT $offset, $limit");
        $query->execute();
        
        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
        
        return $products;
    }

    //Orden, paginacion y filtro 
    function getAllFilterPrice($field, $value, $limit, $offset, $sort, $order){
        $query = $this->db->prepare("SELECT * FROM products WHERE $field < ?  ORDER BY $sort $order LIMIT $offset, $limit");
        $query->execute([$value]);
        
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