
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

$(document).on("click", ".btnEliminarUsuarios", function () {
  let id_usuario = $(this).attr("id_usuario");
  Swal.fire({
    title: "Está seguro de eliminar el usuario?",
    text: "Sino lo está puede cancelar la acción",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, eliminar usuario",
  }).then(function (result) {
    if (result.isConfirmed) {
      window.location = "index.php?pagina=usuarios&id_usuario=" + id_usuario;
    }
  });
});