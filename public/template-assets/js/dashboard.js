document.addEventListener("DOMContentLoaded", function() {
    // Example of fetching data dynamically
    document.getElementById("ordersCount").innerText = 8;
    document.getElementById("productsCount").innerText = 20;
    document.getElementById("usersCount").innerText = 5;

    // Chart Example (if Chart.js is available)
    if (window.Chart) {
        var ctx = document.getElementById('dashboardChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Orders", "Products", "Users"],
                datasets: [{
                    label: 'Statistics',
                    data: [8, 20, 5],
                    backgroundColor: ['red', 'blue', 'green']
                }]
            }
        });
    }
});
