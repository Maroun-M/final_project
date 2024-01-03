if (window.location.pathname === "/ouvatech/doctorProfile.php") {
  // Get the ID parameter from the URL
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get("ID");

  // Construct the request URL with the ID parameter
  const url = `./src/doctor/getDoctorInfo.php?dr_user_id=${id}`;

  // Send the GET request
  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      const dob = new Date(data.date_of_birth);
      const today = new Date();
      // Calculate the difference in years
      let age = today.getFullYear() - dob.getFullYear();
      // Check if the birthday has already occurred this year
      const hasBirthdayOccurred =
        today.getMonth() > dob.getMonth() ||
        (today.getMonth() === dob.getMonth() &&
          today.getDate() >= dob.getDate());

      // If the birthday has not occurred yet, subtract 1 from the age
      if (!hasBirthdayOccurred) {
        age--;
      }
      const profile_container = document.querySelector(
        ".user-profile-info-container"
      );
      const user_img = document.querySelector("#user_profile_picture");
      user_img.src = data.profile_picture;
      let results = ``;
      console.log(data);
      results = `
        <p class="user-profile-info">Dr. ${data.first_name} ${data.last_name}</p>
        <p class="user-profile-info"><u>Education:</u> ${data.education}</p>
        <p class="user-profile-info"><u>Biography:</u> ${data.biography}</p>
        <p class="user-profile-info"><u>Age:</u> ${age}</p>
        <p class="user-profile-info"><u>Location:</u> ${data.location}</p>
        <p class="user-profile-info"><u>Email:</u> ${data.email}</p>
        
        `;
        try {
          if(data.clinics.length != 0 ){
            let counter = 0;
            data.clinics.forEach(clinic => {
                counter++
                results+= `
            <p class="user-profile-info"><u>Clinic ${counter}:</u>  </p>
            <p class="user-profile-info">Clinic Name: ${clinic.clinic_name}</p>
            <p class="user-profile-info">Clinic Location: ${clinic.clinic_location}</p>
            <p class="user-profile-info">Clinic Number: ${clinic.clinic_number}</p>
                
                `
            });
          }
        } catch (error) {
          
        }
       

      profile_container.innerHTML = results;
    })
    .catch((error) => {
      // Handle any errors
      console.error(error);
    });
}
