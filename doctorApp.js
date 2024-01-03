// Call the getPatients function when the page is ready
if (window.location.pathname === "/ouvatech/doctorMainMenu.php") {
  // Function to get the patient data from the server

  const patient_row = document.querySelector(".data-container");
  function getPatients() {
    // Send an AJAX request to the server to get the patient data
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "./src/data/doctorPatients.php", true);
    xhr.setRequestHeader("Content-type", "application/json");
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var patients = JSON.parse(xhr.responseText);
        console.log(patients);
        let results = ``;
        let hypertension;
        let diabetic;
        let prev_preg;
        patients.forEach((patient) => {
          if(patient.hypertension === 1){
            hypertension = "Hypertensive";
          } else {
            hypertension = "Non-hypertensive"
          }
          if(patient.diabetic === 1){
            diabetic = "Diabetic";
          } else {
            diabetic = "Non-diabetic"
          }
          if(patient.previous_pregnancies === 1){
            prev_preg = "Yes";
          } else {
            prev_preg = "None"
          }
          results += `<div class="item">${patient.name}</div>
                    <div class="item">${patient.age}</div>
                    <div class="item">${patient.location}</div>
                    <div class="item">${patient.phone_number}</div>
                    <div class="item">${patient.pregnancy_stage}</div>
                    <div class="item">${diabetic}</div>
                    <div class="item">${hypertension}</div>
                    <div class="item">${prev_preg}</div>
                    
                    <div class="item "><u class="patient-records" data-id=${patient.id}>Records</u></div>`;
        });
        patient_row.innerHTML += results;
        const records_btn = document.querySelectorAll(".patient-records");
        records_btn.forEach((btn) => {
          btn.addEventListener("click", () => {
            const patient_id = event.target.dataset.id;
            window.location.href = "./patientRecords.php?patient=" + patient_id;
          });
        });
      } else if (xhr.readyState === 4) {
        console.log("Error getting patient data: " + xhr.statusText);
      }
    };
    xhr.send();
  }
  document.addEventListener("DOMContentLoaded", function () {
    getPatients();
  });

  //end of patients data for the dr.
}

if (window.location.pathname === "/ouvatech/patientRecords.php") {
  // Get the current URL
  const url = new URL(window.location.href);

  // Get the value of the 'patient' parameter
  const params = new URLSearchParams(url.search);
  const patientId = params.get("patient");

  document.addEventListener("DOMContentLoaded", () => {
    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    xhr.open("GET", `./src/data/patientData.php?patient=${patientId}`, true);

    // Set the response type (optional)
    xhr.responseType = "json";

    // Define the event handler for when the request completes
    xhr.onload = function () {
      if (xhr.status === 200) {
        // Request was successful, process the response
        const data = xhr.response;

        const bloodGlucoseData = data["blood_glucose"];
        const bloodOxygenData = data["blood_oxygen"];
        const bloodPressureData = data["blood_pressure"];

        const fetusData = data["fetus"];
        const hrData = data["heart_rate"];
        const temperatureData = data["temperature"];
        const userFilesData = data["user_files"];
        const table = document.querySelector(".tests-container");
        if (bloodGlucoseData.length !== 0) {
          let results = ``;
          results += `<div class="item">Blood Glucose</div>
              <div class="item">${bloodGlucoseData.glucose_level} mg/dl</div>
              <div class="item">${bloodGlucoseData.date}</div>
              <div class="item">${bloodGlucoseData.time}</div> `;
          table.innerHTML += results;
        }
        if (bloodPressureData.length !== 0) {
          let results = ``;
          results += `<div class="item">Blood Pressure</div>
              <div class="item">${bloodPressureData.systolic} mmHg || ${bloodPressureData.diastolic} mmHg</div>
              <div class="item">${bloodPressureData.date}</div>
              <div class="item">${bloodPressureData.time}</div> `;
          table.innerHTML += results;
        }
        if (bloodOxygenData.length !== 0) {
          let oxygenResults = ``;
          oxygenResults += `<div class="item">Blood Oxygen</div>
              <div class="item">${bloodOxygenData.percentage}%</div>
              <div class="item">${bloodOxygenData.date}</div>
              <div class="item">${bloodOxygenData.time}</div> `;
          table.innerHTML += oxygenResults;
        }
        if (hrData.length !== 0) {
          let hrDataContainer = ``;
          hrDataContainer += `<div class="item">Heart Rate</div>
          <div class="item">${hrData.BPM} BPM</div>
          <div class="item">${hrData.date}</div>
          <div class="item">${hrData.time}</div> `;
          table.innerHTML += hrDataContainer;
        }

        const downloadLink = document.createElement("a");

        // Set the link's href attribute to the file path
        downloadLink.href = userFilesData.file_path;

        // Set the link's text
        downloadLink.innerHTML = "Lab Test";
        let filesData = ``;
        if (userFilesData.length !== 0) {
          filesData += `<div class="item">Lab Tests</div>
          <div class="item"><a href="${userFilesData.file_path}" target="_blank">Lab Tests</a></div>
          <div class="item">${userFilesData.date}</div>
          <div class="item">${userFilesData.time}</div> `;
          table.innerHTML += filesData;
        }
        console.log(temperatureData)
        if (temperatureData.length !== 0) {
          let tempData = ``;
          tempData += `<div class="item">Temperature</div>
  <div class="item">${temperatureData.temp} °C </div>
  <div class="item">${temperatureData.date}</div>
  <div class="item">${temperatureData.time}</div> `;
          table.innerHTML += tempData;
        }

        if (fetusData.length !== 0) {
          let fetusResults = ``;
          fetusResults += `<div class="item">Fetus </div>
          <div class="item">Gestational Age: ${(
            fetusData.gestational_age / 7
          ).toFixed()} || Weight: ${fetusData.weight} g || Heart rate: ${
            fetusData.heart_rate
          } BPM</div>
          <div class="item">${fetusData.date}</div>
          <div class="item">${fetusData.time}</div> `;
          table.innerHTML += fetusResults;
        }
      } else {
        // Request failed, handle the error
        console.error("Request failed. Status:", xhr.status);
      }
    };

    // Send the AJAX request
    xhr.send();
  });

  const graph_btn = document.querySelector(".graphs-btn");
  graph_btn.addEventListener("click", () => {
    window.location.href = "./patientGraphs.php?ID=" + patientId;
  });
}

if (window.location.pathname === "/ouvatech/patientGraphs.php") {
  // Get the current URL
  const url = new URL(window.location.href);

  // Get the value of the 'patient' parameter
  const params = new URLSearchParams(url.search);
  const patientId = params.get("ID");

  const weeklyBtn = document.getElementById("weekly-btn");
  const monthlyBtn = document.getElementById("monthly-btn");
  const yearlyBtn = document.getElementById("yearly-btn");
  const testsBtns = document.querySelectorAll(".tests-btn");
  const dataChart = document.getElementById("data-chart");

  let selectedDataType = "Blood Glucose"; // Default data type

  // Add event listeners to the buttons
  document.addEventListener("DOMContentLoaded", () => {
    fetchData("weekly", selectedDataType);
  });

  testsBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      selectedDataType = btn.innerHTML;
      fetchData("weekly", selectedDataType);
    });
  });

  weeklyBtn.addEventListener("click", () =>
    fetchData("weekly", selectedDataType)
  );
  monthlyBtn.addEventListener("click", () =>
    fetchData("monthly", selectedDataType)
  );
  yearlyBtn.addEventListener("click", () =>
    fetchData("yearly", selectedDataType)
  );

  // Function to fetch the data based on the selected option and data type
  function fetchData(option, dataType) {
    const xhr = new XMLHttpRequest();
    xhr.open(
      "GET",
      `./src/data/patientData.php?range=${option}&ID=${patientId}&type=${dataType}`
    );
    xhr.onload = () => {
      if (xhr.status === 200) {
        const testsData = JSON.parse(xhr.responseText);
        const table = document.querySelector(".tests-data-container");
        let results = `<div class="header ">Values</div>
        <div class="header ">Date</div>
        <div class="header ">Time</div>`;
        testsData.forEach((data) => {
          if (dataType === "Blood Pressure") {
            results += `<div class="item">Diastolic: ${data.diastolic} mmHg Systolic: ${data.systolic} mmHg</div>
                  <div class="item">${data.date}</div>
                  <div class="item">${data.time}</div>`;
          } else if (dataType === "Fetus Data") {
            results += `
                  <div class="item">Gestational Age: ${(
                    data.gestational_age / 7
                  ).toFixed()} Weight: ${data.weight} g Heart rate: ${
              data.heart_rate
            }</div>
                  <div class="item">${data.date}</div>
                  <div class="item">${data.time}</div>`;
          } else if (dataType === "Lab Tests") {
            results += `
              <div class="item"><a href="${data.file_path}" target="_blank">Lab Tests</a></div>
              <div class="item">${data.date}</div>
              <div class="item">${data.time}</div> `;
          } else if (dataType === "Blood Glucose") {
            results += `<div class="item">${data.value} mg/dl</div>
                  <div class="item">${data.date}</div>
                  <div class="item">${data.time}</div>`;
          } else if (dataType === "Blood Oxygen") {
            results += `<div class="item">${data.value}%</div>
                  <div class="item">${data.date}</div>
                  <div class="item">${data.time}</div>`;
          } else if (dataType === "Heart Rate") {
            results += `<div class="item">${data.value} BPM</div>
                  <div class="item">${data.date}</div>
                  <div class="item">${data.time}</div>`;
          } else if (dataType === "Temperature") {
            results += `<div class="item">${data.value} °C</div>
                  <div class="item">${data.date}</div>
                  <div class="item">${data.time}</div>`;
          }
        });
        table.innerHTML = results;

        // Call the createChart function to display the chart

        createChart(testsData);
      }
    };
    xhr.send();
  }

  // Function to create or update the chart
  // Function to create or update the chart
  function createChart(testsData) {
    const canvas = document.getElementById("data-chart");

    // Check if there is an existing chart instance
    const existingChart = Chart.getChart(canvas);
    if (existingChart) {
      existingChart.destroy(); // Destroy the existing chart
    }

    const labels = testsData.map((data) => data.date);
    const dataValues = testsData.map((data) => data.value);

    let backgroundColor = "rgba(255, 105, 180, 0.5)"; // Pink-themed background color
    let borderColor = "rgba(255, 105, 180, 1)"; // Pink-themed border color

    if (selectedDataType === "Lab Tests") {
      const chart = new Chart(canvas, {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            {
              label: selectedDataType,
              data: null,
              backgroundColor: backgroundColor,
              borderColor: borderColor,
              borderWidth: 1,
            },
          ],
        },
        options: {
          // Customize the chart options as needed
        },
      });
    } else if (selectedDataType === "Blood Pressure") {
      const labels = testsData.map((data) => data.date);
      const dataValues1 = testsData.map((data) => data.systolic);
      const dataValues2 = testsData.map((data) => data.diastolic);
      const chart = new Chart(canvas, {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Systolic",
              data: dataValues1,
              backgroundColor: backgroundColor,
              borderColor: borderColor,
              borderWidth: 1,
            },
            {
              label: "Diastolic",
              data: dataValues2,
              backgroundColor: "blue",
              borderColor: "blue",
              borderWidth: 1,
            },
          ],
        },
        options: {
          // Customize the chart options as needed
        },
      });
    } else if (selectedDataType === "Fetus Data") {
      const labels = testsData.map((data) => data.date);
      const dataValues1 = testsData.map((data) => data.gestational_age);
      const dataValues2 = testsData.map((data) => data.weight);
      const dataValues3 = testsData.map((data) => data.heart_rate);

      const chart = new Chart(canvas, {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Gestational Age",
              data: dataValues1,
              backgroundColor: backgroundColor,
              borderColor: borderColor,
              borderWidth: 1,
            },
            {
              label: "Weight",
              data: dataValues2,
              backgroundColor: "blue",
              borderColor: "blue",
              borderWidth: 1,
            },
            {
              label: "Heart Rate",
              data: dataValues3,
              backgroundColor: "red",
              borderColor: "red",
              borderWidth: 1,
            },
          ],
        },
        options: {
          // Customize the chart options as needed
        },
      });
    } else {
      // Default chart creation
      const chart = new Chart(canvas, {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            {
              label: selectedDataType,
              data: dataValues,
              backgroundColor: backgroundColor,
              borderColor: borderColor,
              borderWidth: 1,
            },
          ],
        },
        options: {
          // Customize the chart options as needed
        },
      });
    }
  }
}

if (window.location.pathname === "/ouvatech/doctorInfo.php") {
  document.addEventListener("DOMContentLoaded", function () {
    fetch("./src/doctor/getDoctorInfo.php")
      .then((response) => response.json())
      .then((data) => {
        // Process the retrieved data here
        // Populate the inputs and select with the received data
        if (data.length !== 0) {
          const img = document.getElementById("profile_pic");
          img.src = data.profile_picture;
          document.getElementById("dob").value = data.date_of_birth;
          document.getElementById("location").value = data.location;
          document.getElementById("education").value = data.education;
          document.getElementById("biography").value = data.biography;
          
        }
        if (data.clinics !== 0) {
          const clinics_container = document.querySelector(
            ".doctor-clinics-container"
          );
          let results = ``;
          let index = 1;
          data.clinics.forEach((clinic) => {
            results += `
            <p><u>Clinic ${index}:</u></p>

            <label for="clinic_name">Clinic Name: </label>
            <input type="text" id="clinic_name${clinic.clinic_id}" name="clinic_name" placeholder="" required value="${clinic.clinic_name}" /> <br />
            <label for="clinic_number">Clinic Phone Number: </label>
            <input type="text" id="clinic_number${clinic.clinic_id}" name="clinic_number" placeholder=""  required value="${clinic.clinic_number}"/> <br />
            <label for="clinic_location">Clinic Location: </label>
            <input type="text" id="clinic_location${clinic.clinic_id}" name="clinic_location" placeholder="" required value="${clinic.clinic_location}"/> <br />
            <div class="edit-remove-btns-container">
                <p ><u class="delete-clinic-btn" data-id="${clinic.clinic_id}">Delete</u></p>
                <p ><u class="edit-clinic-btn" data-id="${clinic.clinic_id}">Edit</u></p>

            </div>
            `;
            index++;
          });
          clinics_container.innerHTML = results;

          // Add event listener to the delete button
          var deleteBtn = document.querySelectorAll(".delete-clinic-btn");

          deleteBtn.forEach((btn) => {
            btn.addEventListener("click", () => {
              const confirm_overlay = document.querySelector(
                ".confirmation-overlay"
              );
              confirm_overlay.style.display = "grid";
              const no_btn = document.querySelector("#no-btn");
              no_btn.addEventListener("click", () => {
                confirm_overlay.style.display = "none";
              });
              // Get the data ID from the button
              var dataId = btn.getAttribute("data-id");
              const yes_btn = document.querySelector("#yes-btn");
              yes_btn.addEventListener("click", () => {
                // Get the record ID from the button's data attribute
                var recordId = btn.dataset.id;
                // Create a new XMLHttpRequest object
                var xhr = new XMLHttpRequest();

                // Configure the request
                xhr.open("POST", "./src/doctor/deleteClinic.php", true);

                // Set the Content-Type header for POST request
                xhr.setRequestHeader(
                  "Content-Type",
                  "application/x-www-form-urlencoded"
                );

                // Define the request parameters
                var params = "clinic_id=" + recordId;

                // Send the request
                xhr.send(params);

                // Handle the response
                xhr.onreadystatechange = function () {
                  if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                      // Deletion successful
                      location.reload();
                    } else {
                      // Deletion failed
                      console.error("Failed to delete record.");
                    }
                  }
                };
              });
            });
          });

          // Add event listener to the edit button
          var editBtn = document.querySelectorAll(".edit-clinic-btn");
          editBtn.forEach((btn) => {
            btn.addEventListener("click", () => {
              // Get the record ID from the button's data attribute
              var recordId = btn.dataset.id;

              // Get the input field values
              var clinicName = document.getElementById(
                `clinic_name${recordId}`
              ).value;
              var clinicNumber = document.getElementById(
                `clinic_number${recordId}`
              ).value;
              var clinicLocation = document.getElementById(
                `clinic_location${recordId}`
              ).value;
              // Create a new XMLHttpRequest object
              var xhr = new XMLHttpRequest();

              // Configure the request
              xhr.open("POST", "./src/doctor/updateClinic.php", true);

              // Set the Content-Type header for POST request
              xhr.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
              );

              // Define the request parameters
              var params = `clinic_id=${recordId}&clinic_name=${clinicName}&clinic_number=${clinicNumber}&clinic_location=${clinicLocation}`;

              // Send the request
              xhr.send(params);

              // Handle the response
              xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                  if (xhr.status === 200) {
                    // Update successful
                    location.reload();
                    // Reload the page or perform any necessary actions
                  } else {
                    // Update failed
                    console.error("Failed to update record.");
                  }
                }
              };
            });
          });
        }
      })
      .catch((error) => {
        // Handle any errors that occur during the request
        console.error("Error:", error);
      });
  });

  function addClinicFields() {
    var clinicsContainer = document.querySelector(".form-labels");

    var brElement = document.createElement("br");

    var nameLabel = document.createElement("label");
    nameLabel.setAttribute("for", "clinic_name[]");
    nameLabel.innerHTML = 'Clinic Name: <span class="red">*</span>';

    var nameInput = document.createElement("input");
    nameInput.setAttribute("type", "text");
    nameInput.setAttribute("name", "clinic_name[]");
    nameInput.setAttribute("required", "required");

    var numberLabel = document.createElement("label");
    numberLabel.setAttribute("for", "clinic_number[]");
    numberLabel.innerHTML = 'Clinic Phone Number: <span class="red">*</span>';

    var numberInput = document.createElement("input");
    numberInput.setAttribute("type", "text");
    numberInput.setAttribute("name", "clinic_number[]");
    numberInput.setAttribute("required", "required");

    var locationLabel = document.createElement("label");
    locationLabel.setAttribute("for", "clinic_location[]");
    locationLabel.innerHTML = 'Clinic Location: <span class="red">*</span>';

    var locationInput = document.createElement("input");
    locationInput.setAttribute("type", "text");
    locationInput.setAttribute("name", "clinic_location[]");
    locationInput.setAttribute("required", "required");

    clinicsContainer.appendChild(brElement.cloneNode(false));
    clinicsContainer.appendChild(nameLabel);
    clinicsContainer.appendChild(nameInput);
    clinicsContainer.appendChild(brElement.cloneNode(false));
    clinicsContainer.appendChild(numberLabel);
    clinicsContainer.appendChild(numberInput);
    clinicsContainer.appendChild(brElement.cloneNode(false));
    clinicsContainer.appendChild(locationLabel);
    clinicsContainer.appendChild(locationInput);
    clinicsContainer.appendChild(brElement);
  }

  const addClinicBtn = document.querySelector(".add-clinic-btn");
  addClinicBtn.addEventListener("click", addClinicFields);
  // delete clinic
}
