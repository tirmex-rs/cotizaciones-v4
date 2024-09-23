<?php

include ('../resources/php/conexion.php');
require_once('../fpdf/fpdf.php');
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
                        <h3>Cotizaciones</h3>
                        <button type="button" class="btn btn-success" target="_blank" id="boton" onclick="accionPDF()">PDF</button>

                    </div>
                    <h3>Seleccione un cliente</h3>
                    <?php
                    $sql = "SELECT * FROM tbl_clientes";
                    $result = mysqli_query($conexion, $sql);
                    
                    if (mysqli_num_rows($result) > 0) {
                        echo "<select class='form-control'>";
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row["id_cliente"] . "'>" . $row["nombre_cliente"] . "</option>";
                        }
                        echo "</select>";
                    } else {
                        echo "No results found.";
                    }
                    ?>
                    <h3 class="mt-3">Agregue productos</h3>
                    <?php
                    $sql = "SELECT * FROM tbl_productos";
                    $result = mysqli_query($conexion, $sql);
                    
                    if (mysqli_num_rows($result) > 0) {
                        echo "<select class='form-control'>";
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row["Id_producto"] . "'>" . $row["nombre_producto"] . "</option>";
                        }
                        echo "</select>";
                    } else {
                        echo "No results found.";
                    }
?>
<h3 class="mt-3">Cantidad de productos</h3>
<input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Ingrese cantidad">

<h3 class="mt-3">Fecha</h3>
<input type="date" class="form-control" id="cantidad" name="cantidad" placeholder="Ingrese cantidad">
<button type="submit" name="accion" value="Agregar" class="btn btn-success mt-3">Agregar</button>

<h4 class="text-center mt-8">Lista de productos</h4>

<?php

?>

                </div>
            </main>
        </div>
    </div>
    <script>
  function accionPDF(){
    window.location.href = 'pdf.php';
  }
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="../resources/js/script.js"></script>
    
</body>

</html>