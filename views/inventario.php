<?php

include('../resources/php/conexion.php');
include('../fpdf/fpdf.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <link rel="stylesheet" href="../resources/css/init.css">
    <title>Tirmex | Cotizaciones</title>
</head>

<body>
    <?php

    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
    $accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";
    $txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
    $txtDescripcion = (isset($_POST['txtDescripcion'])) ? $_POST['txtDescripcion'] : "";
    $txtCantidad = (isset($_POST['txtCantidad'])) ? $_POST['txtCantidad'] : "";
    $txtCategoria = (isset($_POST['txtCategoria'])) ? $_POST['txtCategoria'] : "";
    $txtTalla = (isset($_POST['txtTalla'])) ? $_POST['txtTalla'] : "";
    $txtPrecio = (isset($_POST['txtPrecio'])) ? $_POST['txtPrecio'] : "";
    $txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
    $txtOldImagen = (isset($_POST['txtOldImagen'])) ? $_POST['txtOldImagen'] : "";

    switch ($accion) {
        case 'Agregar':
            // Correct the SQL query by using `?` as placeholders
            $queryProductos = $conexion->prepare("INSERT INTO tbl_productos (nombre_producto, categoria_producto, descripcion_producto, imagen_producto, precio_producto, cantidad_producto) VALUES (?, ?, ?, ?, ?, ?);");

            $date = new DateTime();
            $ImgFileName = ($txtImagen != "") ? $date->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";
            $ImgTmp = $_FILES["txtImagen"]["tmp_name"];

            if ($ImgTmp != "") {
                move_uploaded_file($ImgTmp, "../resources/images/productos/" . $ImgFileName);
            }

            // Bind the parameters using `bind_param`. Make sure to specify the types (`s` for string)
            $queryProductos->bind_param('ssssss', $txtNombre, $txtCategoria, $txtDescripcion, $ImgFileName, $txtCantidad, $txtPrecio);

            // Execute the prepared statement
            $queryProductos->execute();

            // Redirect to productosindex.php
           
            header('Location: inventario.php');
            break;
    }

    switch ($accion) {
        case 'Seleccionar':
            $queryProductos = $conexion->prepare("SELECT * FROM tbl_productos WHERE Id_producto = ?");
            $queryProductos->bind_param('i', $txtID); // 'i' para entero
            $queryProductos->execute();
            $result = $queryProductos->get_result();
            $productos = $result->fetch_assoc();
            $txtNombre = $productos['nombre_producto'];
            $txtDescripcion = $productos['descripcion_producto'];
            $txtCantidad = $productos['cantidad_producto'];
            $txtPrecio = $productos['precio_producto'];
            $txtImagen = $productos['imagen_producto'];
            $txtOldImagen = $productos['imagen_producto'];
            break;
    
        case 'Eliminar':
            $queryProductos = $conexion->prepare("SELECT imagen_producto FROM tbl_productos WHERE Id_producto = ?");
            $queryProductos->bind_param('i', $txtID);
            $queryProductos->execute();
            $result = $queryProductos->get_result();
            $productos = $result->fetch_assoc();
    
            if (isset($productos["imagen_producto"]) && ($productos["imagen_producto"] != "imagen.jpg")) {
                if (file_exists("images/productos/" . $productos["imagen_producto"])) {
                    unlink("images/productos/" . $productos["imagen_producto"]);
                }
            }
            $queryProductos = $conexion->prepare("DELETE FROM tbl_productos WHERE Id_producto = ?");
            $queryProductos->bind_param('i', $txtID);
            $queryProductos->execute();
            header('Location: inventario.php');
            break;
    
        case 'Modificar':
            $queryProductos = $conexion->prepare("UPDATE tbl_productos SET nombre_producto = ?, categoria_producto = ?, descripcion_producto = ?,cantidad_producto = ?, precio_producto = ?, imagen_producto = ? WHERE Id_producto = ?");
            $queryProductos->bind_param('sssissi', $txtNombre, $txtCategoria, $txtDescripcion, $txtCantidad, $txtPrecio, $txtImagen, $txtID);
            $queryProductos->execute();
    
            if ($txtImagen != "") {
                $date = new DateTime();
                $ImgFileName = $date->getTimestamp() . "_" . $_FILES["txtImagen"]["name"];
                $ImgTmp = $_FILES["txtImagen"]["tmp_name"];
                move_uploaded_file($ImgTmp, "images/productos/" . $ImgFileName);
    
                // Update the image path
                $queryProductos = $conexion->prepare("SELECT imagen_producto FROM tbl_productos WHERE Id_producto = ?");
                $queryProductos->bind_param('i', $txtID);
                $queryProductos->execute();
                $result = $queryProductos->get_result();
                $productos = $result->fetch_assoc();
    
                if (isset($productos["imagen_producto"]) && ($productos["imagen_producto"] != "imagen.jpg")) {
                    if (file_exists("images/productos/" . $productos["imagen_producto"])) {
                        unlink("images/productos/" . $productos["imagen_producto"]);
                    }
                }
    
                $queryProductos = $conexion->prepare("UPDATE tbl_productos SET imagen_producto = ? WHERE Id_producto = ?");
                $queryProductos->bind_param('si', $ImgFileName, $txtID);
                $queryProductos->execute();
            } else {
                $queryProductos = $conexion->prepare("UPDATE tbl_productos SET imagen_producto = ? WHERE Id_producto = ?");
                $queryProductos->bind_param('si', $txtOldImagen, $txtID);
                $queryProductos->execute();
            }
    
            header('Location: inventario.php');
            break;
    
        default:
            // Handle default case if needed
            break;
    }
    ?>
    <div class="wrapper">
        <!-- Aqui empieza el sidebar -->
        <aside id="sidebar">
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="#"><img src="../resources/images/logo-removed.png" height="80px" width="140px" alt=""></a>
                </div>
                <!-- Navegacion del  sidebar -->

                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Actividades
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="fa-solid fa-list pe-2"></i>
                            Perfil
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#pages"
                            aria-expanded="false" aria-controls="pages">
                            <i class="fa-regular fa-file-lines pe-2"></i>
                            Paginas
                        </a>
                        <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="cotizaciones.php" class="sidebar-link">Cotizaciones</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="clientes.php" class="sidebar-link">Clientes</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="inventario.php" class="sidebar-link">Inventario</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard"
                            aria-expanded="false" aria-controls="dashboard">
                            <i class="fa-solid fa-sliders pe-2"></i>
                            Dashboard
                        </a>
                        <ul id="dashboard" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Graficas</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link"></a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#auth"
                            aria-expanded="false" aria-controls="auth">
                            <i class="fa-regular fa-user pe-2"></i>
                            Auth
                        </a>
                        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Login</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Register</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-header">
                        Multi Level Nav
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi"
                            aria-expanded="false" aria-controls="multi">
                            <i class="fa-solid fa-share-nodes pe-2"></i>
                            Multi Level
                        </a>
                        <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#multi-two" aria-expanded="false" aria-controls="multi-two">
                                    Two Links
                                </a>
                                <ul id="multi-two" class="sidebar-dropdown list-unstyled collapse">
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">Link 1</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">Link 2</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
        <!-- Aqui empieza toda la pagina que no parte del side -->
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <!-- Button for sidebar toggle -->
                <button class="btn" type="button" data-bs-theme="dark">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </nav>
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <div class="mb-3 d-flex justify-content-between">
                        <h3>Inventario</h3>
                        
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
    Open modal
  </button>

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          Modal body..
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>



                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <h4 class="card-title">Productos form</h4>
                            <p class="card-description">

                            </p>
                            <form method="post" enctype="multipart/form-data" class="forms-sample">
                                <div class="form-group mt-3 mb-3">
                                    <label for="exampleInputName1">ID</label>
                                    <input type="hidden" class="form-control" id="txtID" value="<?php echo $txtID; ?>" placeholder="Name" name="txtID">
                                </div>
                                <div class="form-group mt-3 mb-3">
                                    <input type="hidden" class="form-control" id="txtID" value="<?php echo $txtID; ?>" placeholder="Name">
                                    <label for="exampleInputName1">Nombre del producto</label>
                                    <input type="text" class="form-control" id="txtNombre" value="<?php echo $txtNombre; ?>" placeholder="Name" name="txtNombre">
                                </div>
                                <div class="form-group mt-3 mb-3">
                                    <label for="exampleInputEmail3">Categoria</label>
                                    <input type="text" class="form-control" id="txtCategoria" value="<?php echo $txtCategoria; ?>" placeholder="Categoria" name="txtCategoria">
                                </div>
                                <div class="form-group mt-3 mb-3">
                                    <label for="exampleInputEmail3">Descripcion</label>
                                    <input type="text" class="form-control" id="txtDescripcion" value="<?php echo $txtDescripcion; ?>" placeholder="Descripcion" name="txtDescripcion">
                                </div>
                                <div class="form-group mt-3 mb-3">
                                    <label for="exampleInputPassword4">Cantidad</label>
                                    <input type="text" class="form-control" id="txtCantidad" value="<?php echo $txtCantidad; ?>" placeholder="Cantidad en stock" name="txtCantidad">
                                </div>
                                <div class="form-group mt-3 mb-3">
                                    <label for="exampleTextarea1">Precio</label>
                                    <input type="text" class="form-control" id="txtPrecio" value="<?php echo $txtPrecio; ?>" placeholder="Precio" name="txtPrecio">
                                </div>
                                <div class="form-group mt-3 mb-3">
                                    <label for="exampleTextarea1">Imagen</label>
                                    <?php echo $txtImagen; ?>
                                    <input type="hidden" class="form-control" id="txtImagen" value="<?php echo $txtImagen; ?>" name="txtOldImagen">
                                    <input type="file" name="txtImagen" class="form-control" placeholder="Imagen">

                                </div>
                                <div class="flex justify-content-center">
                                <button type="submit" name="accion" value="Agregar" class="btn btn-success  m-2">Agregar</button>

                                <button type="submit" name="accion" value="Modificar" class="btn btn-warning m-2 flex align-items-center">Modificar</button>
                                </div>
                            </form>
                        </form>
                    </div>
                    <div class="content-wrapper">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-xs-2">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Tabla productos</h4>
                                        <p class="card-description">
                                            Add class <code></code>
                                        </p>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead></thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Nombre</th>
                                                    <th>Categoria</th>
                                                    <th>Descripcion</th>
                                                    <th>Stock</th>
                                                    <th>Precio con IVA</th>                                                    
                                                    <th>Imagen</th>
                                                </tr>
                                                </thead>
                                                <?php



                                                $queryProductos = $conexion->prepare("SELECT * FROM tbl_productos");
                                                $queryProductos->execute();
                                                $result = $queryProductos->get_result();
                                                $listProductos = $result->fetch_all(MYSQLI_ASSOC);

                                                ?>

                                                <tbody>
                                                    <?php foreach ($listProductos as $productos) { ?>
                                                        <tr>
                                                            <td> <?php echo $productos['Id_producto']; ?> </td>
                                                            <td> <?php echo $productos['nombre_producto']; ?></td>
                                                            <td> <?php echo $productos['categoria_producto']; ?></td>
                                                            <td> <?php echo $productos['descripcion_producto']; ?></td>
                                                            <td> <?php echo $productos['cantidad_producto']; ?></td>
                                                            <td> <?php echo  '$'.  $productos['precio_producto']; ?></td>
                                                            <td> <img src="../resources/images/productos/<?php echo $productos['imagen_producto'] ?>" width="100" alt=""></td>
                                                            <td>
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="txtID" value="<?php echo $productos["Id_producto"] ?>">
                                                                    <input type="submit" name="accion" value="Seleccionar" class="btn btn-success m-2">
                                                                    <input type="submit" onclick="Eliminar()" name="accion" value="Eliminar" class="btn btn-danger m-2">


                                                                </form>

                                                            </td>
                                                        </tr>
                                                    <?php } ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="../resources/js/script.js"></script>
    <script>
      function Eliminar() {
        
      }
    </script>
</body>

</html>