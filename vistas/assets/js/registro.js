<script>
document.querySelector("form").addEventListener("submit", function(e) {
    const pass = document.querySelector("input[name='contrasena']").value;
    const confirm = document.querySelector("input[name='confirmar_contrasena']").value;
    if (pass !== confirm) {
        e.preventDefault();
        alert("Las contraseñas no coinciden");
    }
});
</script>
