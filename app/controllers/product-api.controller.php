<?php
    require_once './app/models/product.model.php';
    require_once './app/views/api.view.php';
    require_once './app/helpers/auth-api.helper.php';
    
    class ProductApiController {
        private $model;
        private $view;
        private $authHelper;
        
        private $data;

        public function __construct(){
            $this->model = new ProductModel();
            $this->view = new ApiView();
            $this->authHelper = new AuthApiHelper();
            
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
            $orderType = array('asc','desc');
            // Total de registros de la tabla
            $total = $this->model->count();
            
            //Defino valores por defecto
            $sort = $fields[0];
            $order = $orderType[0];
            $limit = $total;
            $offset = 0;
            $field = null;
            $value = null;
            
            //Filtrado
            if ((isset($_GET['field'])&&isset($_GET['value']))){
                $value = $_GET['value'];
                // Verifica que lo que se haya recibido por parametro GET pertenezca al array de opciones posibles
                if (in_array($_GET['field'], $fields))
                    $field = $_GET['field'];
                else 
                    return $this->view->response("$field no es un campo existente en la tabla", 400);   
            }

            //Ordenado por un campo asc o desc
            if ((isset($_GET['sort'])&&isset($_GET['order']))){
                // Verifica que lo que se haya recibido por parametro GET pertenezca al array de opciones posibles
                if (in_array($sort, $fields)&&in_array($order, $orderType)){
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];   
                } 
                else 
                    return $this->view->response("La ruta es incorrecta", 400);
            } 
            
            //Paginacion
            if ((isset($_GET['limit'])&&(isset($_GET['pag'])))){
                if((int)$_GET['limit'] <= $total){
                    $limit = (int)$_GET['limit'];
                    $pages = round($total/$limit);
                    if ((int)$_GET['pag']<=$pages){
                        $page = (int)$_GET['pag'];                     
                        $offset = $limit * ($page-1);
                    } else {
                        return $this->view->response("Las paginas existentes son $pages", 400);
                    }
                } else {
                        return $this->view->response("El limite debe ser menor a $total", 400);
                }
            }
            
            //Hago los llamados al modelo, en caso de que no haya entrado a los if anteriores, van los valores por defecto
            if ($field!=null&&$value!=null)
                if($field=='price')
                    $products = $this->model->getAllFilterPrice($field, $value, $limit, $offset, $sort, $order);
                else
                    $products = $this->model->getAllFilter($field, $value, $limit, $offset, $sort, $order);
            else 
                $products = $this->model->getAll($limit, $offset, $sort, $order);
            
            //Hago el llamado a la vista
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
            if(!$this->authHelper->isLoggedIn()){
                $this->view->response("No estas logeado", 401);
                return;
            }
    
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
            if(!$this->authHelper->isLoggedIn()){
                $this->view->response("No estas logeado", 401);
                return;
            }
    
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
            if(!$this->authHelper->isLoggedIn()){
                $this->view->response("No estas logeado", 401);
                return;
            }
    
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