const deleteBtn = document.getElementById("delete-profile-btn");
deleteBtn.addEventListener("click", () => {
  const confirm_overlay = document.querySelector(".confirmation-overlay");
  confirm_overlay.style.display = "grid";
  const no_btn = document.querySelector("#no-btn");
  no_btn.addEventListener("click", () => {
    confirm_overlay.style.display = "none";
  });
  const yes_btn = document.querySelector("#yes-btn");
  yes_btn.addEventListener("click", () => {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "./src/data/deletePicture.php", true);
    
    xhr.onload = function () {
      if (xhr.status === 200) {
        var response = xhr.responseText;
        location.reload();
        return true;
      } else {
        // Request failed
        return false
        // Handle the error or display an appropriate message
      }
    };

    xhr.onerror = function () {
      // An error occurred during the request
      return false
      // Handle the error or display an appropriate message
    };

    xhr.send();
  });
});
