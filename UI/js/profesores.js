// Funciones JavaScript para la gestión de profesores
let modal = document.getElementById('modalProfesor');
let form = document.getElementById('formProfesor');

function abrirModalCrear() {
    document.getElementById('modalTitulo').textContent = 'Nuevo Profesor';
    form.reset();
    document.getElementById('profesorId').value = '';
    modal.style.display = 'block';
}

function cerrarModal() {
    modal.style.display = 'none';
}

function editarProfesor(id) {
    // Aquí harías una llamada AJAX para obtener los datos del profesor
    fetch(`../../Controladores/profesor_ajax.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalTitulo').textContent = 'Editar Profesor';
                document.getElementById('profesorId').value = data.profesor.id;
                document.getElementById('legajo').value = data.profesor.legajo;
                document.getElementById('nombre').value = data.profesor.nombre;
                document.getElementById('apellido').value = data.profesor.apellido;
                document.getElementById('email').value = data.profesor.email || '';
                document.getElementById('telefono').value = data.profesor.telefono || '';
                modal.style.display = 'block';
            } else {
                alert('Error al cargar los datos del profesor');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del profesor');
        });
}

function guardarProfesor(event) {
    event.preventDefault();
    
    const formData = new FormData(form);
    const datos = Object.fromEntries(formData);
    
    const url = datos.id ? '../../Controladores/profesor_ajax.php?action=update' 
                         : '../../Controladores/profesor_ajax.php?action=create';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            cerrarModal();
            location.reload(); // Recargar la página para ver los cambios
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el profesor');
    });
}

function eliminarProfesor(id) {
    if (confirm('¿Está seguro de que desea eliminar este profesor?')) {
        fetch(`../../Controladores/profesor_ajax.php?action=delete&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el profesor');
            });
    }
}

function asignarCursos(id) {
    // Redirigir a la página de asignación de cursos
    window.location.href = `asignar_cursos.php?profesor_id=${id}`;
}

// Cerrar modal al hacer click fuera
window.onclick = function(event) {
    if (event.target == modal) {
        cerrarModal();
    }
}