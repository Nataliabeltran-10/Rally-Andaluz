document.getElementById('formRegistro').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita que el formulario se envíe de la manera tradicional
    
    // Obtener los datos del formulario
    const nombre = document.getElementById('nombre').value;
    const email = document.getElementById('email').value;
    const contraseña = document.getElementById('contraseña').value;
    const rol = document.getElementById('rol').value;
  
    // Crear un objeto con los datos
    const datos = {
      nombre: nombre,
      email: email,
      contraseña: contraseña,
      rol: rol
    };
  
    // Enviar los datos al servidor con fetch
    fetch('registro.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(datos) // Convertir los datos a formato JSON
    })
    .then(response => response.json()) // Convertir la respuesta a JSON
    .then(data => {
      if (data.success) {
        document.getElementById('mensaje').textContent = "¡Registro exitoso!";
      } else {
        document.getElementById('mensaje').textContent = "Error al registrar: " + data.message;
      }
    })
    .catch(error => {
      document.getElementById('mensaje').textContent = "Error en la conexión: " + error;
    });
  });
  