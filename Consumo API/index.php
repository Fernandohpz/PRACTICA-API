<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuario</title>

    <link rel="stylesheet" href="./lib/bootstrap-5.3.7-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./lib/notyf/notyf.min.css">
</head>

<body>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h2>Crear empleado</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email">
                    </div>
                    <div class="mb-3">
                        <label for="edad" class="form-label">Edad</label>
                        <input type="number" class="form-control" id="edad">
                    </div>
                    <div class="btn-group">
                        <button id="btnEnviar" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <script src="./lib/bootstrap-5.3.7-dist/js/bootstrap.min.js"></script>
    <script src="./lib/notyf/notyf.min.js"></script>

 <script>
    const urlApi = 'http://localhost/API/usuarios/';
    const endpointCrear = "create.php";

    // Duración en milisegundos (5 segundos)
    const notyf = new Notyf({
        duration: 5000
    });

    // Consumo del enpoint de crear en nuestra API
    document.getElementById("btnEnviar").addEventListener("click", async () => {
        let nombre = document.getElementById("nombre").value;
        let email = document.getElementById("email").value;
        let edad = document.getElementById("edad").value;

        if (!nombre || !email) {
            notyf.error({
                message: 'Faltan datos'
            });

            return;
        }

        try {
            const response = await fetch(`${urlApi}${endpointCrear}`, {
                method: 'POST',
                body: JSON.stringify({
                    nombre: nombre,
                    email: email,
                    edad: parseInt(edad)
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const json = await response.json();

            if (json.codeServer == 400 || json.codeServer == 500) {
                notyf.error({

                    message: json.message
                });

                return;
            }

            notyf.success({
                message: json.message
            });
        } catch (error) {
            notyf.error({
                message: '¡Ocurrió un error!'
            });
        }
    });
</script>

</body>

</html>