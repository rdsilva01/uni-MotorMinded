const update = document.querySelector('input[type="submit"]');
update.addEventListener("click", (event) => {
  event.preventDefault();

  const form = document.querySelector("form");
  const data = new FormData(form);

  fetch(
    "http://localhost:8888/project/server/Models/User.php?crud_req=update",
    {
      method: "POST",
      credentials: "include",
      mode: "cors",
      body: data,
    }
  )
    .then((res) => {
      if (res.ok) {
        return res.text();
      } else {
        throw new Error("Erro na solicitação");
      }
    })
    .then((data) => {
      displaySuccessAlert(data);
      setTimeout(() => {
        location.href = "./profile.php?id=" + userID;
      }, 1500);
    })
    .catch((error) => {
      displayErrorAlert(error);
      console.error(error);
    });
});

// Função para carregar os dados do usuário na tela
function loadUserProfile(user_ID) {
  fetch(
    `http://localhost:8888/project/server/Models/User.php?crud_req=profile&id=${user_ID}`,
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      document.getElementById("firstName").value = data.first_name;
      document.getElementById("LastName").value = data.last_name;
      document.getElementById("email").value = data.email;
      document.getElementById("country").value = data.country;
      document.getElementById("about").value = data.about_me;
      document.getElementById("profileImagePreview").src =
        `../images/` + data.image_path;
    })
    .catch((error) => {
      console.error(error);
    });
}

// Chame a função para carregar o perfil do usuário quando a página for carregada
window.addEventListener("load", loadUserProfile(userID));

// função para obter o select com os países
const username = "rodrids01";
const selectElement = document.getElementById("country");
const flagContainer = document.getElementById("flagContainer");
let countryData = [];

fetch(`http://api.geonames.org/countryInfoJSON?username=${username}`)
  .then((response) => response.json())
  .then((data) => {
    countryData = data.geonames;

    countryData.forEach((country) => {
      const option = document.createElement("option");
      option.value = country.countryName;
      option.text = country.countryName;

      selectElement.appendChild(option);
    });
  })
  .catch((error) => {
    console.error("Erro ao obter a lista de países:", error);
  });

function getCountryCode(countryName) {
  const country = countryData.find((c) => c.countryName === countryName);
  return country ? country.countryCode : null;
}

// Evento de escuta para capturar a seleção do país
selectElement.addEventListener("change", function () {
  const selectedCountryName = this.value;
  const countryCode = getCountryCode(selectedCountryName);

  if (countryCode) {
    flagContainer.innerHTML = `<img src="https://flagsapi.com/${countryCode}/shiny/16.png">`;
  } else {
    flagContainer.innerHTML = "";
  }
});

var textarea = document.getElementById("about");
var counter = document.getElementById("charCounter");

textarea.addEventListener("input", function () {
  var text = textarea.value;
  var numCharacteres = text.length;
  var limit = 128;

  if (numCharacteres > limit) {
    // Limita o texto ao número máximo de caracteres
    text = text.substring(0, limit);
    textarea.value = text;
    numCharacteres = limit;
  }
  counter.textContent = numCharacteres + "/" + limit + " characters";
});

// Obtém o elemento de entrada do arquivo e a visualização da imagem
const input = document.getElementById("profileImage");
const preview = document.getElementById("profileImagePreview");

// Adiciona um evento de alteração ao elemento de entrada do arquivo
input.addEventListener("change", function () {
  const file = this.files[0];

  if (file) {
    // Cria um objeto URL para a imagem selecionada
    const imageURL = URL.createObjectURL(file);

    // Atualiza a visualização da imagem
    preview.src = imageURL;
  }
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
