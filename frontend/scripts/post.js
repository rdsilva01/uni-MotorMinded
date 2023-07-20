// Atualizar a imagem de visualização quando o usuário selecionar um arquivo
const inputImagem = document.getElementById("image");
const imagemPreview = document.getElementById("preview");

inputImagem.addEventListener("change", function () {
  const file = inputImagem.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function () {
      imagemPreview.src = reader.result;
      imagemPreview.style.display = "visible";
    };
    reader.readAsDataURL(file);
  } else {
    imagemPreview.src = "../images/default-img.gif";
    imagemPreview.style.display = "visible";
  }
});

// para tornar a imagem clicavel
document.getElementById("preview").addEventListener("click", function () {
  document.getElementById("image").click();
});

// enviar o post para a base de dados
const register = document.querySelector('input[type="submit"]');
register.addEventListener("click", () => {
  //e.preventDefault(); // Impedir o envio padrão do formulário
  var status;
  const formData = new FormData(document.querySelector("form"));
  console.log(formData.get("image"));
  console.log(formData.get("post_title"));

  // Enviar o formulário com fetch
  fetch(
    "http://localhost:8888/project/server/Models/Post.php?crud_req=create",
    {
      method: "POST",
      body: formData,
    }
  )
    .then((res) => {
      status = res.status;
      return res.text();
    })
    .then((data) => {
      if (status == 200) {
        displaySuccessAlert("Uploaded!");
        setTimeout(() => {
          location.href = "/project/frontend/pages/home.php";
        }, 1500);
      } else {
        const errorMessage = data;
        displayErrorAlert(errorMessage);
      }
    })
    .catch((err) => {
      displayErrorAlert(err);
      console.log(err);
    });
});

function displaySuccessAlert(message) {
  const alertContainer = document.getElementById("alertContainer");
  const alertMessage = document.getElementById("alertMessage");

  if (alertContainer.classList.contains("alert-danger")) {
    alertContainer.classList.remove("alert-danger");
    alertContainer.classList.add("alert-primary");
  } else {
    alertContainer.classList.add("alert-primary");
  }

  alertMessage.textContent = message;
  alertContainer.classList.add("show");
}

function displayErrorAlert(errorMessage) {
  const alertContainer = document.getElementById("alertContainer");
  const alertMessage = document.getElementById("alertMessage");

  alertContainer.classList.add("alert-danger");
  alertMessage.textContent = "Error";
}
