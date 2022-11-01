Chart.defaults.pointHitDetectionRadius = 1
Chart.defaults.plugins.tooltip.enabled = false
Chart.defaults.plugins.tooltip.mode = 'index'
Chart.defaults.plugins.tooltip.position = 'nearest'
Chart.defaults.plugins.tooltip.external = coreui.ChartJS.customTooltips
Chart.defaults.defaultFontColor = '#646470'

const random = (min, max) =>
  // eslint-disable-next-line no-mixed-operators
  Math.floor(Math.random() * (max - min + 1) + min)

// const labels = [
//     'Tháng 1',
//     'Tháng 2',
//     'Tháng 3',
//     'Tháng 4',
//     'Tháng 5',
//     'Tháng 6',
//     'Tháng 7',
//     'Tháng 8',
//     'Tháng 9',
//     'Tháng 10',
//     'Tháng 11',
//     'Tháng 12',
//   ];

let configuration = {
    type: 'line',
    options: 
    {
        maintainAspectRatio: false,
        plugins: 
        {
        legend: { display: false }
    },// options
    scales: 
    {
        x: 
        {
            grid: { drawOnChartArea: false }
        },
        y: 
        {
            ticks: 
            {
            beginAtZero: true,
            maxTicksLimit: 5,
            stepSize: Math.ceil(200 / 10),
            max: 250
            }
        }
    },//scales
    elements: 
    {
      line: { tension: 0.4 },
      point: 
      {
        radius: 0,
        hitRadius: 10,
        hoverRadius: 4,
        hoverBorderWidth: 3
      }
    }// elements
  }
};

/**
 * @author Phong-Kaster
 * @since 29-10-2022
 * @return Bar Chart about rate between BOOKING and ALL APPOINTMENTS in last 7 days
 */
function createChartWithAJAX(type, url, request)
{
    let data = {
        request: request
    }
    $.ajax({
      type: type,
      url: url,
      data: data,
      dataType: "JSON",
      success: function(resp) {
          Swal.close();// close loading screen
          if(resp.result == 1)// result = 1
          {
            switch(request) {
                case "appointmentsinlast7days":
                    appointmentIn7days(resp);
                    break;
                case "appointmentandbookinginlast7days":
                    bookingAndAppointmentIn7days(resp);
                    break;
                default:
                  console.log("Request value is invalid!");
              }
          }
          else// result = 0
          {
              title = 'error';
              Swal.fire({
              position: 'center',
              icon: 'warning',
              title: 'Warning',
              text: msg,
              showConfirmButton: true
              });
          }
      },
      error: function(err) {
          Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
      }
  });
}


/**
 * @author Phong-Kaster
 * @since 29-10-2022
 * @returns Chart about quantity of appointments monthly
 */
function appointmentIn7days(response){
    /**Step 1 - declare */
    let labels = [];
    let quantity = [];

    /**Step 2 - setup labels and quantity for each labels */
    for(let i = 0; i < response.data.length; i++)
    {
      labels.push(response.data[i].date);
      quantity.push(response.data[i].appointment);
    }

    /**Step 3 - set the last labels is today */
    labels[ labels.length-1] = "Hôm nay";


    let data = 
    {
        labels: labels,
        datasets: [{
            label: 'Số lượng bệnh nhân',
            backgroundColor: coreui.Utils.getStyle('--cui-success'),
            borderColor: coreui.Utils.getStyle('--cui-success'),
            data: quantity,
        }]
    };

    configuration["data"] = data;
    configuration["type"] = "bar";
    let chart = new Chart( document.getElementById('main-chart') ,configuration);
    return chart;
}


/**
 * @author Phong-Kaster
 * @since 29-10-2022
 * @return Bar Chart about rate between BOOKING and ALL APPOINTMENTS in last 7 days
 */
 function bookingAndAppointmentIn7days(response){
    /**Step 1 - declare */
    let labels = [];
    let quantityAppointment = [];
    let quantityBooking = [];


    /**Step 2 - setup labels and quantity for each labels */
    for(let i = 0; i < response.data.length; i++)
    {
      labels.push(response.data[i].date);
      quantityAppointment.push(response.data[i].appointment);
      quantityBooking.push(response.data[i].booking);
    }

    /**Step 3 - set the last labels is today */
    labels[ labels.length-1 ] = "Hôm nay";

    let data = 
    {
        labels: labels,
        datasets: [
        {
            label: 'Booking',
            backgroundColor: coreui.Utils.hexToRgba(coreui.Utils.getStyle('--cui-info'), 10),
            borderColor: coreui.Utils.getStyle('--cui-info'),
            pointHoverBackgroundColor: '#fff',
            borderWidth: 2,
            data: quantityBooking,
        },
        {
            label: 'Appointment',
            borderColor: coreui.Utils.getStyle('--cui-danger'),
            pointHoverBackgroundColor: '#fff',
            borderWidth: 2,
            data: quantityAppointment,
        },
        {
            label: 'My Third dataset',
            borderColor: coreui.Utils.getStyle('--cui-danger'),
            pointHoverBackgroundColor: '#fff',
            borderWidth: 1,
            borderDash: [8, 5],
            data: [0,0,0,0,0,0,0]
        }
        ]
    };


    /**Step 4 - setup configuration */
    configuration["data"] = data;
    configuration["type"] = "line";
    let chart = new Chart( document.getElementById('booking-and-appointment-chart') ,configuration);
    return chart;
}

/**
 * @author Phong-Kaster
 * @since 29-10-2022
 * there charts below which is used to decorate DASHBOARD page.
 */
const cardChart1 = new Chart(document.getElementById('card-chart1'), {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label: 'My First dataset',
          backgroundColor: 'transparent',
          borderColor: 'rgba(255,255,255,.55)',
          pointBackgroundColor: coreui.Utils.getStyle('--cui-primary'),
          data: [65, 59, 84, 84, 51, 55, 40]
        }
      ]
    },
    options: {
      plugins: {
        legend: {
          display: false
        }
      },
      maintainAspectRatio: false,
      scales: {
        x: {
          grid: {
            display: false,
            drawBorder: false
          },
          ticks: {
            display: false
          }
        },
        y: {
          min: 30,
          max: 89,
          display: false,
          grid: {
            display: false
          },
          ticks: {
            display: false
          }
        }
      },
      elements: {
        line: {
          borderWidth: 1,
          tension: 0.4
        },
        point: {
          radius: 4,
          hitRadius: 10,
          hoverRadius: 4
        }
      }
    }
  })
  
// eslint-disable-next-line no-unused-vars
const cardChart2 = new Chart(document.getElementById('card-chart2'), {
type: 'line',
data: {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
    {
        label: 'My First dataset',
        backgroundColor: 'transparent',
        borderColor: 'rgba(255,255,255,.55)',
        pointBackgroundColor: coreui.Utils.getStyle('--cui-info'),
        data: [1, 18, 9, 17, 34, 22, 11]
    }
    ]
},
options: {
    plugins: {
    legend: {
        display: false
    }
    },
    maintainAspectRatio: false,
    scales: {
    x: {
        grid: {
        display: false,
        drawBorder: false
        },
        ticks: {
        display: false
        }
    },
    y: {
        min: -9,
        max: 39,
        display: false,
        grid: {
        display: false
        },
        ticks: {
        display: false
        }
    }
    },
    elements: {
    line: {
        borderWidth: 1
    },
    point: {
        radius: 4,
        hitRadius: 10,
        hoverRadius: 4
    }
    }
}
})

// eslint-disable-next-line no-unused-vars
const cardChart3 = new Chart(document.getElementById('card-chart3'), {
type: 'line',
data: {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
    {
        label: 'My First dataset',
        backgroundColor: 'rgba(255,255,255,.2)',
        borderColor: 'rgba(255,255,255,.55)',
        data: [78, 81, 80, 45, 34, 12, 40],
        fill: true
    }
    ]
},
options: {
    plugins: {
    legend: {
        display: false
    }
    },
    maintainAspectRatio: false,
    scales: {
    x: {
        display: false
    },
    y: {
        display: false
    }
    },
    elements: {
    line: {
        borderWidth: 2,
        tension: 0.4
    },
    point: {
        radius: 0,
        hitRadius: 10,
        hoverRadius: 4
    }
    }
}
})

// eslint-disable-next-line no-unused-vars
const cardChart4 = new Chart(document.getElementById('card-chart4'), {
type: 'bar',
data: {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April'],
    datasets: [
    {
        label: 'My First dataset',
        backgroundColor: 'rgba(255,255,255,.2)',
        borderColor: 'rgba(255,255,255,.55)',
        data: [78, 81, 80, 45, 34, 12, 40, 85, 65, 23, 12, 98, 34, 84, 67, 82],
        barPercentage: 0.6
    }
    ]
},
options: {
    maintainAspectRatio: false,
    plugins: {
    legend: {
        display: false
    }
    },
    scales: {
    x: {
        grid: {
        display: false,
        drawTicks: false

        },
        ticks: {
        display: false
        }
    },
    y: {
        grid: {
        display: false,
        drawBorder: false,
        drawTicks: false
        },
        ticks: {
        display: false
        }
    }
    }
}
})


function getQuantityWithAJAX(type, url, params)
{
    //console.log(params);
    // console.log(type);
    // console.log(url);
    $.ajax({
      type: "GET",
      url: url,
      data: params,
      dataType: "JSON",
      success: function(resp) {
          Swal.close();// close loading screen
          if(resp.result == 1)// result = 1
          {
             let quantity = resp.quantity;
             $("#"+type).text(quantity);
          }
          else// result = 0
          {
              console.log(resp.msg);
              // title = 'error';
              // Swal.fire({
              // position: 'center',
              // icon: 'warning',
              // title: 'Warning',
              // text: resp.msg,
              // showConfirmButton: true
              // });
          }
      },
      error: function(err) {
          if(err.status != 200)
          {
            console.log(err);
            //Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
          }
      }
  });
}

function getDoctorInfoWithAJAX(url, params)
{
    /**AJAX */
    $.ajax({
      type: "GET",
      url: url,
      data: params,
      dataType: "JSON",
      success: function(resp) {
          if(resp.result == 1)// result = 1
          {
              createDoctorTable(resp);
          }
          else// result = 0
          {
              // title = 'error';
              // Swal.fire({
              //   position: 'center',
              //   icon: 'warning',
              //   title: 'Warning',
              //   text: msg,
              //   showConfirmButton: true
              // });
          }
      },
      error: function(err) {
          Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
      }
    })//end AJAX
}

function createDoctorTable(resp)
{
    
    /** loop resp to append into table */
    for(let i=0; i< resp.data.length; i++)
    {
        let avatar = resp.data[i].avatar ? resp.data[i].avatar : "default_avatar.jpg";
        let name = resp.data[i].name;
        let createAt = resp.data[i].create_at;
        let speciality = resp.data[i].speciality.name;
        let email = resp.data[i].email;
        let phone = resp.data[i].phone;
        let role = resp.data[i].role;
        switch(role){
            case "member":
              role = "Bác sĩ";
              break;
            case "admin":
              role = "Trưởng khoa";
              break;
            
            case "supporter":
              role = "Hỗ trợ viên";
              break;
            default:
              role = "Bác sĩ";
              break;
        }
        let updateAt = resp.data[i].update_at;

       
        let element = `<tr class="align-middle">
                    <td class="text-center">
                      <div class="avatar avatar-md">
                        <img class="avatar-img" src="${API_URL}/assets/uploads/${avatar}" alt="${email}">
                        <!-- <span class="avatar-status bg-success"></span> -->
                      </div>
                    </td>

                    <td>
                      <div class="fw-semibold">${name}</div>
                      <div class="small text-medium-emphasis fw-semibold">Bắt đầu làm việc: ${createAt}</div>
                    </td>

                    <td class="text-center">
                      <div class="fw-semibold">${speciality}</div>
                    </td>

                    <td>
                      <div class="clearfix">
                        <div class="fw-semibold">${email} | ${phone}</div>
                      </div>
                    </td>

                    <td class="text-center">
                        <div class="fw-semibold">${role}</div>
                    </td>

                    <!-- UPDATE AT -->
                    <td>
                      <!-- <div class="small text-medium-emphasis">Last login</div> -->
                      <div class="fw-semibold">${updateAt}</div>
                    </td>

                    <td>
                      <div class="dropdown">
                        <button class="btn btn-transparent p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <svg class="icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-options"></use>
                          </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#">Info</a><a class="dropdown-item" href="#">Edit</a><a class="dropdown-item text-danger" href="#">Delete</a></div>
                      </div>
                    </td>
                  </tr>`;

        $("div.table-responsive tbody").append(element);
    }
}