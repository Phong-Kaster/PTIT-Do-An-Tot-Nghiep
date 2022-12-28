/**
 * @author Phong-Kaster
 * @since 04-11-2022
 * setup date picker for the "DATE" fiend in APPOINTMENTS table
 */
 function setupDatePicker()
 {
     let today = new Date();
     let year = today.getFullYear();
     let month = today.getMonth()+1;
     let day = today.getDate();
     if( month < 10)
     {
         month = "0" + month;
     }
     if( day < 10)
     {
         day = "0" + day;
     }
 
     date = year + "-" + month + "-" + day;
     $("#datepicker").val(date);
     $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd'});
 }



function setupDatePickerForPatientBirthday()
{
    let today = new Date();
     let year = today.getFullYear();
     let month = today.getMonth()+1;
     let day = today.getDate() - 1;
     if( month < 10)
     {
         month = "0" + month;
     }
     if( day < 10)
     {
         day = "0" + day;
     }
 
     date = year + "-" + month + "-" + day;
     $("#patient-birthday").val(date);
     $("#patient-birthday").datepicker({ dateFormat: 'yy-mm-dd'});
}



/**
 * @author Phong-Kaster
 * @since 04-11-2022
 * set up time picker for the "APPOINTMENT_DATE" field in APPOINTMENT table
 */
function setupTimePicker()
{

    $('#appointment-time').val("");
    $('#appointment-time').datetimepicker({
        datepicker:false,
        format:'Y-m-d H:i',
        allowTimes:['08:00','09:00','10:00','11:00','12:00',
                    '14:00','15:00','16:00']
    });
}


/**
 * @author Phong-Kaster 
 * @since 04-01-2022
 */
function reset()
{
    $("#speciality").val('');
    $("#doctor").val("");
    $("#patient-name").val("");
    $("#patient-phone").val("");
    $("#datepicker").val("");
    $("#appointment-time").val("");
    $("#status").val("");
    $("#patient-reason").val("");
    $("#patient-birthday").val("");
    $("#patient-id").val("");
}



/**
 * @author Phong-Kaster
 * @since 04-01-2022
 * set up button CONFIRM and CANCEL 
 */
function setupButton(id)
{
    /**Step 1 - define URL */
    let method = "POST";
    let url = API_URL+"/appointments/";
    let info = {};
    if( id > 0)
    {
        url = API_URL+`/appointments/${id}`;
        method = "PUT";
        info["patient_id"] = id;
    }


    /**BUTTON CONFIRM*/
    $("#button-confirm").click(function(){
        Swal
        .fire({
            title: 'Bạn chắc chắn muốn thực hiện hành động ngày',
            text: "Hành động này không thể khôi phục sau khi thực hiện",
            icon: 'question',
            confirmButtonText: 'Xác nhận',
            confirmButtonColor: '#FF0000',
            cancelButtonColor: '#0000FF',
            cancelButtonText: 'Hủy',
            reverseButtons: false,
            showCancelButton: true
        })
        .then((result) => 
            {
                if (result.isConfirmed) 
                {
                    info = getNecessaryInfo();

                    let doctorId = info["doctor_id"];
                    if( doctorId > 0)
                    {
                        isDoctorBusy(method, url, info);
                    }
                    else
                    {
                        sendAppointmentRequest(method, url, info); 
                    } 
                } 
                else
                {
                    Swal.close();
                }
            });// end Swal
    });


    /**BUTTON RESET */
    $("#button-reset").click(function(){
        reset();
    })


    /**BUTTON CANCEL */
    $("#button-cancel").click(function(){
        Swal
        .fire({
            title: 'Bạn chắc chắn muốn huỷ bỏ',
            text: "Hành động này không thể khôi phục sau khi thực hiện",
            icon: 'question',
            confirmButtonText: 'Xác nhận',
            confirmButtonColor: '#FF0000',
            cancelButtonColor: '#0000FF',
            cancelButtonText: 'Hủy',
            reverseButtons: false,
            showCancelButton: true
        })
        .then((result) => 
            {
                if (result.isConfirmed) 
                {
                    window.location.href = `${APP_URL}/dashboard`;
                } 
                else
                {
                    Swal.close();
                }
            });// end Swal
    });
}



/**
 * @author Phong-Kaster
 * @since 04-11-2022
 * get necessary info
 */
let bookingId = 0;
let serviceId = 0;
let doctorId = 0;
function getNecessaryInfo()
{
    let doctor = $("#doctor :selected").val();
    let patientName = $("#patient-name").val();
    let patientPhone = $("#patient-phone").val();
    
    let appointmentTime = $("#appointment-time").val();
    let date = $("#datepicker").val();
    if( appointmentTime )
    {
        date = appointmentTime.slice(0,10);
    }
    let patientBirthday = $("#patient-birthday").val();
    let status = $("#status").val();
    let patientReason = $("#patient-reason").val();
    let patientId = $("#patient-id").val();
    serviceId = $("#service").val();
    doctorId = $("#doctor").val();

    let data = {
        doctor_id: doctor,
        patient_name: patientName,
        patient_phone: patientPhone,
        patient_reason: patientReason,
        date: date,
        appointment_time: appointmentTime,
        status: status,
        patient_birthday: patientBirthday,
        patient_id: patientId,
        booking_id: bookingId,
        service_id: serviceId,
        doctor_id: doctorId
    }

    return data;
}



/**
 * @author Phong-Kaster
 * @since 04-11-2022
 * send CREATE or UPDATE request to server
 */
function sendAppointmentRequest(method, url, data)
{
    if(!url)
    {
        showMessageWithButton('error', 'Thất bại', 'Không có đường dẫn hợp lệ');
    }

    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            showMessageWithButton('success','Thành công','Yêu cầu đã được hoàn tất !');
            if(method ==='POST')// if create then reset
            {
                reset();
            }
            
        }
        else
        {
            showMessageWithButton('error','Thất bại', resp.msg);
        }
        },
        error: function(err) {
            console.log(err);
            Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
        }
    });
}

/**
 * @author Phong-Kaster
 * @since 04-11-2022
 * @param {} id 
 * 
 * print appointment info to screen
 */
function setupAppointmentInfo(id)
{
    $.ajax({
        type: "GET",
        url: `${API_URL}/appointments/${id}`,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            //console.log(resp);
            let speciality = resp.data.speciality_id;
            let doctor = resp.data.doctor_id;
            let patientName = resp.data.patient_name;
            let patientPhone = resp.data.patient_phone;
            let patientBirthday = resp.data.patient_birthday;
            let patientReason = resp.data.patient_reason;
            let appointmentTime = resp.data.appointment_time;
            let status = resp.data.status;
            let date = resp.data.date;
            let patientId = resp.data.patient_id;
            
            $("#speciality").val(speciality);
            $(`#doctor option[value="${doctor}"]`).attr("selected", "selected");
            $('#doctor').click();
            $("#patient-name").val(patientName);
            $("#patient-phone").val(patientPhone);
            $("#datepicker").val(date);
            $("#appointment-time").val(appointmentTime);
            $("#status").val(status);
            $("#patient-reason").val(patientReason);
            $("#patient-birthday").val(patientBirthday);
            $("#patient-id").val(patientId);
        }
        else
        {
            showMessageWithButton('error','Thất bại', resp.msg);
        }
        },
        error: function(err) {
            Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
        }
    });
}


function setupAppointmentInfoWithParameter(resp)
{
    bookingId = resp.bookingId;
    serviceId = resp.serviceId;
    doctorId = resp.doctorId;

    
    let patientName = resp.patientName;
    let patientPhone = resp.patientPhone;
    let patientBirthday = resp.patientBirthday;
    let patientReason = resp.patientReason;
    let appointmentTime = resp.appointmentDate + " " + resp.appointmentTime;
    let date = getCurrentDate();
    if( resp.appointmentDate )
    {
        date = resp.appointmentDate;
    }

    let patientId = resp.patientId;

    $("#patient-name").val(patientName);
    $("#patient-phone").val(patientPhone);
    $("#appointment-time").val(appointmentTime);
    $("#patient-reason").val(patientReason);
    $("#patient-birthday").val(patientBirthday);
    $("#datepicker").val(date);
    $("#patient-id").val(patientId);
    $("#service").val(serviceId);
    $("#doctor").val(doctorId);

    /**Run 2 functions to select what PATIENT have chosen from mobile */
    setupDropdownService2();
    setupDropdownDoctor2();
}

/**
 * @author Phong-Kaster
 * @since 04-11-2022
 * @param {@} patient_id 
 */
function setupPatientInformation(patient_id)
{
    $.ajax({
        type: "GET",
        url: `${API_URL}/patients/${patient_id}`,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            let patientName = resp.data.name;
            let patientPhone = resp.data.phone;
            let patientBirthday = resp.data.birthday;

            $("#patient-name").val(patientName);
            $("#patient-phone").val(patientPhone);
            $("#patient-birthday").val(patientBirthday);
        }
        else
        {
            showMessageWithButton('error','Thất bại', resp.msg);
        }
        },
        error: function(err) {
            Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
        }
    });
}


/**
 * @author Phong-Kaster
 * @since 18-12-2022
 */
function setupDropdownService2()
{
    $.ajax({
        type: "GET",
        url: API_URL + "/services",
        data: {length: 100},
        dataType: "JSON",
        success: function(resp) {
            if(resp.result == 1)// result = 1
            {
                createDropdownService2(resp);
            }
            else// result = 0
            {
                console.log(resp.msg);
            }
        },
        error: function(err) {
            Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
        }
    })//end AJAX
}



/**
 * @author Phong-Kaster
 * @since 18-12-2022
 */
function createDropdownService2(resp)
{
    $("#service").empty();
    $("#service").append(`<option selected="" disabled="" value="">Chọn...</option>`);
     for(let i = 0; i < resp.data.length; i++)
     {
         let id = resp.data[i].id;
         let name = resp.data[i].name;
         let element = `<option value="${id}">${name}</option>`;
         $("#service").append(element);
     }
     $("#service").val(serviceId);
}


/**
 * @author Phong-Kaster
 * @since 18-12-2022
 * setup dropdown DOCTOR
 */
function setupDropdownDoctor2()
{
    /**Step 1 - make AJAX call */
    $.ajax({
        type: "GET",
        url: API_URL + "/doctors",
        data: {length: 100},
        dataType: "JSON",
        success: function(resp) {
            if(resp.result == 1)// result = 1
            {
                createDropdownDoctor2(resp);
            }
            else// result = 0
            {
                console.log(resp.msg);
            }
        },
        error: function(err) {
            Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
        }
    })//end AJAX
}



/**
 * @author Phong-Kaster
 * @since 18-12-2022
 * @param {JSON} resp 
 */
 function createDropdownDoctor2(resp)
 {
    $("#doctor").empty();
    $("#doctor").append(`<option selected="" disabled="" value="">Chọn...</option>`);
    $("#doctor").append(`<option  value="0">Để hệ thống lựa chọn</option>`);
     for(let i = 0; i < resp.data.length; i++)
     {
         let id = resp.data[i].id;
         let name = resp.data[i].name;
         let element = `<option value="${id}">${name}</option>`;
         $("#doctor").append(element);
     }
    $("#doctor").val(doctorId);
 }



 /**
  * @author Phong-Kaster
  * @since 19-12-2022
  * gửi yêu cầu với id của bác sĩ để kiểm tra xem bác sĩ này có bận không?
  */
 function isDoctorBusy(method, url, info)
 {
    /**Step 1 - gui ajax kiem tra xem bac si co nhieu benh nhan khong */
    let doctorId = info["doctor_id"];


    $.ajax({
        type: "GET",
        url: `${API_URL}/is-doctor-busy/${doctorId}`,
        dataType: "JSON",
        success: function(resp) 
        {
            let msg = resp.msg;
            let result = resp.result;
            // console.log(result);
            // console.log(msg);
            /**Case 1: bác sĩ sẵn sàng tiếp nhận bệnh nhân thì tạo lịch khám luôn */
            if(resp.result == 1)
            {
                sendAppointmentRequest(method, url, info);
            }
            /**Case 2: bác sĩ đang có nhiều bệnh nhân thì cân nhắc về việc chọn bác sĩ khác */
            else
            {
                Swal
                .fire({
                    title: msg,
                    text: 'Nếu bệnh nhân mong muốn khám với bác sĩ, nhấn Tiếp tục. Nếu bệnh nhân muốn tiết kiệm thời gian hãy chọn Nhu cầu khám bệnh phù hợp cho bệnh nhân',
                    icon: 'question',
                    confirmButtonText: 'Tiếp tục',
                    confirmButtonColor: '#FF0000',
                    cancelButtonColor: '#0000FF',
                    cancelButtonText: 'Để hệ thống lựa chọn',
                    reverseButtons: false,
                    showCancelButton: true
                })
                .then((result) => 
                    {
                        if (result.isConfirmed) 
                        {
                            sendAppointmentRequest(method, url, info);
                        } 
                        else
                        {
                            info["doctor_id"] = 0;
                            sendAppointmentRequest(method, url, info);
                        }
                    });// end Swal
            }
        },
        error: function(err) {
            console.log(err.responseText);
            showMessageWithButton('error','Thất bại', err);
        }
    });
 }