/*=============================================
Función Sweetalert
=============================================*/
function fncSweetAlert(type, text, url) {
  switch (type) {
    /*=============================================
   Cuando ocurre un error
   =============================================*/
    case "error":
      if (url == null) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: text,
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: text,
        }).then((result) => {
          if (result.value) {
            window.open(url, "_top");
          }
        });
      }

      break;
    /*=============================================
   Cuando es correcto
   =============================================*/
    case "success":
      if (url == null) {
        Swal.fire({
          icon: "success",
          title: "OK",
          text: text,
        });
      } else {
        Swal.fire({
          icon: "success",
          title: "OK",
          text: text,
        }).then((result) => {
          if (result.value) {
            window.open(url, "_top");
          }
        });
      }
      break;
    /*=============================================
Cuando es una advertencia
=============================================*/
    case "warning":
      if (url == null) {
        Swal.fire({
          icon: "warning",
          title: "Atención",
          text: text,
          confirmButtonColor: "#f39c12", // Amarillo
        });
      } else {
        Swal.fire({
          icon: "warning",
          title: "Atención",
          text: text,
          confirmButtonColor: "#f39c12",
        }).then((result) => {
          if (result.value) {
            window.open(url, "_top");
          }
        });
      }
      break;
    /*=============================================
    Cuando es un mensaje informativo
    =============================================*/
    case "info":
      if (url == null) {
        Swal.fire({
          icon: "info",
          title: "Información",
          text: text,
          confirmButtonColor: "#3498db", // Azul
        });
      } else {
        Swal.fire({
          icon: "info",
          title: "Información",
          text: text,
          confirmButtonColor: "#3498db",
        }).then((result) => {
          if (result.value) {
            window.open(url, "_top");
          }
        });
      }
      break;


    case "loading":
      Swal.fire({
        allowOutsideClick: false,
        icon: "info",
        text: text,
      });
      Swal.showLoading();
      break;
    /*=============================================
   Cuando necesitamos cerrar la alerta suave
   =============================================*/
    case "close":
      Swal.close();
      break;
    /*=============================================
   Cuando solicitamos confirmación
   =============================================*/
    case "confirm":
      return new Promise((resolve) => {
        Swal.fire({
          text: text,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          cancelButtonText: "Cancelar",
          confirmButtonText: "Si eliminar!",
        }).then(function (result) {
          resolve(result.value);
        });
      });
      break;
  }
}
