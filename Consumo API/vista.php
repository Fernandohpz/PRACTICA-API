<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Usuarios</title>
  <link rel="stylesheet" href="./lib/bootstrap-5.3.7-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./lib/notyf/notyf.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">

  <h1 class="text-center mb-4">Gestión de Usuarios</h1>

  <!-- Buscar usuario -->
  <div class="input-group mb-4">
    <input type="number" id="buscarId" class="form-control" placeholder="Buscar usuario por ID...">
    <button id="btnBuscar" class="btn btn-primary">Buscar</button>
    <button id="btnRecargar" class="btn btn-secondary">Recargar lista</button>
  </div>

  <!-- Tabla de usuarios -->
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Lista de usuarios</h5>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Edad</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaUsuarios"></tbody>
      </table>
    </div>
  </div>
</div>

<!--  Modal editar usuario -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditar">
        <div class="modal-header">
          <h5 class="modal-title">Editar usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editId">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" id="editNombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" id="editEmail" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Edad</label>
            <input type="number" id="editEdad" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="./lib/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
<script src="./lib/notyf/notyf.min.js"></script>
<script>
const API_URL = 'http://localhost/API/usuarios/';
const notyf = new Notyf({ duration: 3000 });

// Cargar todos los usuarios
async function cargarUsuarios() {
  try {
    const res = await fetch(`${API_URL}index.php`);
    const data = await res.json();
    const tabla = document.getElementById('tablaUsuarios');
    tabla.innerHTML = '';

    data.forEach(u => {
      tabla.innerHTML += `
        <tr>
          <td>${u.id}</td>
          <td>${u.nombre}</td>
          <td>${u.email}</td>
          <td>${u.edad}</td>
          <td>
            <button class="btn btn-sm btn-warning me-2" onclick="editarUsuario(${u.id})">Editar</button>
            <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(${u.id})">Eliminar</button>
          </td>
        </tr>`;
    });
  } catch (err) {
    console.error(err);
    notyf.error('Error al obtener usuarios');
  }
}

// Buscar usuario por ID
document.getElementById('btnBuscar').addEventListener('click', async () => {
  const id = document.getElementById('buscarId').value.trim();
  if (!id) return notyf.error('Ingresa un ID');

  try {
    const res = await fetch(`${API_URL}get.php?id=${id}`);
    const u = await res.json();
    const tabla = document.getElementById('tablaUsuarios');
    tabla.innerHTML = '';

    if (u.message) {
      notyf.error('Usuario no encontrado');
      return;
    }

    tabla.innerHTML = `
      <tr>
        <td>${u.id}</td>
        <td>${u.nombre}</td>
        <td>${u.email}</td>
        <td>${u.edad}</td>
        <td>
          <button class="btn btn-sm btn-warning me-2" onclick="editarUsuario(${u.id})">Editar</button>
          <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(${u.id})">Eliminar</button>
        </td>
      </tr>`;
  } catch (err) {
    notyf.error('Error al buscar usuario');
  }
});

// Recargar lista completa
document.getElementById('btnRecargar').addEventListener('click', cargarUsuarios);

// Abrir modal de edición con datos del usuario
async function editarUsuario(id) {
  try {
    const res = await fetch(`${API_URL}get.php?id=${id}`);
    const u = await res.json();
    if (u.message) return notyf.error('Usuario no encontrado');

    document.getElementById('editId').value = u.id;
    document.getElementById('editNombre').value = u.nombre;
    document.getElementById('editEmail').value = u.email;
    document.getElementById('editEdad').value = u.edad;

    new bootstrap.Modal(document.getElementById('modalEditar')).show();
  } catch (err) {
    notyf.error('Error al cargar usuario');
  }
}

// Guardar cambios
document.getElementById('formEditar').addEventListener('submit', async e => {
  e.preventDefault();

  const data = {
    id: document.getElementById('editId').value,
    nombre: document.getElementById('editNombre').value,
    email: document.getElementById('editEmail').value,
    edad: document.getElementById('editEdad').value
  };

  try {
    const res = await fetch(`${API_URL}update.php`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const json = await res.json();

    notyf.success(json.message);
    cargarUsuarios();
    bootstrap.Modal.getInstance(document.getElementById('modalEditar')).hide();
  } catch (err) {
    notyf.error('Error al actualizar usuario');
  }
});

//  Eliminar usuario
async function eliminarUsuario(id) {
  if (!confirm('¿Seguro que deseas eliminar este usuario?')) return;

  try {
    const res = await fetch(`${API_URL}delete.php`, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id })
    });
    const json = await res.json();

    notyf.success(json.message);
    cargarUsuarios();
  } catch (err) {
    notyf.error('Error al eliminar usuario');
  }
}

// Inicialización
cargarUsuarios();
</script>

</body>
</html>

