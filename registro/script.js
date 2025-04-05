document.getElementById("registration-form").addEventListener("submit", function(event) {
  let nombre = document.getElementById("nombre").value;
  let email = document.getElementById("email").value;
  let contraseña = document.getElementById("contraseña").value;

  if (nombre.trim() === "" || email.trim() === "" || contraseña.trim() === "") {
      alert("Por favor complete todos los campos.");
      event.preventDefault(); // Evitar el envío del formulario si falta información
  }
});
