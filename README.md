<em> Mateando API </em>

# ENDPOINTS

## Servicios GET

- `GET /products`
Accede al listado completo de productos que existen en la base de datos 'db_tpe' dentro de la tabla 'products'. 

### Ordenamiento y filtado

Agregando ```?sort=FIELD&order=ORDERTYPE``` permite ordenar la lista de manera ascendente o descendente por un campo. El campo se debe especificar en el ```sort``` y el orden deseado en el ```order```. Solo es posible ordenar por campos existentes en la tabla de la base de datos, de lo contrario existira un ```400 - Bad Request```.

Ejemplo ```GET /products?sort=sale&order=desc``` este punto de entrada traera el listado de productos ordenado descendentemente por el campo `sale`, lo que significa que se listaran primero todos los productos que esten en oferta (sale = 1) y luego los que no (sale = 0).

- `GET /products/:ID`
Este endpoint permite acceder a un producto especifico de la tabla dado un id particular. En caso de que el id sea incorrecto, se producira un error ```404 Not Found```. 

Ejemplo ```GET /products/123```

## Servicio POST
- `POST /products`
Este servicio permite agregar un nuevo producto a la tabla a traves del body de `postman`

Ejemplo 
    ```POST /products```
    {
        "name": "Prueba POST",
        "id_brand": 1,
        "description": "Descripcion",
        "price": 100,
        "sale": 1
    }

## Servicio PUT
- `PUT /products/:ID`
Por medio de este endpoint se puede hacer una modificacion a un producto existente en la tabla de la base de datos. Para especificar el producto a modificar se captura el ID que viene por parametro. Este ID debe existir en la tabla de lo contrario se arroja un status ```404 Not Found```.
La modificacion al igual que con `POST` se hace a traves del body de `postman`, respetando la estructura del objeto.

Ejemplo
    ```PUT /products/123```
    Los nuevos valores de los campos del producto 123 seran los siguientes:

    {
        "name": "Prueba PUT",
        "id_brand": 1,
        "description": "Descripcion editada",
        "price": 50,
        "sale": 0
    }


## Servicio DELETE
- `DELETE /products/:ID`
Este servicio elimina el producto de la tabla cuyo id sea el que se pase por parametro. De no existir tal parametro ocurrira un ```404 Not found```

Ejemplo
    ```DELETE /products/123```
    De existir ese producto la respuesta sera:
    "Eliminado con exito del producto con id = 123"
