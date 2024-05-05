document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('reclamationChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.titles,  // Ensure titles are fetched from chartData
            datasets: [{
                label: 'Percentage of Reclamations',
                data: chartData.counts,  // Ensure counts are fetched from chartData
                backgroundColor: chartjsColors.backgroundColors,
                borderColor: chartjsColors.borderColors,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(2) + '%'; // Format ticks as percentages with two decimal places
                        },
                        suggestedMax: 100  // This helps ensure the scale goes up to 100%
                    }
                }
            },
            plugins: {
                legend: {
                    display: true  // Optionally control the display of the legend here
                },
                title: {
                    display: true,
                    text: 'Percentage of Reclamations per Oeuvre',
                    font: {
                        size: 18
                    }
                }
            }
        }
    });
});