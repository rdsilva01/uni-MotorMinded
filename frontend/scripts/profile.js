document.addEventListener("DOMContentLoaded", function () {
  window.addEventListener("load", function () {
    const body = document.querySelector("body");
    body.style.display = "block";
  });
});

const update = document.getElementById("update");
const unsubscribe = document.getElementById("unsubscribe");

const homebutton = document.getElementById("homebutton");

homebutton.addEventListener("click", () => (location.href = "./home.php"));

update.addEventListener("click", () => (location.href = "./update.php"));

// Handle the confirmation and delete action
document.getElementById("confirmUnsubscribe").addEventListener("click", () => {
  fetch("http://localhost:8888/project/server/Models/User.php", {
    method: "DELETE",
    credentials: "include",
    mode: "cors",
  })
    .then((res) => res.text())
    .then((data) => {
      displaySuccessAlert("User deleted successfully!");
      setTimeout(() => {
        location.href = "index.php";
      }, 1000);
    });
});

const logout = document.querySelector(".logout");

logout.addEventListener("click", () => {
  fetch("http://localhost:8888/project/server/Models/User.php", {
    credentials: "include",
    mode: "cors",
  })
    .then((res) => res.text())
    .then((data) => {
      displaySuccessAlert("Goodbye!");
      setTimeout(() => {
        location.href = "index.php";
      }, 1000);
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
    .then(async (data) => {
      // Trate os dados recebidos aqui
      const username = data.user_name;
      const firstName = data.first_name;
      const lastName = data.last_name;
      const email = data.email;
      const aboutMe = data.about_me;
      const country = data.country;
      const followers = data.followers;
      const following = data.following;
      const image = data.image_path;

      // Atualize a bandeira do país

      const countryCode = await getCountryCode(country);

      console.log(countryCode);

      if (countryCode) {
        flagContainer.innerHTML = `<img class="rounded-circle" src="https://flagsapi.com/${countryCode}/shiny/16.png">`;
      } else {
        flagContainer.innerHTML = "";
      }

      // Atualize o conteúdo da página com os dados do usuário
      //  const Followers = document.getElementById("followers");
      //Followers.innerHTML = `${followers}`;
      const Name = document.getElementById("name");
      Name.innerHTML = `${firstName} ${lastName} <small><a href="#" class="link-secondary link-offset-2 link-underline-opacity-0 link-underline-opacity-25-hover" title="Copy the Profile Link" onclick="copyToClipboard('http://localhost:8888/project/frontend/pages/profile.php?id=${user_ID}')">@${username}</a></small>`;

      const Country = document.getElementById("country");
      Country.innerHTML = `${country}`;

      const About = document.getElementById("aboutMe");
      About.innerHTML = `${aboutMe}`;

      const Image = document.getElementById("profileImg");
      Image.src = `../images/${image}`;

      if (isOwner === false) {
        const update = document.getElementById("update");
        update.style.display = "none";

        const unsubscribe = document.getElementById("unsubscribe");
        unsubscribe.style.display = "none";
      }
    })
    .catch((error) => {
      console.error(error);
    });
}

// Chame a função para carregar o perfil do usuário quando a página for carregada
window.addEventListener("load", loadUserProfile(userID));

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

const postsContainer = document.getElementById("posts-container");
function showUserPosts() {
  const urlParams = new URLSearchParams(window.location.search);
  const userId = urlParams.get("id");
  if (userId != userLogged) {
    const numPostDiv = document.getElementById("numPostsDiv");
    numPostDiv.classList.remove("col-lg-6");
    numPostDiv.classList.add("col-lg-12");
    if (numPostDiv.classList.contains("justify-content-end")) {
      numPostDiv.classList.remove("justify-content-end");
      numPostDiv.classList.add("justify-content-center");
    }

    const updUnsunButton = document.getElementById("updUnsunButton");
    updUnsunButton.style.display = "none";
    updUnsunButton.classList.remove("col-lg-6");

    const showPostsButton = document.getElementById("showPostsButton");
    showPostsButton.style.display = "none";

    const btnParagraph = document.getElementById("btnParagraph");
    btnParagraph.style.display = "none";
  } else {
    const numPostDiv = document.getElementById("numPostsDiv");
    if (numPostDiv.classList.contains("col-lg-12")) {
      numPostDiv.classList.remove("col-lg-12");
      numPostDiv.classList.add("col-lg-6");
    }

    if (numPostDiv.classList.contains("justify-content-center")) {
      numPostDiv.classList.remove("justify-content-center");
      numPostDiv.classList.add("justify-content-end");
    }
  }

  const updUnsunButton = document.getElementById("updUnsunButton");
  updUnsunButton.style.display = "block";

  const showPostsButton = document.getElementById("showPostsButton");
  showPostsButton.style.display = "block";

  if (!userId) {
    displayErrorAlert("ID de usuário não especificado no URL.");
    return;
  }
  fetch(
    `http://localhost:8888/project/server/Models/Post.php?crud_req=user&id=${userId}`,
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      if (data.length === 0) {
        setTimeout(() => {
          displayErrorAlert(
            "Nenhum post encontrado para o ID de usuário especificado."
          );
        }, 500);
      } else {
        numPosts(userId);
        // Limpar o conteúdo anterior
        postsContainer.innerHTML = "";

        // Criar elementos HTML para cada post e adicioná-los ao container
        data.forEach((dates, index) => {
          if (index % 2 === 0) {
            // Cria um novo elemento 'row' para agrupar as imagens
            const rowElement = document.createElement("div");
            rowElement.classList.add("row");
            rowElement.classList.add("text-center");

            // Cria os elementos 'col' para as duas imagens
            const col1 = document.createElement("div");
            col1.classList.add("col");
            col1.classList.add("ms-3");
            const col2 = document.createElement("div");
            col2.classList.add("col");
            col2.classList.add("me-3");

            // Cria o elemento 'a' para a primeira imagem e adiciona ao 'col1'
            const deleteButton1 = document.createElement("button");
            deleteButton1.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
          </svg>`;
            deleteButton1.classList.add(
              "btn",
              "btn-danger",
              "deletebutton",
              "ms-1",
              "mb-1"
            );
            deleteButton1.style.display = "none";
            // Adicione um manipulador de evento para o botão de apagar
            deleteButton1.addEventListener("click", () => {
              deletePost(dates.id); // Substitua 'deletePost' pelo método apropriado para apagar o post
            });
            col1.appendChild(deleteButton1);

            const img1Link = document.createElement("a");
            img1Link.href = "../pages/postview.php?post=" + dates.id;
            img1Link.target = "_blank";
            col1.appendChild(img1Link);

            // Cria o elemento 'img' para a primeira imagem e adiciona ao 'img1Link'
            const img1 = document.createElement("img");
            img1.src = "../../." + dates.image_path;
            img1.classList.add(
              "card-img-top",
              "rounded",
              "shadow",
              "custom-size-img",
              "mb-3"
            );
            img1Link.appendChild(img1);

            // Verifica se há uma imagem correspondente para o próximo índice
            if (index + 1 < data.length) {
              // Cria o botão de apagar para a segunda imagem
              const deleteButton2 = document.createElement("button");
              deleteButton2.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
              <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
            </svg>`;
              deleteButton2.classList.add(
                "btn",
                "btn-danger",
                "mb-1",
                "ms-1",
                "deletebutton"
              );
              deleteButton2.style.display = "none";
              // Adicione um manipulador de evento para o botão de apagar
              deleteButton2.addEventListener("click", () => {
                deletePost(dates2.id); // Substitua 'deletePost' pelo método apropriado para apagar o post
              });
              col2.appendChild(deleteButton2);

              const dates2 = data[index + 1];

              // Cria o elemento 'a' para a segunda imagem e adiciona ao 'col2'
              const img2Link = document.createElement("a");
              img2Link.href = "../pages/postview.php?post=" + dates2.id;
              img2Link.target = "_blank";
              col2.appendChild(img2Link);

              // Cria o elemento 'img' para a segunda imagem e adiciona ao 'img2Link'
              const img2 = document.createElement("img");
              img2.src = "../../." + dates2.image_path;
              img2.classList.add(
                "card-img-top",
                "rounded",
                "shadow",
                "custom-size-img",
                "mb-3"
              );
              img2Link.appendChild(img2);
            }

            // Adiciona os elementos 'col' ao 'row'
            rowElement.appendChild(col1);
            rowElement.appendChild(col2);

            // Cria o elemento 'post' e adiciona o 'row' ao 'postElement'
            const postElement = document.createElement("div");
            postElement.classList.add("post");
            postElement.appendChild(rowElement);

            // Adiciona o 'postElement' ao 'postsContainer'
            postsContainer.appendChild(postElement);
          }
        });
      }
    })
    .catch((error) => {
      setTimeout(() => {
        // displayErrorAlert("Erro ao obter os posts: " + error.message);
        console.log(error);
      }, 500);
    });
}

window.addEventListener("load", showUserPosts());

const showPostsButton = document.getElementById("showPostsButton");

showPostsButton.addEventListener("click", () => {
  toggleContent();
});

function toggleContent() {
  if (userID == userLogged) {
    var content = document.getElementsByClassName("deletebutton");
    for (var i = 0; i < content.length; i++) {
      if (content[i].style.display === "none") {
        content[i].style.display = "block";
        showPostsButton.textContent = "Cancel";
      } else {
        content[i].style.display = "none";
        showPostsButton.textContent = "Edit";
      }
    }
  }
}

function deletePost(postID) {
  fetch(`http://localhost:8888/project/server/Models/Post.php?id=${postID}`, {
    method: "DELETE",
    credentials: "include",
    mode: "cors",
  })
    .then((res) => {
      if (res.ok) {
        // Post apagado com sucesso
        console.log("Post apagado com sucesso.");
        displaySuccessAlert("Post apagado com sucesso.");
        setTimeout(() => {
          location.reload();
        }, 500);
        // Faça qualquer ação adicional necessária após a exclusão do post
      } else {
        throw new Error("Erro ao apagar o post.");
      }
    })
    .catch((error) => {
      console.error("Erro ao apagar o post:", error);
    });
}

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
      document.getElementById("numPosts").textContent = data;
      if (data && data.length > 0) {
        document.getElementById("numPosts").textContent = data;
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

async function getCountryCode(country) {
  const username = "rodrids01"; // Substitua "seu_username" pelo seu nome de usuário da API Geonames

  try {
    const response = await fetch(
      `http://api.geonames.org/searchJSON?name=${encodeURIComponent(
        country
      )}&maxRows=1&username=${username}`
    );
    const data = await response.json();

    if (data.geonames.length > 0) {
      return data.geonames[0].countryCode;
    } else {
      return null;
    }
  } catch (error) {
    console.error("Erro ao obter o código do país:", error);
    return null;
  }
}

function copyToClipboard(text) {
  navigator.clipboard
    .writeText(text)
    .then(() => {
      displaySuccessAlert("Profile copied to clipboard!");
    })
    .catch((error) => {
      console.error(error);
      displayErrorAlert("Error copying link to clipboard.");
    });
}
document.addEventListener("DOMContentLoaded", function () {
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
