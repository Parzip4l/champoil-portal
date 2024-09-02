<head>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
</head>
<body>
    <div id="chart"></div>
    

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                chart: {
                    type: 'line',
                    height: 480
                },
                series: [{
                    name: 'Example Series',
                    data: [10, 20, 30, 40, 50]
                }],
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May']
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

                html2canvas(document.querySelector("#chart")).then(canvas => {
                    var imageData = canvas.toDataURL('image/png');

                    // Send the image data to the server
                    fetch('/save-chart-image', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ image: imageData })
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            alert('Image saved successfully');
                        } else {
                            alert('Failed to save image');
                        }
                    });
                });
        });
    </script>
</body>
