const login = document.querySelector("button");
login.addEventListener("click", () => {
  const formData = new FormData(document.querySelector("form"));
  var status;
  fetch("http://localhost:8888/project/server/Models/User.php", {
    method: "POST",
    body: formData,
    mode: "cors",
    credentials: "include",
  })
    .then((res) => {
      status = res.status;
      console.log("user cookies is ");
      console.log(res.headers.get("user"));
      return res.json();
    })
    .then((data) => {
      console.log(data);
      console.log(document.cookie);
      if (status == 200) {
        const username = data.username;
        const firstName = data.first_name;
        displaySuccessAlert(firstName);
        setTimeout(() => {
          location.href = "/project/frontend/pages/home.php";
        }, 500);
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

  alertMessage.textContent = "Welcome back " + message + "!";
  alertContainer.classList.add("show");
}

function displayErrorAlert(errorMessage) {
  const alertContainer = document.getElementById("alertContainer");
  const alertMessage = document.getElementById("alertMessage");

  alertContainer.classList.add("alert-danger");
  alertMessage.textContent = "Error";
}
