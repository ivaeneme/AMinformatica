
$(document).on("click", ".btnEliminarServicio", function () {
  let idServicio = $(this).attr("idServicio");
  Swal.fire({
    title: "Está seguro de eliminar el servicio?",
    text: "Sino lo está puede cancelar la acción",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, eliminar servicio",
  }).then(function (result) {
    if (result.isConfirmed) {
      window.location = "index.php?pagina=servicios&idServicio=" + idServicio;
    }
  });
});



$(document).on("click", ".btnEliminarProductos", function () {
  let idMercaderia = $(this).attr("idMercaderia");

  Swal.fire({
    title: "Está seguro de eliminar el producto?",
    text: "Sino lo está puede cancelar la acción",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, eliminar producto",
  }).then(function (result) {
    if (result.isConfirmed) {
      window.location = "index.php?pagina=productos&idMercaderia=" + idMercaderia;
    }
  });
});

$(document).on("click", ".btnEliminarClientes", function () {
  let idCliente = $(this).attr("idCliente");
  Swal.fire({
    title: "Está seguro de eliminar el cliente?",
    text: "Sino lo está puede cancelar la acción",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, eliminar cliente",
  }).then(function (result) {
    if (result.isConfirmed) {
      window.location = "index.php?pagina=clientes&idCliente=" + idCliente;
    }
  });
});

$(document).on("click", ".btnEliminarPagos", function () {
  let id_pago = $(this).attr("id_pago");
  Swal.fire({
    title: "Está seguro de eliminar el pago?",
    text: "Sino lo está puede cancelar la acción",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, eliminar pago",
  }).then(function (result) {
    if (result.isConfirmed) {
      window.location = "index.php?pagina=pagos&id_pago=" + id_pago;
    }
  });
});

$(document).ready(function() {
  // Cuando se hace click en el botón eliminar
  $(document).on("click", ".btnEliminarUsuarios", function(e) {
    e.preventDefault(); // evitar acción por defecto (que siga el href)

    let id_usuario = $(this).attr("id_usuario");

    Swal.fire({
      title: '¿Estás seguro de eliminar este usuario?',
      text: "¡No podrás revertir esta acción!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirigir a la URL que elimina el usuario y vuelve a la lista
        window.location.href = `index.php?controlador=usuarios&accion=eliminar&id_usuario=${id_usuario}`;
      }
    });
  });
});


