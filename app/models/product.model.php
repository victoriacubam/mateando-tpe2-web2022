<?php

class ProductModel {

    private $db;

    //Abro la conexion
    function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpe;charset=utf8', 'root', '');
    }
    
    
    function count(){
        $query = $this->db->prepare("SELECT count(*) FROM products");
        $query->execute();
        $total = $query->fetchColumn();
        return $total;
    }

    function fields(){
        $query = $this->db->prepare("DESCRIBE products");
        $query->execute();
        $fields = $query->fetchAll(PDO::FETCH_ASSOC);
        return $fields;
    }

    //Traigo todos los productos de la bbdd
    function getAll(){
        $query = $this->db->prepare("SELECT * FROM products");
        $query->execute();
        
        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
        
        return $products;
    }

    //Filtrado
    function getAllFilter($field, $value){
        $query = $this->db->prepare("SELECT * FROM products WHERE $field = ?");
        $query->execute([$value]);
        
        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
        
        return $products;
    }

    //Filtrado por precios menores
    function getAllFilterLowerPrice($field, $value){
        $query = $this->db->prepare("SELECT * FROM products WHERE $field < ?");
        $query->execute([$value]);
        
        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
        
        return $products;
    }
    
    //Ordenado
    //Traigo todos los productos de la bbdd en ordenado en base a una columna
    function getAllInOrder($sort, $order) {
        $query = $this->db->prepare("SELECT * FROM products ORDER BY $sort $order");
        $query->execute();
      
        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
 
        return $products;
    }
    
    //Paginacion
    function getAllLimit($limit, $offset){
        $query = $this->db->prepare("SELECT * FROM `products` LIMIT $offset, $limit");
        $query->execute();
       
        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
 
        return $products;
    }
    
    //Orden y paginacion
    function getAllInOrderAndLimit($limit, $offset, $sort, $order){
        $query = $this->db->prepare("SELECT * FROM products ORDER BY $sort $order LIMIT $offset, $limit");
        $query->execute();
      
        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
 
        return $products;
    }
    
    //Orden y Filtrado
    function getAllFilterAndOrder($field, $value, $sort, $order){
        $query = $this->db->prepare("SELECT * FROM products WHERE $field = ? ORDER BY $sort $order");
        $query->execute([$value]);
      
        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
 
        return $products;
    }
    
    // Paginacion y filtrado
    function getAllFilterAndLimit($field, $value, $limit, $offset){
        $query = $this->db->prepare("SELECT * FROM products WHERE $field = ? LIMIT $offset, $limit");
        $query->execute([$value]);
      
        $products = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de productos
 
        return $products;
    }

    //Ordenado, paginado y filtrado
    function getAllOptions($field, $value, $limit, $offset, $sort, $order){
        $query = $this->db->prepare("SELECT * FROM products WHERE $field = ? ORDER BY $sort $order LIMIT $offset, $limit");
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