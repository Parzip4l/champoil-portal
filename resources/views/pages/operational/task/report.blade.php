@extends('layout.master')
    @php 
        $user = Auth::user();
        $dataLogin = json_decode(Auth::user()->permission); 
        $employee = \App\Employee::where('nik', Auth::user()->name)->first(); 
    @endphp
<style>
  /* styles.css */
    .loading-backdrop {
        display: none; /* Initially hidden */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        z-index: 9999; /* High z-index to ensure it covers other elements */
        align-items: center;
        justify-content: center;
        display: flex;
    }

    .loading-spinner {
        text-align: center;
        padding: 20px;
        font-size: 18px;
        color: #fff; /* White text color */
        border: 4px solid rgba(255, 255, 255, 0.3); /* Light border */
        border-radius: 50%;
        border-top: 4px solid #fff; /* White top border for spinner effect */
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite; /* Spin animation */
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@section('content')
<!-- <div id="loadingBackdrop" class="loading-backdrop">
  <div class="loading-spinner"></div>
</div> -->
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                Filter
                
            </div>
            <div class="card-body">
                <div id="treeView"></div>
            </div>
        </div>
    </div>
    <div  class="col-md-9">
        <div class="row">
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size:30px" id="total_titik">0</span>
                    </div>
                    <div class="card-footer">
                        <h6>Jumlah Titik Patroli</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size:30px"  id="total_point">0</span>
                    </div>
                    <div class="card-footer">
                        <h6>Jumlah Point</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size:30px" id="jumlah_shift">0</span>
                    </div>
                    <div class="card-footer">
                        <h6>Jumlah SHIFT</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size:30px" id="patroli_pershift">0</span>
                        
                    </div>
                    <div class="card-footer">
                        <h6>Jumlah Patroli</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div id="chart"></div>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div id="pie"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>


@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
  <!-- <script src="{{ asset('assets/js/fullcalendar.js') }}"></script> -->
@endpush


@push('custom-scripts')

<script>
    // Function to fetch and update dashboard data based on selected filters
    const dashboardData = async (selectedIds = []) => {
        try {
            const groupedFilters = groupByParentId(selectedIds); // Group selected IDs by parent categories
            
            // Make an API request
            const response = await axios.post('/api/v1/dashboard-patroli', { filters: groupedFilters });
            const responseData = response.data.record;

            // Update the UI elements with the response data
            $("#total_titik").text(responseData.total_titik || 0);
            $("#total_point").text(responseData.total_point || 0);
            $("#jumlah_shift").text(responseData.jumlah_shift || 0);
            $("#patroli_pershift").text(responseData.patroli_pershift || 0);

            // Update the charts
            updateGrafikData(responseData);
            updatePieChart(responseData);
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch dashboard data. Please try again later.',
            });
        }
    };

    // Function to group selected IDs by their parent_id
    const groupByParentId = (selectedIds) => {
        const grouped = {};
        selectedIds.forEach(id => {
            const item = rawData.find(data => data.id === id);
            if (item) {
                const parentId = item.parent_id || 'root'; // Use 'root' for items with no parent
                grouped[parentId] = grouped[parentId] || [];
                grouped[parentId].push(id);
            }
        });
        return grouped;
    };

    // Add data for the tree view
    const rawData = {!! json_encode(project_filter($user->project_id)) !!};
    rawData.push(
        { id: 'Yearly', name: "Yearly", parent_id: null },
        { id: 'Monthly', name: "Monthly", parent_id: null },
        { id: 'January', name: "January", parent_id: 'Monthly' },
        { id: 'February', name: "February", parent_id: 'Monthly' },
        { id: 'March', name: "March", parent_id: 'Monthly' },
        { id: 'April', name: "April", parent_id: 'Monthly' },
        { id: 'May', name: "May", parent_id: 'Monthly' },
        { id: 'June', name: "June", parent_id: 'Monthly' },
        { id: 'July', name: "July", parent_id: 'Monthly' },
        { id: 'August', name: "August", parent_id: 'Monthly' },
        { id: 'September', name: "September", parent_id: 'Monthly' },
        { id: 'October', name: "October", parent_id: 'Monthly' },
        { id: 'November', name: "November", parent_id: 'Monthly' },
        { id: 'December', name: "December", parent_id: 'Monthly' },
        // { id: 'Shift', name: "Shift", parent_id: null },
        // { id: 'SHIFT PAGI', name: "SHIFT PAGI", parent_id: 'Shift' },
        // { id: 'SHIFT MIDLE', name: "SHIFT MIDLE", parent_id: 'Shift' },
        // { id: 'SHIFT MALAM', name: "SHIFT MALAM", parent_id: 'Shift' },
    );

    // Initialize the tree view with jstree
    $('#treeView').jstree({
        core: {
            data: rawData.map(item => ({
                id: item.id,
                parent: item.parent_id || '#',
                text: item.name
            })),
            themes: { responsive: true }
        },
        plugins: ["checkbox"],
        checkbox: { keep_selected_style: false }
    });

    // Handle tree view selection changes
    $('#treeView').on('changed.jstree', (e, data) => {
        const selectedIds = data.selected;
        dashboardData(selectedIds);
    });

    // Initial dashboard data fetch
    dashboardData();

    // Declare chart instances
    let lineChart = null;
    let pieChart = null;

    // Update line chart with data
    const updateGrafikData = (responseData) => {
        const percentData = responseData.percent.map(item => {
            const match = item.match(/\d+/);
            return match ? parseFloat(match[0]) : 0;
        });

        const options = {
            chart: { height: 350, type: 'line', stacked: false },
            series: [
                { name: 'Activity', type: 'line', data: responseData.value_shift1 },
                { name: 'Target Activity', type: 'bar', data: responseData.grafik_value },
                { name: 'Percentage', type: 'line', data: percentData }
            ],
            stroke: { curve: 'smooth', width: [2, 2, 2] },
            xaxis: { categories: responseData.grafik_key },
            
            title: { text: 'Patrol Activity', align: 'center' },
            markers: { size: [4, 4, 4] },
            colors: ['#FEB019', '#00E396', '#008FFB'],
            legend: { position: 'top', horizontalAlign: 'right' },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: (value, { seriesIndex }) =>
                        seriesIndex === 2 ? value + '%' : value
                }
            }
        };

        if (lineChart) {
            lineChart.updateOptions(options);
        } else {
            lineChart = new ApexCharts(document.querySelector("#chart"), options);
            lineChart.render();
        }
    };

    // Update pie chart with data
    const updatePieChart = (responseData) => {
        const options = {
            chart: { type: 'pie', height: 350 },
            series: responseData.pie_chart,
            labels: ['Patrol', 'No Patrol'],
            colors: ['#008FFB', '#FF4560'],
            title: { text: 'Chart Statistic', align: 'center' },
            legend: { position: 'bottom' }
        };

        if (pieChart) {
            pieChart.updateOptions(options);
        } else {
            pieChart = new ApexCharts(document.querySelector("#pie"), options);
            pieChart.render();
        }
    };
</script>


@endpush