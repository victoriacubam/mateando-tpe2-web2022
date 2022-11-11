<?php
    require_once './app/models/product.model.php';
    require_once './app/views/api.view.php';
    
    class ProductApiController {
        private $model;
        private $view;
        
        private $data;

        public function __construct(){
            $this->model = new ProductModel();
            $this->view = new ApiView();
            
            //lee el body del request
            $this->data = file_get_contents("php://input");
        }

        private function getData(){
            return json_decode($this->data);
        }
    
        function getProducts($params = null){
            $products = $this->model->getAll();
            if ($products)
                return $this->view->response($products);
            else 
                return $this->view->response("No hay productos disponibles", 404);
        }
        
        function getProduct($params = null){
            $id = $params[':ID'];
            $product = $this->model->get($id);
            
            if ($product)
                $this->view->response($product);
            else 
                $this->view->response("El producto con el id = $id no existe en el catalogo", 404);
        }
        
        public function deleteProduct($params = null){
            $id = $params[':ID'];
            $product = $this->model->get($id);
            if ($product) {
                $this->model->delete($id);
                $this->view->response("Eliminado con exito del producto con id = $id");
            } else {
                $this->view->response("El producto con el id = $id no existe dentro del catalogo", 404);
            }
        }
        
        public function insertProduct($params = null){
            $product = $this->getData();
            if (empty($product->name)||empty($product->id_brand)||empty($product->description)||empty($product->price)){
                $this->view->response("Complete los datos", 400);
            } else {                
                $id = $this->model->insert($product->name, $product->id_brand, $product->description, $product->price, $product->sale);
                $product = $this->model->get($id);
                $this->view->response($product, 201);
            }
        }

        public function editProduct($params = null){
            $id = $params[':ID'];
            $product = $this->model->get($id);
            if($product){
                $product = $this->getData();
                if (empty($product->name)||empty($product->id_brand)||empty($product->description)||empty($product->price)){
                    $this->view->response("Complete los datos", 400);
                } else {                
                    $this->model->edit($product->name, $product->id_brand, $product->description, $product->price, $product->sale=1, $id);
                    $product = $this->model->get($id);
                    $this->view->response($product, 201);
                }
            } else {
                $this->view->response("El producto con con id = $id no existe en el catalogo", 404);
            }
        }
        
        //En el caso que la ruta sea incorrecta
        function error(){
            return $this->view->response("La ruta es incorrecta", 400);
        }
        
    }