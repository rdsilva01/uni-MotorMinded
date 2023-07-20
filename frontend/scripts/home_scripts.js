const profile = document.querySelector(".profile");

const newpost = document.getElementById("newpost");

const cancel = document.getElementById("cancel");

cancel.addEventListener("click", () => {
  var formulario = document.getElementById("formulario");
  formulario.style.display = "none";
  var newpost = document.getElementById("newpost");
  newpost.style.display = "block";
});

newpost.addEventListener("click", exibirFormulario);

function exibirFormulario() {
  var formulario = document.getElementById("formulario");
  formulario.style.display = "block";
  var newpost = document.getElementById("newpost");
  newpost.style.display = "none";
}

const logout = document.querySelector(".logout");

const user_ID = localStorage.getItem("user_ID");
profile.addEventListener(
  "click",
  () => (location.href = `./profile.php?id=${userID}`)
);

logout.addEventListener("click", () => {
  fetch("http://localhost:8888/project/server/Models/User.php", {
    credentials: "include",
    mode: "cors",
  })
    .then((res) => res.text())
    .then((data) => {
      displaySuccessAlert("Goodbye! See you soon!");
      setTimeout(() => {
        location.href = "index.php";
      }, 1000);
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

// temperatura
const apiKey = "9aa033c68ee601f2ffff66fd3e1300a7";

function obterDadosMeteorologia(lat, lon) {
  const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`;
  //const url = `https://api.weatherbit.io/v2.0/current?lat=${lat}&lon=${lon}&key=${API_KEY}&include=minutely`;

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      // Processar a resposta da API e extrair as informações relevantes
      const temperatura = Math.round(data.main.temp);
      const condicoes = data.weather[0].description;
      const vento = data.wind.speed;
      const wicon = data.weather[0].icon;
      const humidity = data.main.humidity;
      const timestamp = data.dt * 1000; // Multiplica por 1000 para converter em milissegundos
      const dataAtual = new Date(timestamp);
      const hora = dataAtual.getHours();
      const minutos = dataAtual.getMinutes();
      //  const segundos = dataAtual.getSeconds();

      const temperaturaf = Math.round((temperatura * 9) / 5 + 32);

      const iconUrl = `https://openweathermap.org/img/wn/${wicon}.png`;

      //const sunset = data.sunset;
      // const temperatura = data.temp;
      // const condicoes = data.weather.description;

      // Atualizar os elementos HTML com os resultados da meteorologia
      document.getElementById("time").innerHTML = `${hora}:${minutos}`;
      document.getElementById("condicoes").innerHTML = `${condicoes}`;
      document.getElementById(
        "temperatura"
      ).innerHTML = `${temperatura}ºC/${temperaturaf}ºF`;
      document.getElementById("vento").innerHTML = `${vento} km/h`;
      document.getElementById("humidity").innerHTML = `${humidity}%`;
      document.getElementById("wicon").innerHTML = `<img src="${iconUrl}">`;
      // document.getElementById("condicoes").textContent = condicoes;
    })
    .catch((error) => {
      console.error("Erro ao obter dados de meteorologia:", error);
    });
}

function getCountryCode(countryName) {
  const country = countryData.find((c) => c.countryName === countryName);
  return country ? country.countryCode : null;
}

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
      // Trate os dados recebidos aqui
      const username = data.user_name;
      const firstName = data.first_name;
      const lastName = data.last_name;
      const aboutMe = data.about_me;
      const country = data.country;
      const profilePic = data.image_path;

      if (data.user_type === "admin") {
        document.getElementById(
          "admin"
        ).innerHTML = `<a href="./admindash.php" class="btn btn-warning flex-grow-1 fw-bold pe-3 ps-3 p-2 "><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
        <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
        <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"/>
      </svg></a>`;
      }
      document.getElementById(
        "nome"
      ).innerHTML = `${firstName} ${lastName}  <small class="text-body-secondary opacity-50">@<span id="nomeuser" class="mb-2"></span></small>`;
      document.getElementById("nomeuser").innerHTML = `${username}`;

      document.getElementById(
        "imgContainer"
      ).innerHTML = `<img class="img-thumbnail mb-2" src="${profilePic}" alt="Profile Picture" style="z-index: 1;  width: 150px; height: 150px; border-radius: 10%; object-fit: cover;">`;

      const username_geo = "rodrids01";

      function getCountries() {
        return fetch(
          `http://api.geonames.org/countryInfoJSON?username=${username_geo}`
        )
          .then((response) => response.json())
          .then((data) => {
            return data.geonames;
          })
          .catch((error) => {
            console.error(
              "Error while retrieving the list of countries:",
              error
            );
            return [];
          });
      }

      // Function to get the country code from the country name
      async function getCountryCode(countryName) {
        const countryData = await getCountries();
        console.log(countryData);
        const countrycode = countryData.find(
          (c) => c.countryName === countryName
        );
        console.log(countrycode);
        return countrycode ? countrycode.countryCode : null;
      }

      getCountryCode(country)
        .then((countryCode) => {
          console.log(country);
          document.getElementById(
            "nome"
          ).innerHTML = `<img src="https://flagsapi.com/${countryCode}/shiny/24.png" class="pb-1"> ${firstName} ${lastName}  <small class="text-body-secondary opacity-50">@<span id="nomeuser" class="mb-2"></span></small>`;
          document.getElementById("nomeuser").innerHTML = `${username}`;
        })
        .catch((error) => {
          console.error("Error while getting the country code:", error);
        });

      const apiKey_geo = "8fe1a61bd9534446ac79912a8aeb5267";

      fetch(
        `https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(
          country
        )}&key=${apiKey_geo}`
      )
        .then((response) => response.json())
        .then((data) => {
          // Verifique se os dados foram retornados com sucesso
          if (data.status.code === 200 && data.results.length > 0) {
            // Obtenha as coordenadas do primeiro resultado
            const latitude = data.results[0].geometry.lat;
            const longitude = data.results[0].geometry.lng;

            obterDadosMeteorologia(latitude, longitude);
            obterImagensEDarAppend();
            document.getElementById("country").innerHTML = `${country}`;

            // Faça o que você precisa com as coordenadas
            console.log("Latitude:", latitude);
            console.log("Longitude:", longitude);
          } else {
            console.error(
              "Erro ao obter as coordenadas do país:",
              data.status.message
            );
          }
        })

        .catch((error) => {
          console.error("Erro na solicitação:", error);
        });

      console.log(country);
    })
    .catch((error) => {
      console.error(error);
    });
}

window.addEventListener("load", loadUserProfile(userID));

function obterImagensEDarAppend() {
  const diretorioImagens = "../images/cars/"; // Substitua pelo diretório das imagens desejado
  const colCentro = document.getElementById("col_centro");

  fetch(diretorioImagens)
    .then((response) => response.text())
    .then((html) => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, "text/html");
      const imagens = Array.from(doc.querySelectorAll("img"));

      imagens.forEach((imagem) => {
        const src = imagem.getAttribute("src");

        const novaImagem = document.createElement("img");
        novaImagem.classList.add("w-100", "shadow-1-strong", "rounded", "mb-4");
        novaImagem.setAttribute("src", src);
        novaImagem.setAttribute("alt", "Imagem");

        colCentro.appendChild(novaImagem);
      });
    })
    .catch((error) => {
      console.error("Erro ao obter imagens do diretório:", error);
    });
}

// Path: project/frontend/scripts/post_scripts.js
const postsContainer = document.getElementById("posts_container");
let postsToShow = 6; // Número de posts a serem exibidos inicialmente
const postsToAdd = 6; // Número de posts a serem adicionados ao clicar no botão "Mostrar Mais"

function getPosts() {
  // Fazer a requisição ao modelo para obter os posts
  fetch("http://localhost:8888/project/server/Models/Post.php?crud_req=all", {
    method: "GET",
    credentials: "include",
    mode: "cors",
  })
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      if (data.length === 0) {
        setTimeout(() => {
          displayErrorAlert("Nenhum post encontrado para a data especificada.");
        }, 500);
      } else {
        postsContainer.innerHTML = "";

        const visiblePosts = data.slice(0, postsToShow); // Obter a fatia dos posts a serem exibidos inicialmente

        // Criar elementos HTML para cada post e adicioná-los ao container
        // Criar elementos HTML para cada post e adicioná-los ao container
        visiblePosts.forEach((dates) => {
          console.log(dates);
          const postElement = document.createElement("div");
          postElement.classList.add("post");
          postElement.innerHTML = `
            
          <div class="fluid card mb-5 ms-5 border-dark shadow rounded-top-4" style="width: 700px; position: relative; background-color: #1c2433;">
  <img src="../../.${dates.image_path}" class="card-img-top rounded-top-4 shadow custom-size-img" alt="...">
  <div class="card-body pt-4" >
    <div class="text-toggle text-white w-100 mb-5" style="display: none;">
      <p class="rounded-5 card-text opacity-100"><strong><span class="text-uppercase h1 fw-bold fst-italic">${dates.title}</span></strong><small class="text-secondary"> by <a href="./profile.php?id=${dates.user_id}" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">@${dates.user_name}</a></small></p>
      <p class="rounded-5 card-text opacity-100"><small class="text-secondary">model:</small> <span class="fw-bold">${dates.maker} ${dates.model}</span></p>
      <p class="rounded-5 card-text opacity-100"><small class="text-secondary">description:</small> ${dates.descrip}</p>
      <p class="rounded-5 card-text opacity-100"><small class="text-secondary">created: <span class="fw-lighter">${dates.created_at}</span></small></p>
    </div>
    <div class="btn-group position-absolute bottom-0 start-0 ms-3 pb-2" role="group" aria-label="Basic example">
      <a type="button" class="link-secondary link-offset-2 link-underline-opacity-0 link-underline-opacity-0-hover btntoggle" onclick="toggleContent(this)">
        <span opacity-100>Read More</span>
      </a>
    </div>
    <div class="btn-group position-absolute bottom-0 end-0 mb-2 me-3" role="group" aria-label="Basic example">
      <a href="../../.${dates.image_path}" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" download>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
          <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
          <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
        </svg>
        <span></span>
      </a>
    </div>
  </div>
</div>
    `;
          postsContainer.appendChild(postElement);
        });

        if (data.length > postsToShow) {
          // Se houver mais posts para exibir, adicionar o botão "Mostrar Mais"
          const showMoreButton = document.createElement("button");
          showMoreButton.classList.add(
            "btn",
            "btn-link",
            "btn-lg",
            "btn-block",
            "mb-5",
            "text-decoration-none",
            "text-uppercase",
            "fw-bold",
            "text-secondary",
            "shadow-1-strong"
          );
          showMoreButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-square pb-1" viewBox="0 0 16 16">
          <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg> Show More`;
          showMoreButton.addEventListener("click", showMorePosts);
          postsContainer.appendChild(showMoreButton);
        }
      }
    })
    .catch((error) => {
      setTimeout(() => {
        // displayErrorAlert("Erro ao obter os posts: " + error.message);
        console.log(error);
      }, 500);
    });
}

function showMorePosts() {
  postsToShow += postsToAdd; // Incrementar o número de posts a serem exibidos
  getPosts(); // Chamar novamente a função para obter e renderizar os posts
}

function displayErrorAlert(errorMessage) {
  // Exibir a mensagem de erro em um elemento HTML, como um alerta
  setTimeout(() => {
    console.error(errorMessage);
  }, 500);
}

window.addEventListener("load", getPosts);

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

function toggleContent(element) {
  var post = element.parentNode.parentNode;
  var content = post.getElementsByClassName("text-toggle")[0];
  var btn = post.getElementsByClassName("btntoggle")[0];
  if (content.style.display === "none") {
    content.style.display = "block";
    btn.innerHTML = "Hide";
  } else {
    content.style.display = "none";
    btn.innerHTML = "Read More";
  }
}

var textarea = document.getElementById("post_descrip");
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

function getAllRaces() {
  fetch("http://localhost:8888/project/server/Models/Race.php?crud_req=all", {
    method: "GET",
    credentials: "include",
    mode: "cors",
  })
    .then((res) => res.json())
    .then((data) => {
      console.log("Todas as corridas:", data);

      const racesContainer = document.getElementById("racesContainer");
      racesContainer.innerHTML = ""; // Limpar o conteúdo existente
      racesContainer.classList.add("scroll-animation");

      data.forEach((race, index) => {
        const raceElement = document.createElement("div");
        raceElement.classList.add("raceItem");
        const raceHTML = generateRaceHTML(race, index);
        raceElement.innerHTML = raceHTML; // Defina o HTML do card de corrida dentro do elemento da corrida
        racesContainer.appendChild(raceElement);
      });
    })
    .catch((err) => {
      console.error("Erro ao obter todas as corridas:", err);
    });
}

// Função para gerar o HTML de uma corrida
function generateRaceHTML(race, index) {
  const colors = ["bg-danger", "bg-white"]; // Array com as cores vermelho e branco
  const colorIndex = index % colors.length; // Índice baseado no parâmetro "index"

  const colorClass = colors[colorIndex];

  return `
  <div class="card shadow" style="max-width: 30rem;">
      <div class="card-header ${colorClass} fw-bold text-uppercase">
        ${race.name_event}
      </div>
      <div class="card-body">
        <h5 class="card-title f">Place: ${race.location}</h5>
        <p class="card-text">Date: ${race.date}</p>
      </div>
    </div>
    <hr>
  `;
}

window.addEventListener("load", getAllRaces);

function numPosts(userID) {
  fetch(
    `http://localhost:8888/project/server/Models/Post.php?crud_req=numPosts&id=${userID}`,
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      document.getElementById("numPosts").innerHTML = data;
      if (data && data.length > 0) {
        document.getElementById("numPosts").innerHTML = data;
      } else {
        setTimeout(() => {
          displayErrorAlert(
            "Nenhum post encontrado para o ID de usuário especificado."
          );
        }, 500);
      }
    })
    .catch((error) => {
      setTimeout(() => {
        // displayErrorAlert("Erro ao obter os posts: " + error.message);
        console.log(error);
      }, 500);
    });
}

window.addEventListener("load", numPosts(userID));

function getAllSounds() {
  return fetch(
    "http://localhost:8888/project/server/Models/Sound.php?crud_req=getAllSounds",
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      return data;
    })
    .catch((err) => {
      console.error("Erro ao obter todos os sons:", err);
      return [];
    });
}

const carSounds = [
  "720s.mp3",
  "911gt2rs.mp3",
  "amggtr.mp3",
  "audir8.mp3",
  "audittrs.mp3",
  "camarozl1.mp3",
  "continentalgt.mp3",
  "corvette.mp3",
  "ctsv.mp3",
  "e63.mp3",
  "ferrari458.mp3",
  "focusrs.mp3",
  "fordgt.mp3",
  "ftype.mp3",
  "giulia.mp3",
  "golfgti.mp3",
  "hellcat.mp3",
  "huracan.mp3",
  "lc500.mp3",
  "lexuslfa.mp3",
  "m4.mp3",
  "maseratigt.mp3",
  "nissangtr.mp3",
  "panamaraturbo.mp3",
  "shelbygt350.mp3",
  "stinger.mp3",
  "toyota86.mp3",
  "vantage2010.mp3",
  "velostern.mp3",
  "viper.mp3",
];

console.log(carSounds);
let currentSoundItem = null;

function exibirSonsCarros(sounds) {
  const soundList = document.getElementById("soundList");
  const audioSource = document.getElementById("audioSource");
  const audioPlayer = document.getElementById("audioPlayer");
  const playPauseButton = document.getElementById("playPauseIcon");
  const progressBar = document.getElementById("progressBar");

  // Chama a função para criar e atualizar a visualização do espectro de som

  function updateAudioVisualizer() {
    const canvas = document.getElementById("audioVisualizer");
    const canvasContext = canvas.getContext("2d");

    // Atualiza o tamanho do canvas de acordo com o tamanho do container
    canvas.width = canvas.parentElement.offsetWidth - 20;
    canvas.height = 200;

    // Função para iniciar o contexto de áudio após uma interação do usuário
    function iniciarAudioContext() {
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      const audioContext = new AudioContext();

      // Criação do AnalyserNode para análise do áudio
      const analyser = audioContext.createAnalyser();

      // Conecta o nó Analyser ao elemento de áudio
      const source = audioContext.createMediaElementSource(audioPlayer);
      source.connect(analyser);
      analyser.connect(audioContext.destination);

      // Configurações para a visualização do espectro de som
      analyser.fftSize = 256;
      const bufferLength = analyser.frequencyBinCount;
      const dataArray = new Uint8Array(bufferLength);

      // Função de renderização do espectro de som
      function renderAudioVisualizer() {
        requestAnimationFrame(renderAudioVisualizer);

        // Atualiza os dados do espectro de som
        analyser.getByteFrequencyData(dataArray);

        // Limpa o canvas
        canvasContext.clearRect(0, 0, canvas.width, canvas.height);

        // Configurações visuais do espectro de som
        const barWidth = (canvas.width / bufferLength) * 2.5;
        const barHeightMultiplier = canvas.height / 256;

        // Desenha as barras verticais do espectro de som
        for (let i = 0; i < bufferLength; i++) {
          const barHeight = dataArray[i] * barHeightMultiplier;
          const x = i * barWidth;
          const y = canvas.height - barHeight;

          canvasContext.fillStyle = `rgb(26, 135,84)`; //${barHeight + 35}
          canvasContext.fillRect(x, y, barWidth, barHeight);
        }
      }

      // Inicia a renderização do espectro de som
      renderAudioVisualizer();
    }

    // No evento de clique do botão ou em qualquer outro evento de interação do usuário
    audioPlayer.addEventListener("play", () => {
      iniciarAudioContext();
    });
  }

  sounds.forEach((sound) => {
    const li = document.createElement("li");
    li.classList.add(
      "list-group-item",
      "d-flex",
      "justify-content-between",
      "align-items-center",
      "py-2"
    );

    const a = document.createElement("a");
    a.classList.add("text-decoration-none", "text-dark");
    a.href = "#audioPlayer";
    a.innerText = sound.name;
    a.addEventListener("click", (event) => {
      event.preventDefault();

      if (currentSoundItem) {
        currentSoundItem.classList.remove("bg-success");
        currentSoundItem.classList.remove("text-light");
        currentSoundItem.classList.remove("playing");
      }

      currentSoundItem = a.parentElement;
      currentSoundItem.classList.add("bg-success");
      currentSoundItem.classList.add("text-light");
      currentSoundItem.classList.add("playing");

      audioSource.src = "../sounds/" + sound.file;
      audioPlayer.load();
      audioPlayer.addEventListener("loadeddata", () => {
        audioPlayer.play();
        playPauseButton.classList.remove("fa-play");
        playPauseButton.classList.add("fa-pause");
      });
    });

    li.appendChild(a);
    soundList.appendChild(li);
  });

  playPauseButton.addEventListener("click", () => {
    if (audioPlayer.paused) {
      audioPlayer.play();
      playPauseButton.classList.remove("fa-play");
      playPauseButton.classList.add("fa-pause");
    } else {
      audioPlayer.pause();
      playPauseButton.classList.remove("fa-pause");
      playPauseButton.classList.add("fa-play");
    }
  });

  audioPlayer.addEventListener("timeupdate", () => {
    const currentTime = audioPlayer.currentTime;
    const duration = audioPlayer.duration;
    const progress = (currentTime / duration) * 100;
    progressBar.style.width = `${progress}%`;
  });

  audioPlayer.addEventListener("ended", () => {
    if (currentSoundItem) {
      currentSoundItem.classList.remove("bg-primary");
      currentSoundItem.classList.remove("text-white");
      currentSoundItem.classList.remove("playing");
      currentSoundItem = null;
    }

    progressBar.style.width = "0";
    playPauseButton.classList.remove("fa-pause");
    playPauseButton.classList.add("fa-play");
  });

  const resetButton = document.getElementById("resetButton");

  resetButton.addEventListener("click", () => {
    if (currentSoundItem) {
      currentSoundItem.classList.remove("bg-primary");
      currentSoundItem.classList.remove("text-white");
      currentSoundItem.classList.remove("playing");
      currentSoundItem = null;
    }

    audioPlayer.currentTime = 0;
    progressBar.style.width = "0";
    audioPlayer.pause();
    playPauseButton.classList.remove("fa-pause");
    playPauseButton.classList.add("fa-play");
  });

  const volumeSlider = document.getElementById("volumeSlider");
  const volumeText = document.getElementById("volumeText");

  volumeSlider.addEventListener("input", () => {
    audioPlayer.volume = volumeSlider.value;
    volumeText.innerHTML = volumeSlider.value * 100;
    if (volumeSlider.value == 0) {
      volumeText.innerHTML = `<i class="fa-sharp fa-solid fa-volume-xmark mt-1"></i>`;
    } else if (volumeSlider.value >= 0.65) {
      volumeText.innerHTML = `<i class="fa-sharp fa-solid fa-volume-high mt-1"></i>`;
    } else if (volumeSlider.value < 0.65) {
      volumeText.innerHTML = `<i class="fa-sharp fa-solid fa-volume-low mt-1"></i>`;
    }
  });

  updateAudioVisualizer();
}

getAllSounds()
  .then((sounds) => {
    exibirSonsCarros(sounds);
  })
  .catch((err) => {
    console.error("Error while getting sounds:", err);
  });

document.addEventListener("DOMContentLoaded", function () {
  const canvas = document.getElementById("audioVisualizer");
  canvas.width = 100; // Substitua 'novoLargura' pelo valor desejado
  canvas.height = 100; // Substitua 'novoAltura' pelo valor desejado
});

function getAllMakes() {
  fetch(
    "http://localhost:8888/project/server/Models/Car.php?crud_req=allMakes",
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      const selectElement = document.getElementById("post_maker");

      // Clear existing options
      selectElement.innerHTML = "";

      // Add the default option
      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.textContent = "Select the maker";
      defaultOption.disabled = true;
      defaultOption.selected = true;
      selectElement.appendChild(defaultOption);

      // Add options for each make
      data.forEach((make) => {
        const option = document.createElement("option");
        option.value = make.name;
        option.id = make.id;
        option.textContent = make.name;
        selectElement.appendChild(option);
      });
    })
    .catch((err) => {
      console.error("Error retrieving makes:", err);
    });
}

window.addEventListener("load", getAllMakes);

const modelSelectElement = document.getElementById("post_maker");
modelSelectElement.addEventListener("change", getModelId);

function getModelId() {
  const selectedModel = modelSelectElement.value;
  const selectedModelId =
    modelSelectElement.options[modelSelectElement.selectedIndex].id;

  console.log("Selected Maker:", selectedModel);
  console.log("Selected Maker ID:", selectedModelId);
  return selectedModelId;
}

const selectElement = document.getElementById("post_maker");
selectElement.addEventListener("change", loadModelsForMake);

function loadModelsForMake() {
  const selectedMake = getModelId();
  console.log(selectedMake);

  // Clear existing options
  const modelSelectElement = document.getElementById("post_model");
  modelSelectElement.innerHTML = "";

  // Add a default option
  const defaultOption = document.createElement("option");
  defaultOption.value = "";
  defaultOption.textContent = "Select the model";
  defaultOption.disabled = true;
  defaultOption.selected = true;
  modelSelectElement.appendChild(defaultOption);

  if (selectedMake !== "") {
    fetch(
      `http://localhost:8888/project/server/Models/Car.php?crud_req=models&makeId=${selectedMake}`,
      {
        method: "GET",
        credentials: "include",
        mode: "cors",
      }
    )
      .then((res) => res.json())
      .then((data) => {
        // Add options for each model
        data.forEach((model) => {
          const option = document.createElement("option");
          option.value = model.name;
          option.textContent = model.name;
          modelSelectElement.appendChild(option);
        });
      })
      .catch((err) => {
        console.error("Error retrieving models:", err);
      });
  }
}
