# Mateando API

Endpoint de la API : **http://localhost/TPE2-WEB2-2022/api/products**

# Endpoints:

## Servicios GET

- `GET /products`: Accede al listado completo de productos que existen en la base de datos 'db_tpe' dentro de la tabla 'products'. 

    - #### Ordenamiento por campos
        
        - `GET /products?sort=FIELD&order=ORDERTYPE`  


        Agregando `?sort=FIELD&order=ORDERTYPE` permite ordenar la lista de manera ascendente o descendente por un campo. El campo se debe especificar en el `sort` y el orden deseado en el `order`. Solo es posible ordenar por campos existentes en la tabla de la base de datos, de lo contrario existira un `400 - Bad Request`.


        ***Ejemplo*** ```GET /products?sort=sale&order=desc```  
        Este punto de entrada traera el listado de productos ordenado descendentemente por el campo `sale`, lo que significa que se listaran primero todos los productos que esten en oferta (sale = 1) y luego los que no (sale = 0).

    - #### Paginacion

        - `GET /products?limit=value&pag=value`  

        A traves de los Query Params se pasa un limite que no puede exceder a la totalidad de registros de la tabla. Este limite establece la cantidad total de productos que se muestran por pagina, y para "recorrer" los registros se va aumentando el valor de la pagina. Si el limite no esta dentro de los posibles se producira un error `400 Bad Request`.

        ***Ejemplo*** `GET /products?limit=2&pag=1`


            [
                {
                    "id": 1,
                    "name": "Producto 1",
                    "id_brand": 1,
                    "description": "Descripcion",
                    "price": 900,
                    "img": "img/63476aa0203af.jpg",
                    "sale": 1
                },
                {
                    "id": 2,
                    "name": "Productos 2",
                    "id_brand": 1,
                    "description": "Descripcion",
                    "price": 850,
                    "img": "img/63476aa6a7222.jpg",
                    "sale": 0
                }
            ]

    - #### Filtrado
        - `GET /products?field=value&value=value`

        Estableciendo un campo de la tabla es posible filtrar por algun valor en especifico. Es necesario que el campo que se pasa por el Query Param sea uno existente en la tabla, de lo contrario surgira un error `400 Bad Request`. Y en el caso de que el valor que se busca filtrar no exista, ocurre un error `404 Not Found`. 

        ***Ejemplo*** `GET /products?field=sale&value=1`  
        Esta peticion traera todos los productos que se encuentren en oferta, es decir, todos aquellos que tengan true (1) en el campo sale.


- `GET /products/:ID`: Este endpoint permite acceder a un producto especifico de la tabla dado un id particular. En caso de que el id sea incorrecto, se producira un error `404 Not Found`. 

- ***Ejemplo*** `GET /products/123`  


        {
            "id": 123,
            "name": "Producto prueba GET",
            "id_brand": 4,
            "description": "Descripcion",
            "price": 100,
            "img": null,
            "sale": 0
        }

***

## Servicio POST
- `POST /products`: Este servicio permite agregar un nuevo producto a la tabla a traves del body de `postman`

- ***Ejemplo*** `POST /products`  


        {
            "name": "Prueba POST",
            "id_brand": 1,
            "description": "Descripcion",
            "price": 100,
            "sale": 1
        }

***

## Servicio PUT
- `PUT /products/:ID`  
    Por medio de este endpoint se puede hacer una modificacion a un producto existente en la tabla de la base de datos. Para especificar el producto a modificar se captura el ID que viene por parametro. Este ID debe existir en la tabla de lo contrario se arroja un status `404 Not Found`.
    La modificacion al igual que con `POST` se hace a traves del body de `postman`, respetando la estructura del objeto.

- ***Ejemplo*** `PUT /products/123`  


    Los nuevos valores de los campos del producto 123 seran los siguientes:  


    ```{
        "name": "Prueba PUT",
        "id_brand": 1,
        "description": "Descripcion editada",
        "price": 50,
        "sale": 0
    }```

***

## Servicio DELETE
- `DELETE /products/:ID`
    Este servicio elimina el producto de la tabla cuyo id sea el que se pase por parametro. De no existir tal parametro ocurrira un `404 Not found`

- ***Ejemplo*** `DELETE /products/123`


    De existir ese producto la respuesta sera:
    "Eliminado con exito del producto con id = 123"

***

## Autorizacion 
- `GET /auth/token`

El usuario que consume la api tiene libertad de ver todos los registros, de manera ordenada, filtrada y/o paginada. Pero para hacer modificaciones (**PUT POST**) debe estar **autorizado**, para esto, desde este endpoint `GET /auth/token` debe hacer una autorizacion en postman de tipo **basic**, ingresar su **email** y **contraseña**.  

Estos datos deben coincidir con los registros de la base de datos en la tabla *users*, si estos datos son erroneos o estan incompletos se produce un error `401 Not Found Unauthorized`.  

Si la autenticacion es correcta, devuelve un *token*, el cual a traves del endopoint `PUT /products/:ID` O `POST /products` se utiliza en la autorizacion de tipo **Bearer Token** y si es correcto el token, se ejecuta el PUT o POST.
