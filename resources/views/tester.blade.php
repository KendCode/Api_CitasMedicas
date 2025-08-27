{{-- resources/views/tester.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Tester - Citas Médicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <h1 class="mb-4 text-center">API Tester - Citas Médicas</h1>

        {{-- Registro --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Registro</div>
            <div class="card-body">
                <input class="form-control mb-2" type="text" id="regName" placeholder="Nombre">
                <input class="form-control mb-2" type="email" id="regEmail" placeholder="Email">
                <input class="form-control mb-2" type="password" id="regPassword" placeholder="Password">
                <button class="btn btn-success" onclick="register()">Registrar</button>
                <pre class="mt-2 bg-light p-2 border" id="regResult"></pre>
            </div>
        </div>

        {{-- Login --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Login</div>
            <div class="card-body">
                <input class="form-control mb-2" type="email" id="loginEmail" placeholder="Email">
                <input class="form-control mb-2" type="password" id="loginPassword" placeholder="Password">
                <button class="btn btn-success" onclick="login()">Login</button>
                <pre class="mt-2 bg-light p-2 border" id="loginResult"></pre>
            </div>
        </div>

        {{-- Citas --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Gestión de Citas</div>
            <div class="card-body">
                <input class="form-control mb-2" type="date" id="citaFecha" placeholder="Fecha">
                <input class="form-control mb-2" type="time" id="citaHora" placeholder="Hora">
                <input class="form-control mb-2" type="text" id="citaMedico" placeholder="Doctor">
                <textarea class="form-control mb-2" id="citaDesc" placeholder="Descripción"></textarea>
                <button class="btn btn-primary mb-2" onclick="crearCita()">Crear Cita</button>
                <button class="btn btn-info mb-2" onclick="listarCitas()">Actualizar Lista</button>
                <div id="citasContainer" class="row mt-3"></div>
            </div>
        </div>
    </div>

    <script>
        let token = '';
        let citas = [];

        // Registrar usuario
        async function register() {
            const res = await fetch('{{ url('/api/register') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: document.getElementById('regName').value,
                    email: document.getElementById('regEmail').value,
                    password: document.getElementById('regPassword').value
                })
            });

            const data = await res.json();
            document.getElementById('regResult').innerText = JSON.stringify(data, null, 2);
        }

        // Login y guardar token
        async function login() {
            const res = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: document.getElementById('loginEmail').value,
                    password: document.getElementById('loginPassword').value
                })
            });
            const data = await res.json();
            if (res.ok) token = data.access_token;
            document.getElementById('loginResult').innerText = JSON.stringify(data, null, 2);
            listarCitas();
        }

        // Crear cita
        async function crearCita() {
            if (!token) {
                alert('Primero haz login');
                return;
            }
            const res = await fetch('/api/citas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    fecha: document.getElementById('citaFecha').value,
                    hora: document.getElementById('citaHora').value,
                    medico: document.getElementById('citaMedico').value,
                    descripcion: document.getElementById('citaDesc').value
                })
            });
            await res.json();
            listarCitas();
        }

        // Listar citas
        async function listarCitas() {
            if (!token) return;
            const res = await fetch('/api/citas', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            citas = await res.json();
            renderCitas();
        }

        // Renderizar citas
        function renderCitas() {
            const container = document.getElementById('citasContainer');
            container.innerHTML = '';
            citas.forEach(cita => {
                const card = document.createElement('div');
                card.className = 'col-md-4 mb-3';
                card.innerHTML = `
        <div class="card border-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Dr. ${cita.medico}</h5>
                <p><strong>Fecha:</strong> ${cita.fecha}</p>
                <p><strong>Hora:</strong> ${cita.hora}</p>
                <p><strong>Descripción:</strong> ${cita.descripcion || '-'}</p>
                <button class="btn btn-warning btn-sm me-1" onclick="editarCita(${cita.id})">Editar</button>
                <button class="btn btn-danger btn-sm" onclick="borrarCita(${cita.id})">Eliminar</button>
            </div>
        </div>`;
                container.appendChild(card);
            });
        }

        // Editar cita
        function editarCita(id) {
            const cita = citas.find(c => c.id === id);
            if (!cita) return;
            document.getElementById('citaFecha').value = cita.fecha;
            document.getElementById('citaHora').value = cita.hora;
            document.getElementById('citaMedico').value = cita.medico;
            document.getElementById('citaDesc').value = cita.descripcion;
            const btn = document.querySelector('button.btn-primary');
            btn.innerText = 'Actualizar Cita';
            btn.onclick = () => actualizarCita(id);
        }

        // Actualizar cita
        async function actualizarCita(id) {
            const res = await fetch(`/api/citas/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    fecha: document.getElementById('citaFecha').value,
                    hora: document.getElementById('citaHora').value,
                    medico: document.getElementById('citaMedico').value,
                    descripcion: document.getElementById('citaDesc').value
                })
            });
            await res.json();
            const btn = document.querySelector('button.btn-primary');
            btn.innerText = 'Crear Cita';
            btn.onclick = crearCita;
            listarCitas();
        }

        // Borrar cita
        async function borrarCita(id) {
            if (!confirm('¿Seguro que deseas eliminar esta cita?')) return;
            await fetch(`/api/citas/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            listarCitas();
        }
    </script>
</body>

</html>
