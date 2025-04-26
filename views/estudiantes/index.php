<?php require_once VIEWS_PATH . '/layout/main.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Gestión de Estudiantes</h2>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Estudiantes</h6>
            <button class="btn btn-primary btn-sm" onclick="mostrarModal('crear')">
                <i class="fas fa-plus"></i> Nuevo Estudiante
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaEstudiantes" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Fecha Nac.</th>
                            <th>Sexo</th>
                            <th>Grado</th>
                            <th>Carrera</th>
                            <th>Jornada</th>
                            <th>Sección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $estudiantes->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['dni']; ?></td>
                            <td><?php echo $row['nombres']; ?></td>
                            <td><?php echo $row['apellidos']; ?></td>
                            <td><?php echo $row['fecha_nac']; ?></td>
                            <td><?php echo $row['sexo']; ?></td>
                            <td><?php echo $row['grado']; ?></td>
                            <td><?php echo $row['carrera']; ?></td>
                            <td><?php echo $row['jornada']; ?></td>
                            <td><?php echo $row['seccion']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="mostrarModal('editar', <?php echo $row['id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="confirmarEliminar(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Incluir modal -->
<?php include '_modal.php'; ?>

<!-- Incluir scripts necesarios -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaEstudiantes').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
        }
    });
});

function mostrarModal(accion, id = null) {
    const modal = new bootstrap.Modal(document.getElementById('estudianteModal'));
    const tituloModal = document.getElementById('modalTitle');
    const formulario = document.getElementById('estudianteForm');
    
    if(accion === 'crear') {
        tituloModal.textContent = 'Nuevo Estudiante';
        formulario.reset();
        formulario.setAttribute('data-action', 'crear');
    } else {
        tituloModal.textContent = 'Editar Estudiante';
        formulario.setAttribute('data-action', 'editar');
        
        // Cargar datos del estudiante
        fetch(`api/estudiantes.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('id').value = data.id;
                document.getElementById('dni').value = data.dni;
                document.getElementById('nombres').value = data.nombres;
                document.getElementById('apellidos').value = data.apellidos;
                document.getElementById('fecha_nac').value = data.fecha_nac;
                document.getElementById('sexo').value = data.sexo;
                document.getElementById('grado').value = data.grado;
                document.getElementById('carrera').value = data.carrera;
                document.getElementById('jornada').value = data.jornada;
                document.getElementById('seccion').value = data.seccion;
            });
    }
    
    modal.show();
}

function confirmarEliminar(id) {
    if(confirm('¿Está seguro que desea eliminar este estudiante?')) {
        eliminarEstudiante(id);
    }
}

function eliminarEstudiante(id) {
    fetch('api/estudiantes.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar el estudiante');
    });
}

document.getElementById('estudianteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = this.getAttribute('data-action');
    const estudiante = {};
    
    formData.forEach((value, key) => {
        estudiante[key] = value;
    });
    
    let url = 'api/estudiantes.php';
    let method = 'POST';
    
    if(action === 'editar') {
        method = 'PUT';
    }
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(estudiante)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if(data.message.includes('correctamente')) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
});
</script>