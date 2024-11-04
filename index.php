<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD LP3</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/datatables.min.css" rel="stylesheet">

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>
</head>

<body>
    <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CRUD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Articulos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Categorias</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Reportes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Exportar a Excel</a></li>
                            <li><a class="dropdown-item" href="#">Exportar a Word</a></li>
                            <li><a class="dropdown-item" href="#">Exportar a Pdf</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-bordered table-hover" id="tablaarticulos">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Modificar</th>
                            <th>Borrar</th>
                        </tr>
                    </thead>
                </table>
                <button class="btn btn-sm btn-primary" id="BotonAgregar">Agregar artículo</button>
            </div>
        </div>

        <!-- Formulario (Agregar,  Modificar) -->

        <div class="modal fade" id="FormularioArticulo" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Modal de registro</h3>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="Codigo">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Descripción:</label>
                                <input type="text" id="Descripcion" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Precio:</label>
                                <input type="number" id="Precio" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" id="ConfirmarAgregar" class="btn btn-success">Agregar</button>
                            <button type="button" id="ConfirmarModificar" class="btn btn-primary">Modificar</button>
                            <button type="button" id="CancelarAgregar" class="btn btn-warning">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-body-tertiary text-center fixed-bottom">
        <!-- Grid container -->
        <div class="container p-4"></div>
        <!-- Grid container -->
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: #000; color:#fff;">
            © 2024 Copyright - UTIC LP3
        </div>
        <!-- Copyright -->
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let tabla1 = $("#tablaarticulos").DataTable({
                "ajax": {
                    url: "php/articulos.php?accion=listar",
                    dataSrc: ""
                },
                "columns": [{
                    "data": "codigo"
                },
                {
                    "data": "descripcion"
                },
                {
                    "data": "precio"
                },
                {
                    "data": null,
                    "orderable": false
                },
                {
                    "data": null,
                    "orderable": false
                }
                ],
                "columnDefs": [{
                    targets: 3,
                    "defaultContent": "<button class='btn btn-sm btn-primary botonmodificar'>Modificar</button>",
                    data: null
                }, {
                    targets: 4,
                    "defaultContent": "<button class='btn btn-sm btn-danger botonborrar'>Borrar</button>",
                    data: null
                }],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                }
            });

            // Eventos de botones de la aplicación
            $('#BotonAgregar').click(function () {
                $('#ConfirmarAgregar').show();
                $('#ConfirmarModificar').hide();
                limpiarFormulario();
                $("#FormularioArticulo").modal('show');
            });

            $('#CancelarAgregar').click(function () {
                $("#FormularioArticulo").modal('hide');
            });

            $('#ConfirmarAgregar').click(function () {
                $("#FormularioArticulo").modal('hide');
                let registro = recuperarDatosFormulario();
                agregarRegistro(registro);
            });

            $('#ConfirmarModificar').click(function () {
                $("#FormularioArticulo").modal('hide');
                let registro = recuperarDatosFormulario();
                modificarRegistro(registro);
            });

            $('#tablaarticulos tbody').on('click', 'button.botonmodificar', function () {
                $('#ConfirmarAgregar').hide();
                $('#ConfirmarModificar').show();
                let registro = tabla1.row($(this).parents('tr')).data();
                recuperarRegistro(registro.codigo);
            });

            $('#tablaarticulos tbody').on('click', 'button.botonborrar', function () {
                if (confirm("¿Realmente quiere borrar el artículo?")) {
                    let registro = tabla1.row($(this).parents('tr')).data();
                    borrarRegistro(registro.codigo);
                }
            });

            // Funciones que interactúan con el formulario de entrada de datos
            function limpiarFormulario() {
                $('#Codigo').val('');
                $('#Descripcion').val('');
                $('#Precio').val('');
            }

            function recuperarDatosFormulario() {
                let registro = {
                    codigo: $('#Codigo').val(),
                    descripcion: $('#Descripcion').val(),
                    precio: $('#Precio').val()
                };
                return registro;
            }

            // Funciones para comunicarse con el servidor vía ajax
            function agregarRegistro(registro) {
                $.ajax({
                    type: 'POST',
                    url: 'php/articulos.php?accion=agregar',
                    data: registro,
                    success: function (msg) {
                        tabla1.ajax.reload();
                    },
                    error: function () {
                        alert("Hay un problema");
                    }
                });
            }

            function borrarRegistro(codigo) {
                $.ajax({
                    type: 'GET',
                    url: 'php/articulos.php?accion=borrar&codigo=' + codigo,
                    data: '',
                    success: function (msg) {
                        tabla1.ajax.reload();
                    },
                    error: function () {
                        alert("Hay un problema");
                    }
                });
            }

            function recuperarRegistro(codigo) {
                $.ajax({
                    type: 'GET',
                    url: 'php/articulos.php?accion=consultar&codigo=' + codigo,
                    data: '',
                    success: function (datos) {
                        $('#Codigo').val(datos[0].codigo);
                        $('#Descripcion').val(datos[0].descripcion);
                        $('#Precio').val(datos[0].precio);
                        $("#FormularioArticulo").modal('show');
                    },
                    error: function () {
                        alert("Hay un problema");
                    }
                });
            }

            function modificarRegistro(registro) {
                $.ajax({
                    type: 'POST',
                    url: 'php/articulos.php?accion=modificar&codigo=' + registro.codigo,
                    data: registro,
                    success: function (msg) {
                        tabla1.ajax.reload();
                    },
                    error: function () {
                        alert("Hay un problema");
                    }
                });
            }
        });
    </script>
</body>

</html>