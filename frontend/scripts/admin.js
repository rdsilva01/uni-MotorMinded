/************************************************************************/
/**********                  STATS FUNCTIONS                   **********/
/************************************************************************/
function getNumPosts() {
  fetch(
    "http://localhost:8888/project/server/Models/Post.php?crud_req=numPostsAll",
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      // Atualize o elemento HTML para exibir o número de posts
      document.getElementById("numPosts").textContent = data;
      if (data && data.length > 0) {
        document.getElementById("numPosts").textContent = data;
      } else {
        setTimeout(() => {
          /*displayErrorAlert(
              "Nenhum post encontrado para o ID de usuário especificado."
            );*/
        }, 500);
      }
    })
    .catch((err) => {
      console.error("Erro ao obter o número de posts:", err);
    });
}

// Função fetch para obter a marca de carro mais postada
function getMostPostedBrand() {
  fetch(
    "http://localhost:8888/project/server/Models/Post.php?crud_req=mostBrand",
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log("Marca de carro mais postada:", data);
      // Atualize o elemento HTML para exibir a marca de carro mais postada
      const brandElement = document.getElementById("mostPostedBrand");
      brandElement.textContent = data;
    })
    .catch((err) => {
      console.error("Erro ao obter a marca de carro mais postada:", err);
    });
}

// Função fetch para obter os países com mais usuários
function getMostCountryUsers() {
  fetch(
    "http://localhost:8888/project/server/Models/User.php?crud_req=mostCountry",
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log("Países com mais usuários:", data);
      // Atualize o elemento HTML para exibir os países com mais usuários
      const countriesElement = document.getElementById("mostCountryUsers");
      let countriesHtml = "";
      countriesHtml = `${data} <br>`;
      countriesElement.innerHTML = countriesHtml;
    })
    .catch((err) => {
      console.error("Erro ao obter os países com mais usuários:", err);
    });
}

/************************************************************************/
/**********                  POSTS FUNCTIONS                   **********/
/************************************************************************/
function getAllPosts() {
  fetch("http://localhost:8888/project/server/Models/Post.php?crud_req=all", {
    method: "GET",
    credentials: "include",
    mode: "cors",
  })
    .then((res) => res.json())
    .then((data) => {
      console.log("Timeline de posts:", data);
      // Atualize o elemento HTML para exibir a timeline de posts
      const timelineElement = document.getElementById("timeline");
      let timelineHtml = `
          <table class="table table-striped" id="tablePosts">
            <thead class="thead-dark">
              <tr class="postHeader">
                <th>ID</th>
                <th>Title</th>
                <th>Image</th>
                <th>Description</th>
                <th>Maker</th>
                <th>Model</th>
                <th>User ID</th>
                <th>Created in</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
        `;
      data.forEach((post) => {
        timelineHtml += `
            <tr class="post">
              <td>${post.id}</td>
              <td>${post.title}</td>
              <td><img class="custom-size-img rounded-4" src="${post.image_path}"></td>
              <td>${post.descrip}</td>
              <td>${post.maker}</td>
              <td>${post.model}</td>
              <td>${post.user_id}</td>
              <td>${post.created_at}</td>
              <td><button class="btn btn-danger" onclick="deletePost(${post.id})">Delete</button></td>
            </tr>
          `;
      });
      timelineHtml += `
            </tbody>
          </table>
        `;
      timelineElement.innerHTML = timelineHtml;
    })
    .catch((err) => {
      console.error("Erro ao obter a timeline de posts:", err);
    });
}

function deletePost(id) {
  const confirmed = confirm("Tem certeza que deseja apagar este post?");
  if (confirmed) {
    // Continuar com a exclusão do post
    fetch(
      `http://localhost:8888/project/server/Models/Post.php?crud_req=delete&id=${id}`,
      {
        method: "DELETE",
        credentials: "include",
        mode: "cors",
      }
    )
      .then((res) => {
        if (res.ok) {
          // Post apagado com sucesso, atualize a timeline

          getAllPosts();
        } else {
          console.error("Erro ao apagar o post:", res.statusText);
        }
      })
      .catch((err) => {
        console.error("Erro ao apagar o post:", err);
      });
  }
}

/************************************************************************/
/**********                  USERS FUNCTIONS                   **********/
/************************************************************************/
function getAllUsers() {
  fetch("http://localhost:8888/project/server/Models/User.php?crud_req=all", {
    method: "GET",
    credentials: "include",
    mode: "cors",
  })
    .then((res) => res.json())
    .then((data) => {
      console.log("Lista de usuários:", data);
      // Atualize o elemento HTML para exibir a lista de usuários
      const usersElement = document.getElementById("users");
      let usersHtml = `
        <table class="table table-striped" id="tableUsers">
          <thead class="thead-dark">
            <tr class="userHeader">
              <th>ID</th>
              <th>Username</th>
              <th>Profile Image</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>E-mail</th>
              <th>About Me</th>
              <th>Country</th>
              <th>Type</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
      `;
      data.forEach((user) => {
        usersHtml += `
          <tr class="user">
            <td>${user.id}</td>
            <td>${user.user_name}</td>
            <td><img class="custom-size-img rounded-4" src="${user.image_path}"></td>
            <td>${user.first_name}</td>
            <td>${user.last_name}</td>
            <td>${user.email}</td>
            <td>${user.about_me}</td>
            <td>${user.country}</td>
            <td>${user.user_type}</td>
            <td>
            <button class="btn btn-danger" onclick="deleteUser(${user.id})">Delete</button>
            <button class="btn btn-primary" onclick="updateUserBtn(${user.id})">Update</button>
            </td>
          </tr>
        `;
      });
      usersHtml += `
          </tbody>
        </table>
      `;
      usersElement.innerHTML = usersHtml;
    })
    .catch((err) => {
      console.error("Erro ao obter a lista de usuários:", err);
    });
}

function updateUserBtn(user_id) {
  const updateUsersForm = document.getElementById("updateUsersForm");
  updateUsersForm.style.display = "block";
  fetch(
    `http://localhost:8888/project/server/Models/User.php?crud_req=profile&id=${user_id}`,
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log("Dados do usuário:", data);
      // Clear the input fields of the edit form
      const updateButton = document.getElementById("update");
      document.getElementById("userIdSpan").innerHTML = user_id;

      const firstNameInput = document.getElementById("updateUserFirstName");
      firstNameInput.value = data.first_name;

      const lastNameInput = document.getElementById("updateUserLastName");
      lastNameInput.value = data.last_name;

      const emailInput = document.getElementById("updateEmail");
      emailInput.value = data.email;

      const aboutMeInput = document.getElementById("updateAbout");
      aboutMeInput.value = data.about_me;

      const typeInput = document.getElementById("updateUserType");
      typeInput.value = data.user_type;
    })
    .catch((error) => {
      console.log("Ocorreu um erro ao buscar os dados do usuário:", error);
    });
}

function updateUser() {
  const user_id = document.getElementById("userIdSpan").innerHTML;
  const updateFirstName = document.getElementById("updateUserFirstName").value;
  const updateLastName = document.getElementById("updateUserLastName").value;
  const updateEmail = document.getElementById("updateEmail").value;
  const updateAbout = document.getElementById("updateAbout").value;
  const updateUserType = document.getElementById("updateUserType").value;

  // Create an object with the updated user details
  const updatedUser = {
    first_name: updateFirstName,
    last_name: updateLastName,
    email: updateEmail,
    about_me: updateAbout,
    user_type: updateUserType,
  };

  // Send a PUT request to update the user
  fetch(
    `http://localhost:8888/project/server/Models/User.php?crud_req=patch&id=${user_id}`,
    {
      method: "PUT",
      credentials: "include",
      mode: "cors",
      headers: {
        "Content-Type": "plain/text",
      },
      body: JSON.stringify(updatedUser),
    }
  )
    .then((res) => {
      if (res.ok) {
        alert(res);

        setTimeout(() => {
          getAllUsers();
        }, 500);
      } else {
        alert(res.statusText);
        console.error("Erro ao atualizar o usuário:", res.statusText);
      }
    })
    .catch((err) => {
      alert(err);
      console.error("Erro ao atualizar o usuário:", err);
    });
}

/************************************************************************/
/**********                      GRAPHS                        **********/
/************************************************************************/
function getBrandsGraph() {
  fetch(
    "http://localhost:8888/project/server/Models/Post.php?crud_req=numBrands",
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log("Marcas de carro mais postadas:", data);
      // Atualize o elemento HTML para exibir as marcas de carro mais postadas
      const chartLabels = [];
      const chartData = [];

      // Extrair as marcas e contagens de posts dos dados
      for (const brand of data) {
        chartLabels.push(brand.maker);
        chartData.push(brand.post_count);
      }

      // Configurar o gráfico usando o Chart.js e Bootstrap
      const ctx = document.getElementById("chartcars").getContext("2d");
      new Chart(ctx, {
        type: "pie",
        data: {
          labels: chartLabels,
          datasets: [
            {
              label: "Marcas de Carro Mais Postadas",
              data: chartData,
              backgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56",
                "#8BC34A",
                "#9C27B0",
                "#E91E63",
                "#4CAF50",
                "#FF5722",
                "#3F51B5",
                "#FF9800",
                "#673AB7",
                "#CDDC39",
                "#607D8B",
                "#FFC107",
                "#2196F3",
                "#FFEB3B",
                "#009688",
                "#795548",
                "#FF5252",
                "#00BCD4",
                "#9E9E9E",
                "#FF9800",
                "#4CAF50",
                "#E040FB",
                "#FFC107",
                "#607D8B",
                "#3F51B5",
                "#8BC34A",
                "#9C27B0",
                "#FF5722",
              ],
            },
          ],
        },
        options: {
          responsive: true,
        },
      });
    })
    .catch((err) => {
      console.error("Erro ao obter as marcas de carro mais postadas:", err);
    });
}

function getCountriesGraph() {
  fetch(
    "http://localhost:8888/project/server/Models/User.php?crud_req=countries",
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log("Países dos usuários:", data);
      // Atualize o elemento HTML para exibir os países dos usuários
      const chartLabels = [];
      const chartData = [];

      // Extrair os países e contagens de usuários dos dados
      for (const country of data) {
        chartLabels.push(country.country);
        chartData.push(country.total_users);
      }

      // Configurar o gráfico usando o Chart.js e Bootstrap
      const ctx = document.getElementById("chartcountries").getContext("2d");
      new Chart(ctx, {
        type: "pie",
        data: {
          labels: chartLabels,
          datasets: [
            {
              label: "Países dos Usuários",
              data: chartData,
              backgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56",
                "#8BC34A",
                "#9C27B0",
                "#E91E63",
                "#4CAF50",
                "#FF5722",
                "#3F51B5",
                "#FF9800",
                "#673AB7",
                "#CDDC39",
                "#607D8B",
                "#FFC107",
                "#2196F3",
                "#FFEB3B",
                "#009688",
                "#795548",
                "#FF5252",
                "#00BCD4",
                "#9E9E9E",
                "#FF9800",
                "#4CAF50",
                "#E040FB",
                "#FFC107",
                "#607D8B",
                "#3F51B5",
                "#8BC34A",
                "#9C27B0",
                "#FF5722",
              ],
            },
          ],
        },
        options: {
          responsive: true,
        },
      });
    })
    .catch((err) => {
      console.error("Erro ao obter os países dos usuários:", err);
    });
}

function getTopUsersPostsGraph() {
  fetch(
    "http://localhost:8888/project/server/Models/Post.php?crud_req=mostPosts",
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log("Usuários com mais posts:", data);
      // Atualize o elemento HTML para exibir os usuários com mais posts
      const chartLabels = [];
      const chartData = [];

      // Extrair os usuários e o número de posts deles dos dados
      for (const user of data) {
        chartLabels.push(user.user_name);
        chartData.push(user.total_posts);
      }

      // Configurar o gráfico usando o Chart.js e Bootstrap
      const ctx = document.getElementById("chartusers").getContext("2d");
      new Chart(ctx, {
        type: "pie",
        data: {
          labels: chartLabels,
          datasets: [
            {
              label: "Users with most posts",
              data: chartData,
              backgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56",
                "#8BC34A",
                "#FF5722",
                "#9C27B0",
                "#607D8B",
                "#FF9800",
                "#3F51B5",
                "#009688",
              ],
            },
          ],
        },
        options: {
          responsive: true,
        },
      });
    })
    .catch((err) => {
      console.error("Erro ao obter os usuários com mais posts:", err);
    });
}

/************************************************************************/
/**********                  SOUND FUNCTIONS                   **********/
/************************************************************************/
function getAllSounds() {
  fetch(
    "http://localhost:8888/project/server/Models/Sound.php?crud_req=getAllSounds",
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log("All sounds:", data);
      // Update the HTML element to display all sounds
      const soundsElement = document.getElementById("sounds");
      let soundsHtml = `
        <table class="table table-striped" id="tableSounds">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>name</th>
              <th>file</th>
              <th>Actions</th> <!-- New column -->
            </tr>
          </thead>
          <tbody>
      `;
      data.forEach((sound) => {
        soundsHtml += `
          <tr class="sound">
            <td>${sound.id}</td>
            <td>${sound.name}</td>
            <td>${sound.file}</td>
            <td>
              <button class="btn btn-danger" onclick="changeSoundById(${sound.id})">Update</button>
              <button class="btn btn-primary" onclick="deleteSound(${sound.id})">Delete</button>
            </td>
          </tr>
        `;
      });
      soundsHtml += `
          </tbody>
        </table>
      `;
      soundsElement.innerHTML = soundsHtml;
    })
    .catch((err) => {
      console.error("Error while getting all sounds:", err);
    });
}

const addSound = () => {
  const soundName = document.getElementById("addSoundName").value;
  const soundFile = document.getElementById("addSoundFile").files[0];

  // Create a FormData object to send the data
  const formData = new FormData();
  formData.append("soundName", soundName);
  formData.append("soundFile", soundFile);

  // Make a POST request to the PHP script
  fetch("http://localhost:8888/project/server/Models/Sound.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (response.ok) {
        console.log("Sound added successfully");
        // Perform any additional actions or update the UI as needed
      } else {
        console.log("Error adding sound");
        // Handle the error or display an error message
      }
    })
    .catch((error) => {
      console.log("Error:", error);
      // Handle the error or display an error message
    });
};

const soundsElement = document.getElementById("soundsview");
const submitBtn = document.getElementById("addSound");
submitBtn.addEventListener("click", addSound);

function showSounds() {
  if (soundsElement.style.display == "none") {
    soundsElement.style.display = "block";
    timelineElement.style.display = "none";
    racesElement.style.display = "none";
    usersElement.style.display = "none";
  }
}

const soundsBtn = document.getElementById("btnsounds");
soundsBtn.addEventListener("click", showSounds);

const addNewSoundBtn = document.getElementById("addnewsound");
addNewSoundBtn.addEventListener("click", () => {
  const newSoundForm = document.getElementById("addSoundForm");
  newSoundForm.style.display = "block";
});

/************************************************************************/
/**********                  RACE FUNCTIONS                    **********/
/************************************************************************/
function getAllRaces() {
  fetch("http://localhost:8888/project/server/Models/Race.php?crud_req=all", {
    method: "GET",
    credentials: "include",
    mode: "cors",
  })
    .then((res) => res.json())
    .then((data) => {
      console.log("All races:", data);
      // Update the HTML element to display all races
      const racesElement = document.getElementById("races");
      let racesHtml = `
        <table class="table table-striped" id="tableRaces">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>Event</th>
              <th>Location</th>
              <th>Date</th>
              <th>Actions</th> <!-- New column -->
            </tr>
          </thead>
          <tbody>
      `;
      data.forEach((race) => {
        racesHtml += `
          <tr class="race">
            <td>${race.id}</td>
            <td>${race.name_event}</td>
            <td>${race.location}</td>
            <td>${race.date}</td>
            <td>
              <button class="btn btn-danger" onclick="changeRaceById(${race.id})">Update</button>
              <button class="btn btn-primary" onclick="deleteRace(${race.id})">Delete</button>
            </td>
          </tr>
        `;
      });
      racesHtml += `
          </tbody>
        </table>
      `;
      racesElement.innerHTML = racesHtml;
    })
    .catch((err) => {
      console.error("Error while getting all races:", err);
    });
}

function updateRace(race_id) {
  const updateName = document.getElementById("updateName").value;
  const updateLocation = document.getElementById("updateLocation").value;
  const updateDate = document.getElementById("updateDate").value;

  // Create an object with the updated race details
  const updatedRace = {
    name_event: updateName,
    location: updateLocation,
    date: updateDate,
  };

  // Send a PUT request to update the race
  fetch(
    `http://localhost:8888/project/server/Models/Race.php?crud_req=update&id=${race_id}`,
    {
      method: "PUT",
      credentials: "include",
      mode: "cors",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(updatedRace),
    }
  )
    .then((res) => {
      if (res.ok) {
        alert(res);
        setTimeout(() => {
          getAllRaces();
        }, 500);
      } else {
        alert(res.statusText);
        console.error("Error updating the race:", res.statusText);
      }
    })
    .catch((err) => {
      alert(err);
      console.error("Error updating the race:", err);
    });
}

// Function to delete a race
function deleteRace(raceId) {
  // Add your logic to handle deleting a race here
  console.log("Delete race with ID:", raceId);
}

function changeRaceById(race_Id) {
  fetch(
    `http://localhost:8888/project/server/Models/Race.php?crud_req=one&id=${race_Id}`,
    {
      method: "GET",
      credentials: "include",
      mode: "cors",
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log("Dados do carro:", data);
      // Clear the input fields of the edit form
      const updateButton = document.getElementById("update");
      document.getElementById("input-race-id").value = race_Id;

      const raceEventInput = document.getElementById("updateName");
      raceEventInput.value = data.name_event;

      const raceLocationInput = document.getElementById("updateLocation");
      raceLocationInput.value = data.location;

      const raceDateInput = document.getElementById("updateDate");
      raceDateInput.value = data.date;
    })
    .catch((error) => {
      console.log("Ocorreu um erro ao buscar os dados do carro:", error);
    });

  const updateForm = document.getElementById("updateForm");
  updateForm.style.display = "block";
}

const racesElement = document.getElementById("racesview");
function showRaces() {
  if (racesElement.style.display == "none") {
    racesElement.style.display = "block";
    timelineElement.style.display = "none";
    usersElement.style.display = "none";
    soundsElement.style.display = "none";
  }
}

const racesBtn = document.getElementById("btnraces");
racesBtn.addEventListener("click", showRaces);

const updateBtn = document.getElementById("update");
updateBtn.addEventListener("click", () => {
  const race_value = document.getElementById(input - race - id).value;
  console.log(race_value);
  updateRace(race_value);
});

const addNewBtn = document.getElementById("addnew");
addNewBtn.addEventListener("click", () => {
  const updateForm = document.getElementById("updateForm");
  updateForm.style.display = "none";
  const newForm = document.getElementById("createForm");
  newForm.style.display = "block";
});

const cancelBtn2 = document.getElementById("cancel_create");
cancelBtn2.addEventListener("click", () => {
  const updateForm = document.getElementById("updateForm");
  updateForm.style.display = "none";
  const newForm = document.getElementById("createForm");
  newForm.style.display = "none";
});

const create = document.getElementById("create");
create.addEventListener("click", () => {
  var status;
  const formData = new FormData(document.getElementById("raceCreateForm"));
  console.log(formData.get("name_event"));
  fetch(
    "http://localhost:8888/project/server/Models/Race.php?crud_req=create",
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
        console.log(data);
      } else {
      }
    })
    .catch((err) => {
      console.log(err);
    });
});

/************************************************************************/
/**********                    WINDOW LOAD                     **********/
/************************************************************************/
window.addEventListener("load", () => {
  getNumPosts();
  getMostPostedBrand();
  getMostCountryUsers();
  getAllPosts();
  getAllUsers();
  getBrandsGraph();
  getCountriesGraph();
  getTopUsersPostsGraph();
  getAllRaces();
  getAllSounds();
});

const usersElement = document.getElementById("usersview");
const timelineElement = document.getElementById("timelineview");

function showPosts() {
  if (timelineElement.style.display == "none") {
    timelineElement.style.display = "block";
    usersElement.style.display = "none";
    racesElement.style.display = "none";
    soundsElement.style.display = "none";
  }
}

function showUsers() {
  if (usersElement.style.display == "none") {
    console.log("show users");
    usersElement.style.display = "block";
    timelineElement.style.display = "none";
    racesElement.style.display = "none";
    soundsElement.style.display = "none";
  }
}

const postsBtn = document.getElementById("btnposts");
postsBtn.addEventListener("click", showPosts);

const usersBtn = document.getElementById("btnusers");
usersBtn.addEventListener("click", showUsers);

const cancelBtn = document.getElementById("cancel");
cancelBtn.addEventListener("click", () => {
  const updateForm = document.getElementById("updateForm");
  updateForm.style.display = "none";
});

/************************************************************************/
/**********                   PDF DOWNLOAD                     **********/
/************************************************************************/
function hideActionColumns() {
  const tables = document.querySelectorAll("table"); // Get all table elements

  tables.forEach((table) => {
    const headerRow = table.querySelector("thead tr"); // Get the header row
    const headerCells = headerRow.querySelectorAll("th"); // Get all header cells

    // Find the index of the "Action" or "Actions" column
    let actionColumnIndex = -1;
    headerCells.forEach((cell, index) => {
      const cellText = cell.innerText.trim();
      if (cellText === "Action" || cellText === "Actions") {
        actionColumnIndex = index;
      }
    });

    if (actionColumnIndex !== -1) {
      // Hide the "Action" or "Actions" column in each row
      const rows = table.querySelectorAll("tbody tr");
      rows.forEach((row) => {
        const cells = row.querySelectorAll("td");
        cells[actionColumnIndex].style.display = "none";
      });

      // Hide the header cell of the "Action" or "Actions" column
      headerCells[actionColumnIndex].style.display = "none";
    }
  });
}

function hideAllButtons() {
  const buttons = document.querySelectorAll("button"); // Get all button elements

  buttons.forEach((button) => {
    button.style.display = "none"; // Hide each button
  });
}

function downloadAsPDF() {
  const pdfButton = document.getElementById("downloadPDF");
  pdfButton.style.display = "none";

  // Hide unnecessary elements
  const elementsToHide = [
    document.getElementById("statsRow"),
    document.getElementById("graphsStat"),
  ];
  elementsToHide.forEach((element) => (element.style.display = "none"));

  // Hide all images
  const images = document.getElementsByTagName("img");
  for (let i = 0; i < images.length; i++) {
    images[i].style.display = "none";
  }

  hideActionColumns();
  hideAllButtons();

  // Show the desired elements
  const elementsToShow = [
    document.getElementById("racesview"),
    document.getElementById("soundsview"),
    document.getElementById("usersview"),
    document.getElementById("timelineview"),
  ];
  elementsToShow.forEach((element) => {
    element.style.display = "block";
  });

  // Create a configuration for html2pdf with A4 page size
  var options = {
    filename: "dashboard.pdf",
    html2canvas: {
      scale: 2,
    },
    jsPDF: {
      unit: "pt",
      format: "a4",
      orientation: "portrait",
    },
  };

  // Use html2pdf to convert the content into PDF
  html2pdf().set(options).from(document.body).save();

  // Reload the page
  setTimeout(function () {
    location.reload();
  }, 500);
}

function generateUsersPDF() {
  const table = document.getElementById("tableUsers");
  const rows = table.getElementsByClassName("user");
  const headerRow = table.getElementsByClassName("userHeader")[0];

  const data = [];
  const headerData = [];
  const columnWidths = ["auto", "auto", "auto", "auto", "auto", "auto", "auto"];

  // Get header row data and column widths
  const headerCells = headerRow.querySelectorAll("th, td");
  for (let i = 0; i < headerCells.length - 1; i++) {
    if (i !== 2 && i !== 6) {
      // Exclude the third column
      headerData.push({
        text: headerCells[i].textContent.trim(),
        fillColor: "#316543", // Apply a darker fill color to the header row
        fontSize: 12, // Specify the desired font size in points
        bold: true,
        color: "#FFFFFF",
      });
    }
  }
  data.push(headerData);

  // Get table body data
  // Get table body data
  for (let i = 0; i < rows.length; i++) {
    const cells = rows[i].querySelectorAll("th, td");
    const rowData = [];
    for (let j = 0; j < cells.length - 1; j++) {
      if (j !== 2 && j !== 6) {
        const cellData = {
          text: cells[j].textContent.trim(),
        };
        if (i % 2 === 0) {
          cellData.fillColor = "#CCCCCC"; // Apply a different fill color to even rows
        }
        rowData.push(cellData);
      }
    }
    data.push(rowData);
  }

  // Define table style
  const tableStyle = {
    table: {
      headerRows: 1,
      widths: columnWidths,
      body: data,
    },
    headerRow: {
      fillColor: "#CCCCCC", // Apply a darker fill color to the header row
      bold: true,
    },
  };

  const docDefinition = {
    content: [
      {
        layout: "noBorders",
        table: tableStyle.table,
        headerRow: tableStyle.headerRow,
      },
    ],
    footer: {
      text: "Motor Minded © - 2023",
      alignment: "center",
    },
  };

  pdfMake.createPdf(docDefinition).download("users.pdf");
}

const downloadUsersButton = document.getElementById("downloadUsersPDF");
downloadUsersButton.addEventListener("click", generateUsersPDF);

function generatePostsPDF() {
  const table = document.getElementById("tablePosts");
  const rows = table.getElementsByClassName("post");
  const headerRow = table.getElementsByClassName("postHeader")[0];

  const data = [];
  const headerData = [];
  const rowData = [];
  const columnWidths = ["auto", "auto", "auto", "auto", "auto", "auto", "auto"];

  // Get header row data and column widths
  const headerCells = headerRow.querySelectorAll("th, td");
  for (let i = 0; i < headerCells.length - 1; i++) {
    if (i !== 2) {
      // Exclude the third column
      headerData.push({
        text: headerCells[i].textContent.trim(),
        fillColor: "#553B94", // Apply a darker fill color to the header row
        fontSize: 12, // Specify the desired font size in points
        bold: true,
        color: "#FFFFFF",
      });
    }
  }
  data.push(headerData);
  // Get table body data
  for (let i = 0; i < rows.length; i++) {
    const cells = rows[i].querySelectorAll("th, td");
    const rowData = [];
    for (let j = 0; j < cells.length - 1; j++) {
      if (j !== 2) {
        const cellData = {
          text: cells[j].textContent.trim(),
        };
        if (i % 2 === 0) {
          cellData.fillColor = "#CCCCCC"; // Apply a different fill color to even rows
        }
        rowData.push(cellData);
      }
    }
    data.push(rowData);
  }

  // Define table style
  const tableStyle = {
    table: {
      headerRows: 1,
      widths: columnWidths,
      body: data,
    },
    headerRow: {
      fillColor: "#CCCCCC", // Apply a darker fill color to the header row
      bold: true,
    },
  };

  const docDefinition = {
    content: [
      {
        layout: "noBorders",
        table: tableStyle.table,
        headerRow: tableStyle.headerRow,
      },
    ],
    footer: {
      text: "Motor Minded © - 2023",
      alignment: "center",
    },
  };

  pdfMake.createPdf(docDefinition).download("posts.pdf");
}

const downloadPostsButton = document.getElementById("downloadPostsPDF");
downloadPostsButton.addEventListener("click", generatePostsPDF);
