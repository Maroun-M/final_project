if (window.location.pathname === "/ouvatech/admin.php") {
  // Function to make an AJAX request to the server
  function getUsersData(page, perPage, name) {
    // Prepare the AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open(
      "GET",
      "./src/admin/getUsers.php?page=" +
        page +
        "&perPage=" +
        perPage +
        "&name=" +
        name +
        "&type=" +
        1,
      true
    );

    // Handle the AJAX response
    xhr.onload = function () {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        var totalPages = response.totalPages;
        var data = response.data;
        console.log(data);
        let results = `<div class="header">Name</div>
        <div class="header">Age</div>
        <div class="header">Location</div>
        <div class="header">Phone Number</div>
        <div class="header">Medical Record</div>
        <div class="header">Delete</div>`;
        data.forEach((element) => {
          results += `      
            <div class="item">${element.full_name}</div>

            <div class="item">${element.age}</div>
            <div class="item">${element.location}</div>
            <div class="item">${element.phone_number}</div>
            
            <div class="item "><a href="./patientRecords.php?patient=${element.id}"><u class="patient-records">Records</u></a>
            </div>
            <div class="item "><i class="bi bi-trash delete-btn delete-users" style="border:none;" data-id=${element.id}></i>
            </div>`;
        });
        const patientsDiv = document.querySelector(".admin-patients-holder");
        patientsDiv.innerHTML = results;
        // Generate pagination buttons

       

        const delete_btns_patients = document.querySelectorAll(
          ".admin-patients-holder .delete-users"
        );
        deleteUser(delete_btns_patients, (delete_btns_doctors = null));
        generatePaginationButtons(totalPages, page);
      } else {
        console.log("Error: " + xhr.status);
      }
    };

    // Send the AJAX request
    xhr.send();
  }

  const patientSearch = document.querySelector("#search-patients");
  const paginationContainer = document.querySelector(".pagination-container");
  let currentPage = 1;

  // Function to search patients by name
  function searchByName() {
    var patientName = patientSearch.value;
    getUsersData(currentPage, 10, patientName);
  }

  // Event listener for patient search
  patientSearch.addEventListener("input", searchByName);

  // Function to generate pagination buttons
  function generatePaginationButtons(totalPages, currentPage) {
    var threshold = 10; // Maximum number of pages to show buttons for

    paginationContainer.innerHTML = ""; // Clear the pagination container

    if (totalPages <= threshold) {
      // If the total number of pages is less than or equal to the threshold
      for (var i = 1; i <= totalPages; i++) {
        var button = createPaginationButton(i, currentPage);
        paginationContainer.appendChild(button);
      }
    } else {
      paginationContainer.style.display = "none"; // Hide the pagination container
    }
  }

  // Helper function to create a pagination button
  function createPaginationButton(pageNumber, currentPage) {
    var button = document.createElement("button");
    button.textContent = pageNumber;
    if (pageNumber === currentPage) {
      button.style.background = "rgba(208, 22, 180, 0.664)";
      button.style.color = "#fff";
    }
    button.addEventListener("click", function () {
      currentPage = parseInt(this.textContent);
      searchByName();
    });
    return button;
  }

  document.addEventListener("DOMContentLoaded", () => {
    // Initial function call to fetch and display the data
    getUsersData(currentPage, 10, "");
  });

  // doctor table

  // Function to make an AJAX request to the server
  function getDoctorData(page, perPage, name) {
    // Prepare the AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open(
      "GET",
      "./src/admin/getUsers.php?page=" +
        page +
        "&perPage=" +
        perPage +
        "&name=" +
        name +
        "&type=" +
        2,
      true
    );

    // Handle the AJAX response
    xhr.onload = function () {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        var totalPages = response.totalPages;
        var data = response.data;
        let results = `
      <div class="header">Profile</div>
      
      <div class="header">Name</div>
        <div class="header">Age</div>
        <div class="header">Location</div>
        <div class="header">Education</div>
        <div class="header">Phone Number</div>
        <div class="header">Delete</div>`;
        console.log(data);
        data.forEach((element) => {
          results += `
        <div class="item "><u class="view-profile-btn" data-id="${element.id}">View Profile</u></div>

        <div class="item">${element.full_name}</div>
            <div class="item">${element.age}</div>
            <div class="item">${element.location}</div>
            <div class="item">${element.education}</div>
            <div class="item">${element.phone_number}</div>
            
            <div class="item "><i class="bi bi-trash delete-btn delete-users" style="border:none;" data-id=${element.id}></i>
            </div>`;
        });
        const doctorsDiv = document.querySelector(".admin-doctors-holder");
        doctorsDiv.innerHTML = results;


        var viewBtns = document.querySelectorAll(".view-profile-btn");
        viewBtns.forEach((btn) => {
          btn.addEventListener("click", () => {
            let doctorID = btn.dataset.id;
            // Assuming you want to navigate to the "destination.html" page and add a parameter called "param" with a value of "example"
            const destinationUrl = "./doctorProfile.php";
            const parameterName = "ID";
            const parameterValue = doctorID;

            // Construct the new URL with the parameter
            const urlWithParameter = `${destinationUrl}?${parameterName}=${parameterValue}`;

            // Navigate to the new URL
            window.location.href = urlWithParameter;
          });
        });
        const delete_btns_doctors = document.querySelectorAll(
          ".admin-doctors-holder .delete-users"
        );
        deleteUser((delete_btns_patients = null), delete_btns_doctors);

        generateDrButtons(totalPages, page);
      } else {
        console.log("Error: " + xhr.status);
      }
    };

    // Send the AJAX request
    xhr.send();
  }

  const doctorSearch = document.querySelector("#search-doctors");
  const paginatedDoctors = document.querySelector(".dr-pagination-container");
  let currPage = 1;

  // Function to search patients by name
  function searchByDrName() {
    var doctorName = doctorSearch.value;
    getDoctorData(currPage, 10, doctorName);
  }

  // Event listener for patient search
  doctorSearch.addEventListener("input", searchByDrName);

  // Function to generate pagination buttons
  function generateDrButtons(totalPages, currPage) {
    var threshold = 10; // Maximum number of pages to show buttons for

    paginatedDoctors.innerHTML = ""; // Clear the pagination container

    if (totalPages <= threshold) {
      // If the total number of pages is less than or equal to the threshold
      for (var i = 1; i <= totalPages; i++) {
        var button = createDrPaginationButton(i, currPage);
        paginatedDoctors.appendChild(button);
      }
    } else {
      paginatedDoctors.style.display = "flex"; // Show the pagination container
      var startPage = Math.max(currPage - Math.floor(threshold / 2), 1);
      var endPage = Math.min(startPage + threshold - 1, totalPages);

      if (endPage - startPage < threshold - 1) {
        startPage = Math.max(endPage - threshold + 1, 1);
      }

      for (var i = startPage; i <= endPage; i++) {
        var button = createDrPaginationButton(i, currPage);
        paginatedDoctors.appendChild(button);
      }
    }
  }

  // Helper function to create a pagination button
  function createDrPaginationButton(pageNumber, currPage) {
    var button = document.createElement("button");
    button.textContent = pageNumber;
    if (pageNumber === currPage) {
      button.style.background = "rgba(208, 22, 180, 0.664)";
      button.style.color = "#fff";
    }
    button.addEventListener("click", function () {
      currPage = parseInt(this.textContent);
      searchByDrName();
    });
    return button;
  }

  document.addEventListener("DOMContentLoaded", () => {
    // Initial function call to fetch and display the data
    getDoctorData(currPage, 10, "");
  });

  // delete button with overlay for confirmation

  let deleteUser = (delete_btns_patients, delete_btns_doctors) => {
    var delete_btns = [];

    if (delete_btns_patients) {
      delete_btns = delete_btns.concat(Array.from(delete_btns_patients));
    }

    if (delete_btns_doctors) {
      delete_btns = delete_btns.concat(Array.from(delete_btns_doctors));
    }

    delete_btns.forEach((btn) => {
      btn.addEventListener("click", () => {
        // Confirmation overlay logic here
        const confirm_overlay = document.querySelector(".confirmation-overlay");
        confirm_overlay.style.display = "grid";
        const no_btn = document.querySelector("#no-btn");
        no_btn.addEventListener("click", () => {
          confirm_overlay.style.display = "none";
        });
        // Get the data ID from the button
        var dataId = btn.getAttribute("data-id");
        const yes_btn = document.querySelector("#yes-btn");
        yes_btn.addEventListener("click", () => {
          console.log(dataId);
          // Send the POST request to deleteUser.php
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "./src/admin/deleteUser.php", true);
          xhr.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
          );

          xhr.onload = function () {
            if (xhr.status === 200) {
              location.reload();
              // Handle the response from the server
              if (response.success) {
              } else {
                // Error deleting user
                // Handle the error and display an appropriate message
              }
            } else {
              console.log("Error: " + xhr.status);
            }
          };

          // Prepare the data to send in the request body
          var params = "id=" + encodeURIComponent(dataId);

          // Send the POST request
          xhr.send(params);
        });
      });
    });
  };
}
