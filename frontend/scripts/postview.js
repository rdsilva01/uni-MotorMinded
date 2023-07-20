const postcontainer = document.querySelector(".post-container");

// fetch posts from database
function getThePost(id) {
  fetch(
    `http://localhost:8888/project/server/Models/Post.php?crud_req=onePost&id=${id}`
  )
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      const post = data;
      const postElement = document.createElement("div");
      postElement.classList.add("post");
      postElement.innerHTML = `
        <div class="row">
            <div class="col border rounded-start-4 d-flex flex-column justify-content-center" style="background-color: #efefef;">
                <div class="post-header">
                    <div class="post-title text-uppercase">
                        <h2 class="display-3 fw-bold fst-italic">${post.title}</h2>
                    </div>
                    <div class="post-date ">
                        <p><small class="text-muted"> ${post.created_at}</small></p>
                    </div>
                </div>
                <div class="post-content">
                <p class="fw-bold">${post.descrip}</p>
                <p><small class="text-muted fst-italic">model:</small> <span class="fw-bold">${post.maker} ${post.model}<span></p>
            </div>
            <div class="post-footer">
                <div class="post-author">
                    <p>by <a href="../pages/profile.php?id=${post.user_id}" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" target="_blank"> ${post.user_name}</a></p>
                </div>
                <a href="../../.${post.image_path}" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" download><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
              </svg></a>
            </div>
        </div>
            <div class="col">
                <div class="post-image">
                    <img src="http://localhost:8888/project/server/uploads/${post.image_path}" alt="post image" width="540px" height="540px"  class="rounded-end-4" style="object-fit: cover;">
                </div>
            </div>
        </div>
            
        `;
      postcontainer.appendChild(postElement);
    });
}

window.addEventListener("DOMContentLoaded", () => {
  const id = window.location.search.split("=")[1];
  getThePost(id);
});
