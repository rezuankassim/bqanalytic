<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BQAnalytic</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-200 sans text-gray-700">
    <div class="container w-full mx-auto py-8">
        <div class="flex items-center px-2 py-2 border-b border-gray-500">
            <h2 class="flex-1 font-semibold tracking-wider uppercase">Analytics</h2>
            <div class="flex">
                <button class="bg-indigo-300 hover:bg-indigo-500 rounded text-white font-bold py-1 px-2">
                    <svg class="fill-current w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M487.976 0H24.028C2.71 0-8.047 25.866 7.058 40.971L192 225.941V432c0 7.831 3.821 15.17 10.237 19.662l80 55.98C298.02 518.69 320 507.493 320 487.98V225.941l184.947-184.97C520.021 25.896 509.338 0 487.976 0z"/></svg>
                </button>
                <div class="relative">
                    <input type="text" class="py-1 px-2 ml-2 pl-6 rounded" placeholder="Date" id="date">
                    <div class="absolute top-0 left-0 ml-3 mt-2 text-gray-500">
                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M12 192h424c6.6 0 12 5.4 12 12v260c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V204c0-6.6 5.4-12 12-12zm436-44v-36c0-26.5-21.5-48-48-48h-48V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H160V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H48C21.5 64 0 85.5 0 112v36c0 6.6 5.4 12 12 12h424c6.6 0 12-5.4 12-12z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap w-full mt-6">
            <div class="w-full md:w-1/2 xl:w-1/4 pl-2 p-2 md:pl-0">
                <div class="bg-white border rounded shadow p-2">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-red-300 text-white">
                                <svg class="fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="text-grey">Users</h5>
                            <h3 class="text-md" id="activeUserCount">0</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/4 pl-2 p-2 md:pl-0">
                <div class="bg-white border rounded shadow p-2">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-orange-300 text-white">
                                <svg class="fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M272 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h224c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM160 480c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="text-grey">Mobile Users</h5>
                            <h3 class="text-md" id="activeMobileUserCount">0</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/4 pl-2 p-2 md:pl-0">
                <div class="bg-white border rounded shadow p-2">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-green-300 text-white">
                                <svg class="fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M400 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM224 480c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="text-grey">Tablet Users</h5>
                            <h3 class="text-md" id="activeTabletUserCount">0</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/4 pl-2 p-2 md:pl-0">
                <div class="bg-white border rounded shadow p-2">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-blue-300 text-white">
                                <svg class="fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M528 0H48C21.5 0 0 21.5 0 48v320c0 26.5 21.5 48 48 48h192l-16 48h-72c-13.3 0-24 10.7-24 24s10.7 24 24 24h272c13.3 0 24-10.7 24-24s-10.7-24-24-24h-72l-16-48h192c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zm-16 352H64V64h448v288z"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="text-grey">Desktop Users</h5>
                            <h3 class="text-md" id="activeDektopUserCount">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="w-full md:w-2/4 bg-white shadow rounded m-2 md:m-0 md:mt-5 md:mr-2">
                <div class="card-header border-b border-gray-500">
                    <div class="card-title px-4 py-4">
                        <h2 class="text-md font-semibold tracking-wider uppercase">Active Users by Platform</h2>
                    </div>
                </div>
                <div class="card-body">
                    <div class="content px-4 py-4">
                        <canvas id="activeUsersByPlatformChart" class="w-full"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="w-full md:w-2/4  bg-white shadow rounded m-2 md:m-0 md:mt-5 md:mr-2">
                <div class="card-header border-b border-gray-500">
                    <div class="card-title px-4 py-4">
                        <h2 class="text-md font-semibold tracking-wider uppercase">Event Count by Users</h2>
                    </div>
                </div>
                <div class="card-body">
                    <div class="content px-4 py-4">
                        <canvas id="allEventWithEventCountPie" class="w-full"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>

    <script>
        let activeUsersByPlatformChart;
        let allEventWithEventCountPie;

        $(function() {
            $('#date').daterangepicker({
                "autoApply": true,
                "startDate": moment().subtract('1', 'days'),
                "endDate": moment().subtract('1', 'days'),
                "opens": "left",
                "locale": {
                    "format": "DD/MM/YYYY",
                },
            });

            $('#date').on('change', function () {
                getAnalytic()
            })

            activeUsersByPlatformChart = new Chart(document.getElementById('activeUsersByPlatformChart'), {
                type: 'line',
                data: {},
                options: {
                    scales: { 
                        xAxes: [{ 
                            offset:true, 
                        }] 
                    }
                }
            });

            allEventWithEventCountPie = new Chart(document.getElementById('allEventWithEventCountPie'), {
                type: 'doughnut',
                data: {}
            })

            getAnalytic()
        })

        function getAnalytic()
        {
            console.log($('#date').val())
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                url: "{{ route('bqanalytics.analytic') }}",
                type: 'POST',
                data: {
                    'range': $('#date').val()
                },
                success: function (response) {
                    setData(response)
                } 
            })
        }

        function setData(data)
        {
            if (data.activeUsers) {
                $('#activeUserCount').text(data.activeUsers.active_user_count)
                $('#activeMobileUserCount').text(data.activeUsers.active_mobile_user_count)
                $('#activeTabletUserCount').text(data.activeUsers.active_tablet_user_count)
                $('#activeDesktopUserCount').text(data.activeUsers.active_desktop_user_count)
            }

            if (data.activeUsersByPlatform) {
                removeData(activeUsersByPlatformChart)
                activeUsersByPlatformChart.data.datasets.push({
                    label: 'IOS Platform',
                    data: [],
                    borderColor: '#f56565',
                    borderWidth: 0,
                    fill: false,
                })
                activeUsersByPlatformChart.data.datasets.push({
                    label: 'Android Platform',
                    data: [],
                    borderColor: '#fbd38d',
                    borderWidth: 0,
                    fill: false,
                })
                activeUsersByPlatformChart.data.datasets.push({
                    label: 'Web Platform',
                    data: [],
                    borderColor: '#9ae6b4',
                    borderWidth: 0,
                    fill: false,
                })
                data.activeUsersByPlatform.forEach((value) => {
                    activeUsersByPlatformChart.data.labels.push(value.date)
                    activeUsersByPlatformChart.data.datasets[0].data.push(value.ios_platform)
                    activeUsersByPlatformChart.data.datasets[1].data.push(value.android_platform)
                    activeUsersByPlatformChart.data.datasets[2].data.push(value.other_platform)
                })
                activeUsersByPlatformChart.update()
            }

            if (data.allEventWithEventCount) {
                removeData(allEventWithEventCountPie)
                allEventWithEventCountPie.data.datasets.push({
                    data: [],
                    backgroundColor: [
                        "#718096", 
                        "#feb2b2", 
                        "#9b2c2c", 
                        "#f6ad55",
                        "#c05621",
                        "#ecc94b",
                        "#c6f6d5",
                        "#2f855a",
                        "#4fd1c5",
                        "#285e61",
                        "#90cdf4",
                        "#2b6cb0",
                    ]  
                })
                data.allEventWithEventCount.forEach((value) => {
                    allEventWithEventCountPie.data.labels.push(value.event_name)
                    allEventWithEventCountPie.data.datasets[0].data.push(value.event_count) 
                })
                allEventWithEventCountPie.update()
            }
            
        }

        function addData(chart, label, data) {
            chart.data.labels.push(label);
            chart.data.datasets.forEach((dataset) => {
                dataset.data.push(data);

            });
            chart.update();
        }

        function removeData(chart) {
            chart.data.labels = [];
            chart.data.datasets = [];
            chart.update();
        }
    </script>
</body>
</html>