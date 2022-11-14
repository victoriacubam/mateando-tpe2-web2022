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
            $products = null;
            // Campos de la tabla
            $fields = array('id', 'name', 'id_brand', 'description', 'price', 'sale');
            // Tipos de orden
            $orderType = array('desc', 'asc');
            // Total de registros de la tabla
            $total = $this->model->count();
        
            //Ordenado por un campo asc o desc
            if ((isset($_GET['sort'])&&isset($_GET['order']))){
        
                $sort = $_GET['sort'];
                $order = $_GET['order'];
                
                // Verifica que lo que se haya recibido por parametro GET pertenezca al array de opciones posibles
                if (in_array($sort, $fields)&&in_array($order, $orderType))  
                    $products = $this->model->getAllInOrder($sort, $order);   
                else 
                    return $this->view->response("La ruta es incorrecta", 400);
        
            } 
            
            //Paginacion
            if ((isset($_GET['limit'])&&(isset($_GET['pag'])))){
                $total = $this->model->count();
                $limit = (int)$_GET['limit'];
                $page = (int)$_GET['pag'];
                $pages = round($total/$limit);
                
                if(($limit <= $total) && ($page<=$pages)){
                    $offset = $limit * ($page-1);
                    $products = $this->model->getAllLimit($limit, $offset);
                } else {
                    return $this->view->response("El limite debe ser menor a $total y las paginas existentes son $pages", 400);
                }
        
            } 
            
            //Filtrado
            if ((isset($_GET['field'])&&isset($_GET['value']))){
                $field = $_GET['field'];
                $value = $_GET['value'];
                if (in_array($field, $fields)){
                    if($field=='price'){
                        $products = $this->model->getAllFilterLowerPrice($field, $value);
                    } else {
                        $products = $this->model->getAllFilter($field, $value);
                    }
                } else {
                    return $this->view->response("$field no es un campo existente en la tabla", 400);
                }
            }
            
            //Si no hay ningun criterio en el endpoint
            if (empty($_GET['field'])&&empty($_GET['value'])&&empty($_GET['limit'])&&empty($_GET['pag'])&&empty($_GET['order'])&&empty($_GET['sort'])) {
                $products = $this->model->getAll();
            }
            
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
        
        function deleteProduct($params = null){
            $id = $params[':ID'];
            $product = $this->model->get($id);
            if ($product) {
                $this->model->delete($id);
                $this->view->response("Eliminado con exito del producto con id = $id");
            } else {
                $this->view->response("El producto con el id = $id no existe dentro del catalogo", 404);
            }
        }
        
        function insertProduct($params = null){
            $product = $this->getData();
            if (empty($product->name)||empty($product->id_brand)||empty($product->description)||empty($product->price)){
                $this->view->response("Complete los datos", 400);
            } else {                
                $id = $this->model->insert($product->name, $product->id_brand, $product->description, $product->price, $product->sale);
                $product = $this->model->get($id);
                $this->view->response($product, 201);
            }
        }

        function editProduct($params = null){
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