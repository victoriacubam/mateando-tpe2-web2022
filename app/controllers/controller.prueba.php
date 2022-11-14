<?php

function getProducts($params = null){
    $products = null;
    $fields = array('id', 'name', 'id_brand', 'description', 'price', 'sale');
    $orderType = array('desc', 'asc');
    $total = $this->model->count();

    //Ordenado
    if ((isset($_GET['sort'])&&isset($_GET['order']))){

        $sort = $_GET['sort'];
        $order = $_GET['order'];
        
        if (in_array($sort, $fields)&&in_array($order, $orderType))  // Verifica que lo que se haya recibido por parametro GET pertenezca al array de opciones posibles
            $products = $this->model->getAllInOrder($sort, $order);   
        else 
            return $this->view->response("La ruta es incorrecta", 400);

    } 
    //Paginacion
    else if ((isset($_GET['limit'])&&(isset($_GET['pag'])))){
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
    else if ((isset($_GET['field'])&&isset($_GET['value']))){
        $field = $_GET['field'];
        $value = $_GET['value'];
        if (in_array($field, $fields)){
            if($field=='price'){
                $products = $this->model->getAllFilterLowerPrice($field, $value);
            } else {
                $products = $this->model->getAllFilter($field, $value);
            }
        }
    //Orden y paginacion
    } else if ((isset($_GET['sort'])&&isset($_GET['order']))&&(isset($_GET['limit'])&&(isset($_GET['pag'])))){
        $sort = $_GET['sort'];
        $order = $_GET['order'];
        $limit = (int)$_GET['limit'];
        $page = (int)$_GET['pag'];
        $pages = round($total/$limit);

        if (in_array($sort, $fields)&&in_array($order, $orderType)&&(($limit <= $total) && ($page<=$pages))){
            $offset = $limit * ($page-1);
            $products = $this->model->getAllInOrderAndLimit($limit, $offset, $sort, $order);
        }
    } 

    // Paginacion y filtrado
    else if ((isset($_GET['limit'])&&(isset($_GET['pag'])))&&(isset($_GET['field'])&&isset($_GET['value']))){
        $limit = (int)$_GET['limit'];
        $page = (int)$_GET['pag'];
        $pages = round($total/$limit);

        if (in_array($field, $fields)&&(($limit <= $total) && ($page<=$pages))){
            $products = $this->model->getAllFilterAndLimit($field, $value, $limit, $offset);
        }
    } 

    //Orden y Filtrado
    else if((isset($_GET['sort'])&&isset($_GET['order']))&&(isset($_GET['field'])&&isset($_GET['value']))){
        $field = $_GET['field'];
        $value = $_GET['value'];
        $sort = $_GET['sort'];
        $order = $_GET['order'];
        if (in_array($field, $fields)&&in_array($sort, $fields)&&in_array($order, $orderType)){
            $products = $this->model->getAllFilterAndOrder($field, $value, $sort, $order);
        } 
    }
    //Orden, paginacion y filtrado
    else if((isset($_GET['sort'])&&isset($_GET['order']))&&(isset($_GET['limit'])&&(isset($_GET['pag'])))&&(isset($_GET['field'])&&isset($_GET['value']))){
        $sort = $_GET['sort'];
        $order = $_GET['order'];
        $limit = (int)$_GET['limit'];
        $page = (int)$_GET['pag'];
        $pages = round($total/$limit);
        $field = $_GET['field'];
        $value = $_GET['value'];
        
        if (in_array($field, $fields)&&($limit <= $total) && ($page<=$pages)&&in_array($sort, $fields)&&in_array($order, $orderType)) {
            $products = $this->model->getAllOptions($field, $value, $limit, $offset, $sort, $order);
        }
    }
    
    if ($products)
        return $this->view->response($products);
    else 
        return $this->view->response("No hay productos disponibles", 404);
}