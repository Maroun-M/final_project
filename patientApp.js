const logout = () => {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "./src/login/logout.php");
  xhr.addEventListener("load", () => {
    if (xhr.status === 200) {
      // redirect to the login page
      window.location.href = "./login.php";
    }
  });
  xhr.send();
};

const logoutButton = document.querySelectorAll(".logout-btn");
logoutButton.forEach((btn) => {
  btn.addEventListener("click", logout);
});

const getPatientData = async () => {
  const res = await fetch("./src/patient/patientData.php");
  const data = await res.json();
  const patient = Object.values(data);
  return patient;
};

const insert = async () => {
  try {
    const patients = await getPatientData();
    if (patients.length !== 0) {
      const dob = document.querySelector("input[name='dob']");
      const location = document.querySelector("select[name='location']");
      const prevPregYes = document.querySelector(
        "input[name='previous-pregnancies'][value='true']"
      );
      const prevPregNo = document.querySelector(
        "input[name='previous-pregnancies'][value='false']"
      );
      const diabetic = document.querySelector("input[name='diabetics']");
      const hypertension = document.querySelector("input[name='hypertension']");
      const LMP = document.querySelector("input[name='LMP']");
      dob.value = patients[0];
      location.value = patients[1];
      if (patients[2] === 0) {
        prevPregNo.checked = true;
      } else {
        prevPregYes.checked = true;
      }
      LMP.value = patients[3];
      const info_data_container = document.querySelector(".form-labels");
      const originalDate = patients[6]; // Date in "year-date-month" format

      // Convert the original date string to a Date object
      const dateParts = originalDate.split("-");
      const year = parseInt(dateParts[0]);
      const month = parseInt(dateParts[1]) - 1; // Month is zero-based
      const day = parseInt(dateParts[2]);
      const convertedDate = new Date(year, month, day);
      const formattedDate = convertedDate.toLocaleDateString("en-GB");

      info_data_container.insertAdjacentHTML(
        "afterend",
        `<div class="form-labels">Gestational age: ${(
          patients[7] / 7
        ).toFixed()} weeks</div><div class="form-labels">Expected due date: ${formattedDate}</div>`
      );
      // info_data_container.innerHTML += ``;

      if (patients[4] == 1) {
        diabetic.checked = true;
      }
      if (patients[5] == 1) {
        hypertension.checked = true;
      }
      const img = document.querySelector("#profile_pic");
      img.src = patients[8];
    }
  } catch (error) {
    console.log(error);
  }
};

window.onload = load = () => {
  if (window.location.pathname === "/ouvatech/userInfo.php") {
    insert();
  }
};

if (window.location.pathname === "/ouvatech/chooseDoctor.php") {
  document.addEventListener("DOMContentLoaded", () => {
    fetchDoctors(1);
    fetchTotal();
  });
}

let fetchTotal = () => {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", `./src/data/allDoctors.php?getTotal=true`);
  xhr.onload = () => {
    if (xhr.status === 200) {
      var total = JSON.parse(xhr.responseText);
      total = total.totalPages;
      createButtons(total);
    }
  };
  xhr.send();
};

let createButtons = (total) => {
  const paginationContainer = document.querySelector(".page-btns-container");
  for (let i = 1; i <= total; i++) {
    const button = document.createElement("button");
    button.innerText = i;
    button.addEventListener("click", () => {
      const doctorsData = document.querySelectorAll(".doctor-info");
      doctorsData.forEach((record) => {
        record.remove();
      });
      fetchDoctors(i);
    });
    paginationContainer.appendChild(button);
  }
};

let fetchDoctors = (i) => {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", `./src/data/allDoctors.php?page=${i}`);
  xhr.onload = () => {
    if (xhr.status === 200) {
      const doctors = JSON.parse(xhr.responseText);
      // Process the doctors data here (e.g., create buttons, update UI)
      displayDrs(doctors);
      chooseDr();
    }
  };
  xhr.send();
};

let displayDrs = (doctors) => {
  const doctorsData = document.querySelector(".doctor-list-data");
  let results = ``;
  if (doctors.length !== 0) {
    doctors.forEach((dr) => {
      results += `
      <div class="grid-item doctor-info view-profile-btn" data-id="${dr.user_id}"><u>View Profile</u></div>
      <div class="grid-item doctor-info">Dr. ${dr.name}</div>
      <div class="grid-item doctor-info">${dr.phone_number}</div>
      <div class="grid-item doctor-info">${dr.education}</div>  
      <div class="grid-item doctor-info">${dr.location}</div>
      <div class="grid-item doctor-info">
          <button class="choose-doctor-btn" data-id="${dr.doctor_id}">Choose</button>
      </div>`;
    });
    doctorsData.innerHTML += results;

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
  }
};

let chooseDr = () => {
  const chooseBtn = document.querySelectorAll(".choose-doctor-btn");
  chooseBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
      const doctorID = event.target.dataset.id;
      const url = "./src/data/allDoctors.php";
      const data = {
        doctor_id: doctorID,
      };

      fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      })
        .then((response) => {
          if (response.ok) {
            const successMessage = "Success"; // Set your success message here
            const params = new URLSearchParams(window.location.search);
            params.set("choice", successMessage);
            const newUrl = `${window.location.pathname}?${params.toString()}`;
            window.location.href = newUrl;
          } else {
            throw new Error("Request failed");
          }
        })
        .catch((error) => {
          console.error(error);
        });
    });
  });
};

// let enableDisableButton = (pregnancyStage) => {

// };

if (window.location.pathname === "/ouvatech/patientMainMenu.php") {
  const weeklyBtn = document.getElementById("weekly-btn");
  const monthlyBtn = document.getElementById("monthly-btn");
  const yearlyBtn = document.getElementById("yearly-btn");
  const testsBtns = document.querySelectorAll(".tests-btn");

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
      `./src/data/patientData.php?range=${option}&type=${dataType}&patientRequest=1`
    );
    xhr.onload = () => {
      if (xhr.status === 200) {
        const testsData = JSON.parse(xhr.responseText);
        const table = document.querySelector(".tests-data-container");
        let results = ``;
        testsData.forEach((data) => {
          if (dataType === "Blood Pressure") {
            results += `<div class="item">Diastolic: ${data.diastolic} Systolic: ${data.systolic}</div>
                <div class="item">${data.date}</div>
                <div class="item">${data.time}</div>
                <div class="delete-btn"  onclick="deleteData(${data.record_id}, '${dataType}')">
                <i class="bi bi-trash-fill"></i></div>`;
          } else if (dataType === "Fetus Data") {
            results += `
                <div class="item">Gestational Age: ${data.gestational_age} Weight: ${data.weight} Heart rate: ${data.heart_rate}</div>
                <div class="item">${data.date}</div>
                <div class="item">${data.time}</div>
                <div class="delete-btn"  onclick="deleteData(${data.record_id}, '${dataType}')">
                <i class="bi bi-trash-fill"></i></div>`;
          } else if (dataType === "Lab Tests") {
            results += `
            <div class="item"><a href="${data.file_path}" target="_blank">Lab Tests</a></div>
            <div class="item">${data.date}</div>
            <div class="item">${data.time}</div>
            <div class="delete-btn"  onclick="deleteData(${data.record_id}, '${dataType}')">
            <i class="bi bi-trash-fill"></i></div> `;
          } else {
            results += `<div class="item">${data.value}</div>
                <div class="item">${data.date}</div>
                <div class="item">${data.time}</div>
                <div class="delete-btn"  onclick="deleteData(${data.record_id}, '${dataType}')">
                <i class="bi bi-trash-fill"></i></div>`;
          }
        });
        table.innerHTML = results;
        createChart(testsData);
      }
    };
    xhr.send();
  }

  // Function to handle the delete button click
  function deleteData(recordId, dataType) {
    var id = parseInt(recordId);
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./src/data/deleteRecord.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = () => {
      // Handle the response after deleting the data
      if (xhr.status === 200) {
        location.reload(); // Reload the page
      }
    };

    xhr.send(`recordId=${id}&dataType=${dataType}`);
  }

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
              backgroundColor: "blue",
              borderColor: "blue",
              borderWidth: 1,
            },
            {
              label: "Weight",
              data: dataValues2,
              backgroundColor: backgroundColor,
              borderColor: borderColor,
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

document.addEventListener("DOMContentLoaded", () => {
  const hamburgerBtn = document.querySelector(".bi-list");
  const closeBtn = document.querySelector(".bi-x-circle");
  const sideBar = document.querySelector(".sidebar");
  hamburgerBtn.addEventListener("click", () => {
    sideBar.classList.add("nav-active");
  });

  closeBtn.addEventListener("click", () => {
    sideBar.classList.remove("nav-active");
  });
});

// forms validation
let fetchTrimester = () => {
  var xhr = new XMLHttpRequest();
  var url = "./src/data/patientTrimester.php"; // Replace with the URL or file path of your PHP script
  var parameter = "patient";
  url += "?" + parameter;
  xhr.open("GET", url, true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = xhr.responseText;
      response = JSON.parse(response);
      const pregnancyStage = response.pregnancy_stage;
      const patientName = response.first_name + " " + response.last_name;
      if (window.location.pathname === "/ouvatech/patientMainMenu.php") {
        const patientSection = document.querySelector("#patient-info-tab");
        let results = `
        <img class="profile_pic_display" src="${response.profile_picture}" alt="">
        <p>Hello, ${patientName}!</p>
        <p>User ID: ${response.id}</p>`;
        if (response.doctor_name === null) {
          results += `<p>Current Doctor: Doctor not assigned yet</p>`;
        } else {
          results += `<p>Current Doctor: Dr. ${response.doctor_name}</p>`;
        }
        patientSection.innerHTML = results;
      }

      if (window.location.pathname === "/ouvatech/heartRate.php") {
        validateHR(pregnancyStage);
      }
      if (window.location.pathname === "/ouvatech/temperature.php") {
        validateTemperature(pregnancyStage);
      }
      if (window.location.pathname === "/ouvatech/bloodGlucose.php") {
        validateGlucose();
      }

      if (window.location.pathname === "/ouvatech/bloodOxygen.php") {
        validateOxygen(pregnancyStage);
      }
      if (window.location.pathname === "/ouvatech/bloodPressure.php") {
        validateBP(pregnancyStage);
      }
    }
  };

  xhr.send();
};

if (window.location.pathname === "/ouvatech/heartRate.php") {
  function validateHR(pregnancyStage) {
    // Clear previous error message
    const hrError = document.getElementById("heart-rate-error");

    // Retrieve input value
    var heartRate = document.getElementById("heart-rate");

    heartRate.addEventListener("input", () => {
      var valid = false;
      // Validate heart rate
      var hrValue = parseInt(heartRate.value);
      if (isNaN(hrValue) || hrValue <= 0) {
        hrError.textContent = "Please enter a valid heart rate.";
        valid = false;
      } else if (pregnancyStage === 1 && hrValue < 63) {
        hrError.textContent = "Bradycardia";
        valid = true;
      } else if (pregnancyStage === 1 && hrValue > 105) {
        hrError.textContent = "Tachycardia";
        valid = true;
      } else if (pregnancyStage === 1 && (hrValue >= 63 || hrValue <= 105)) {
        hrError.textContent = "Normal Heart Rate";
        valid = true;
      } else if (pregnancyStage === 2 && hrValue < 67) {
        hrError.textContent = "Bradycardia";
        valid = true;
      } else if (pregnancyStage === 2 && hrValue > 113) {
        hrError.textContent = "Tachycardia";
        valid = true;
      } else if (pregnancyStage === 2 && (hrValue >= 67 || hrValue <= 113)) {
        hrError.textContent = "Normal Heart Rate";
        valid = true;
      } else if (pregnancyStage === 3 && hrValue < 65) {
        hrError.textContent = "Bradycardia";
        valid = true;
      } else if (pregnancyStage === 3 && hrValue > 114) {
        hrError.textContent = "Tachycardia";
        valid = true;
      } else if (pregnancyStage === 3 && (hrValue >= 65 || hrValue <= 114)) {
        hrError.textContent = "Normal Heart Rate";
        valid = true;
      }
      validateHRBtn(valid);
    });
  }
  let validateHRBtn = (valid) => {
    var addButton = document.getElementById("add-button");
    addButton.disabled = !valid;
  };
}
if (window.location.pathname === "/ouvatech/bloodPressure.php") {
  function validateBP(pregnancyStage) {
    // Clear previous error message
    const bpError = document.getElementById("blood-pressure-error");

    // Retrieve input values
    var systolic = document.getElementById("systolic");
    var diastolic = document.getElementById("diastolic");

    let validate = () => {
      var validBP = false;
      var systolicValue = parseInt(systolic.value);
      var diastolicValue = parseInt(diastolic.value);
      // Validate blood pressure
      if (
        isNaN(systolicValue) ||
        isNaN(diastolicValue) ||
        systolicValue <= 0 ||
        diastolicValue <= 0
      ) {
        validBP = false;
        bpError.textContent =
          "Please enter valid systolic and diastolic values.";
      } else if (
        pregnancyStage === 1 &&
        (systolicValue < 95 || diastolicValue < 56)
      ) {
        bpError.textContent = "Hypotension";
        validBP = true;
      } else if (
        pregnancyStage === 1 &&
        (systolicValue > 138 || diastolicValue > 87)
      ) {
        bpError.textContent = "Hypertension";
        validBP = true;
      } else if (
        pregnancyStage === 1 &&
        systolicValue >= 95 &&
        systolicValue <= 138 &&
        diastolicValue >= 56 &&
        diastolicValue <= 87
      ) {
        bpError.textContent = "Normal";
        validBP = true;
      } else if (
        pregnancyStage === 2 &&
        (systolicValue < 96 || diastolicValue < 57)
      ) {
        bpError.textContent = "Hypotension";
        validBP = true;
      } else if (
        pregnancyStage === 2 &&
        (systolicValue > 136 || diastolicValue > 87)
      ) {
        bpError.textContent = "Hypertension";
        validBP = true;
      } else if (
        pregnancyStage === 2 &&
        systolicValue >= 96 &&
        systolicValue <= 136 &&
        diastolicValue >= 57 &&
        diastolicValue <= 87
      ) {
        bpError.textContent = "Normal";
        validBP = true;
      } else if (
        pregnancyStage === 3 &&
        (systolicValue < 102 || diastolicValue < 62)
      ) {
        bpError.textContent = "Hypotension";
        validBP = true;
      } else if (
        pregnancyStage === 3 &&
        (systolicValue > 144 || diastolicValue > 95)
      ) {
        bpError.textContent = "Hypertension";
        validBP = true;
      } else if (
        pregnancyStage === 3 &&
        systolicValue >= 102 &&
        systolicValue <= 144 &&
        diastolicValue >= 62 &&
        diastolicValue <= 95
      ) {
        bpError.textContent = "Normal";
        validBP = true;
      }
      validateBPBtn(validBP);
    };

    systolic.addEventListener("input", validate);
    diastolic.addEventListener("input", validate);
  }
  let validateBPBtn = (validBP) => {
    var addButton = document.getElementById("add-button");
    addButton.disabled = !validBP;
  };
}

if (window.location.pathname === "/ouvatech/temperature.php") {
  function validateTemperature(pregnancyStage) {
    var error = document.querySelector("#temp-error");
    var input = document.querySelector("#temperature");
    const btn = document.querySelector("#add-button");
    btn.disabled = true;
    document.addEventListener("input", () => {
      var inputValue = parseInt(input.value);
      if (isNaN(inputValue) || inputValue <= 0 || inputValue > 43) {
        btn.disabled = true;

        error.innerHTML = "Please enter a valid temperature.";
      } else if (
        (pregnancyStage === 1 && inputValue > 38) ||
        ((pregnancyStage === 2 || pregnancyStage === 3) && inputValue > 37)
      ) {
        btn.disabled = false;

        error.innerHTML = "Hyperthermia";
      } else if (
        (pregnancyStage === 1 && inputValue < 36) ||
        ((pregnancyStage === 2 || pregnancyStage === 3) && inputValue < 35)
      ) {
        btn.disabled = false;

        error.innerHTML = "Hypothermia";
      } else {
        btn.disabled = false;

        error.innerHTML = "Normal Temperature";
      }
    });
  }
}

if (window.location.pathname === "/ouvatech/bloodGlucose.php") {
  function validateGlucose() {
    const glucoseInput = document.getElementById("glucose");
    const addButton = document.getElementById("add-button");
    addButton.disabled = true;

    glucoseInput.addEventListener("input", function () {
      const glucoseLevel = parseInt(glucoseInput.value);
      const glucoseDetail = document.querySelector(".glucose-detail");
      // Validate glucose value
      if (
        isNaN(glucoseLevel) ||
        !Number.isInteger(parseFloat(glucoseLevel)) ||
        glucoseLevel <= 0 ||
        glucoseLevel >= 220
      ) {
        glucoseDetail.textContent = "Invalid glucose level";
        addButton.disabled = true;
      }
      if (glucoseLevel >= 140) {
        glucoseDetail.textContent = "Hyperglycemia";
        addButton.disabled = false;
      } else if (glucoseLevel <= 60) {
        glucoseDetail.textContent = "Hypoglycemia";
        addButton.disabled = false;
      } else {
        glucoseDetail.textContent = "Normal";
        addButton.disabled = false;
      }
    });
  }
}

if (window.location.pathname === "/ouvatech/bloodOxygen.php") {
  function validateOxygen(pregnancyStage) {
    var error = document.querySelector("#oxygen-error");
    var input = document.querySelector("#oxygen");
    const btn = document.querySelector("#add-button");
    btn.disabled = true;
    document.addEventListener("input", () => {
      var inputValue = parseInt(input.value);
      if (isNaN(inputValue) || inputValue <= 0 || inputValue > 100) {
        btn.disabled = true;
        error.innerHTML = "Please enter a valid oxygen saturation level.";
      } else if (
        (pregnancyStage === 1 && inputValue < 94) ||
        ((pregnancyStage === 2 || pregnancyStage === 3) && inputValue < 93)
      ) {
        btn.disabled = false;
        error.innerHTML = "Hypoxia";
      } else if (inputValue > 99) {
        btn.disabled = false;
        error.innerHTML = "Hyperoxia";
      } else {
        btn.disabled = false;
        error.innerHTML = "Normal Oxygen Saturation";
      }
    });
  }
}

if (window.location.pathname === "/ouvatech/fetus.php") {
  const btn = document.querySelector("#add-button");
  btn.disabled = true;
  function validateFetusData() {
    var fetusWeight = parseFloat(document.querySelector("#fetal-weight").value);
    var fetusHR = parseFloat(document.querySelector("#fetal-heart-rate").value);
    const error = document.querySelector("#fetus-error");
    if (isNaN(fetusWeight) || isNaN(fetusHR)) {
      error.innerHTML = "Please enter a valid value.";
      btn.disabled = true;
    } else {
      error.innerHTML = ``;
      btn.disabled = false;
    }
  }

  var fetusWeight = document.querySelector("#fetal-weight");
  var fetusHR = document.querySelector("#fetal-heart-rate");

  fetusWeight.addEventListener("input", validateFetusData);
  fetusHR.addEventListener("input", validateFetusData);
}

document.addEventListener("DOMContentLoaded", () => {
  fetchTrimester();
});



