console.log("xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");

//carga la imagen de file en el elemento src imagen
function mostrarFoto(file, imagen) {
  var reader = new FileReader();
  reader.addEventListener("load", function () {
    imagen.src = reader.result;
  });
  reader.readAsDataURL(file);
}

// envia formulario
async function registrarAsync(evento) {
  try {
    evento.preventDefault();
    console.log("registrarAsync 1");
    let url = evento.target.getAttribute("action");
    let data = new FormData(evento.target);
    let init = {
      url: url,
      method: "post",
      body: data,
    };
    console.log("registrarAsync 2", url, data);

    let request0 = new Request(url, init);

    const response = await fetch(request0);

    if (!response.ok) {
      throw Error(response.statusText);
    }
    const result = await response.text();

    alert("El registro se ha guardado correctamente.");

    // window.location.href =
    //   window.location.href + "?action=my_datos_vlogamer&proceso=listar";

    // var r = confirm("¿Quieres registrar más usuarios?");
    // if (r == true) {
    //   window.location.href =
    //     window.location.href + "?action=my_datos_vlogamer&proceso=registro";
    // } else {
    //   window.location.href =
    //     window.location.href + "?action=my_datos_vlogamer&proceso=listar";
    // }

    console.log("Correcto devuelvo:", result);
  } catch (error) {
    console.log(error);
    alert("Hubo un error al guardar el registro. Inténtelo de nuevo.");
  }
}

//escuchamos evento selección nuevo fichero.
function ready() {
  console.log("eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee");
  var fichero = document.querySelector("#foto");
  var imagen = document.querySelector("#img_foto");
  fichero.addEventListener("change", function (event) {
    mostrarFoto(this.files[0], imagen);
  });

  var myForm = document.querySelector("#myFormAsync");
  myForm.addEventListener("submit", function (event) {
    console.log("aded submit event");
    registrarAsync(event);
  });

  // if (document.forms.length > 0) {
  // document.forms[0].addEventListener("submit", function (event) {
  //     enviaForm(event);
  // })
  // }
}
ready();
