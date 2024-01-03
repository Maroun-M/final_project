const headers = new Headers();

if (window.location.pathname === "/ouvatech/bloodGlucose.php") {
  // Get references to the HTML elements
  const weeklyBtn = document.getElementById('weekly-btn');
  const monthlyBtn = document.getElementById('monthly-btn');
  const yearlyBtn = document.getElementById('yearly-btn');
  const glucoseChart = document.getElementById('glucose-chart');

  // Define the chart options
  const options = {
    type: 'line',
    data: {
      labels: [],
      datasets: [{
        label: 'Blood Glucose Level',
        data: [],
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          },
          scaleLabel: {
            display: true,
            labelString: 'mg/dL' // Add the units to the y-axis label
          }
        }],
        xAxes: [{
          type: 'time',
          time: {
            unit: 'day', // Set the time unit to day
            displayFormats: {
              day: 'MMM DD' // Format the date string
            }
          },
          ticks: {
            autoSkip: true,
            maxTicksLimit: 20
          },
          scaleLabel: {
            display: true,
            labelString: 'Date' // Add a label for the x-axis
          }
        }]
      }
    }
  };

  // Initialize the chart
  const chart = new Chart(glucoseChart, options);

  // Add event listeners to the buttons
  document.addEventListener("DOMContentLoaded", () => {
    fetchData('weekly')
  });

  weeklyBtn.addEventListener('click', () => fetchData('weekly'));
  monthlyBtn.addEventListener('click', () => fetchData('monthly'));
  yearlyBtn.addEventListener('click', () => fetchData('yearly'));

// Function to fetch the data based on the selected option
function fetchData(option) {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', `./src/data/bloodGlucoseData.php?option=${option}`);
  xhr.onload = () => {
    if (xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      const labels = [];
      const glucoseLevels = [];
        console.log(data);
      // Loop through the data and format it for the chart
      data.forEach(item => {
        labels.push(item.timestamp);
        glucoseLevels.push(item.glucose_level);
      });

      // Update the chart with the new data
      chart.data.labels = labels;
      chart.data.datasets[0].data = glucoseLevels;
      chart.update();
    }
  };
  xhr.send();
}

  
}
