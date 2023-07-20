const register = document.getElementById("button");
register.addEventListener("click", () => {
  var status;
  const formData = new FormData(document.querySelector("form"));
  console.log(formData.get("pwd"));
  fetch("http://localhost:8888/project/server/Models/User.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => {
      status = res.status;
      return res.text();
    })
    .then((data) => {
      if (status == 200) {
        console.log(data);
        displaySuccessAlert("User created successfully! Welcome!");
        setTimeout(() => {
          location.href = "/project/frontend/pages/index.php";
        }, 1000);
      } else {
        const errorMessage = data;
        console.log(errorMessage);  
        displayErrorAlert(errorMessage);
      }
    })
    .catch((err) => {
      displayErrorAlert(err);
      console.log(err);
    });
});

const username = "rodrids01";
const selectElement = document.getElementById("floatingCountry");
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

// Função para obter o código do país a partir do nome
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

function displaySuccessAlert(message) {
  const alertContainer = document.getElementById("alertContainer");
  const alertMessage = document.getElementById("alertMessage");

  if (alertContainer.style.backgroundColor === "#B31B1B") {
    alertContainer.style.backgroundColor = "darkseagreen";
  }
  alertMessage.textContent = message;
  alertContainer.classList.add("show");
}
function displayErrorAlert(errorMessage) {
  const alertContainer = document.getElementById("alertContainer");
  const alertMessage = document.getElementById("alertMessage");

  alertMessage.textContent = errorMessage;
  alertContainer.classList.add("show");

  // Check if the background color is "darkseagreen" and change it to "red"
  if (alertContainer.style.backgroundColor === "darkseagreen") {
    alertContainer.style.backgroundColor = "#B31B1B";
  }
}
