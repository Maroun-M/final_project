const changePass = () => {
    const oldPass = document.querySelector("#old-pass").value;
    const newPass = document.querySelector("#new-pass").value;
    const confirmNewPass = document.querySelector("#confirm-new-pass").value;

    const xhr = new XMLHttpRequest();

    xhr.open("POST", "./src/login/changePassword.php");
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function () {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          console.log(response.message);
          const feedbackContainer = document.querySelector(".change-pass-feedback");
          feedbackContainer.innerText = response.message;
        } catch (error) {
          console.error("Error parsing JSON response:", error);
        }
      } else {
        console.error("Request failed with status:", xhr.status);
      }
    };

    xhr.onerror = function () {
      console.error("An error occurred during the request.");
    };

    const data = {
      oldPassword: oldPass,
      newPassword: newPass,
      confirmNewPassword: confirmNewPass,
    };

    xhr.send(JSON.stringify(data));
  };

  const changePassBtn = document.querySelector("#change-pass-btn");
  changePassBtn.addEventListener("click", changePass);