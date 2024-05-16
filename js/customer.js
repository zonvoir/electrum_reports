// customer.js

// Function to load customer data and populate a select element
function loadCustomerData() {
  fetch("functions/get_customer_data.php")
    .then((response) => response.json()) // Assuming the PHP file returns JSON data
    .then((data) => {
      const select = document.getElementById("customerSelect"); // Replace with your select element's ID
      data.forEach((customer) => {
        const option = document.createElement("option");
        option.value = customer.id; // Adjust these properties based on your data
        option.text = customer.name;
        if (option) {
          select.appendChild(option);
        }
      });
    })
    .catch((error) => {
      console.error("Error loading customer data:", error);
    });
}

// Call the function to load customer data
loadCustomerData();
