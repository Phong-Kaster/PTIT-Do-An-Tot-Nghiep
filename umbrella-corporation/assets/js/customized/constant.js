const API_URL = "http://localhost:8080/PTIT-Do-An-Tot-Nghiep/API";
const APP_URL = "http://localhost:8080/PTIT-Do-An-Tot-Nghiep/umbrella-corporation";
const DEFAULT_LENGTH = 5;
/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * this function get current day with format yyyy-dd-mm. 
 * For instance, 2022-01-01
 */
function getCurrentDate()
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

    let date =  year + "-" + month + "-" + day;
    return date;
}

/**
 * @author Phong-Kaster
 * @param {*} icon success | error | warning | info | question
 * @param {*} title 
 * @param {*} message
 * @since 04-11-2022 
 * show message with confirm button. When the button is clicked, this dialog disappears.
 */
function showMessageWithButton(icon = "info", title, message)
{
    Swal
    .fire({
        title: title,
        text: message,
        icon: icon,
        confirmButtonText: 'Xác nhận',
        confirmButtonColor: '#FF0000',
        reverseButtons: false
    });
}

/**
 * @author Phong-Kaster
 * @since 31-10-2022
 * setup dropdown DOCTOR's speciality
 */
 function setupDropdownSpeciality(params)
 {
     /**Step 1 - make AJAX call */
     $.ajax({
         type: "GET",
         url: API_URL + "/specialities",
         data: params,
         dataType: "JSON",
         success: function(resp) {
             if(resp.result == 1)// result = 1
             {
                 createDropdownSpeciality(resp);
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
 * @since 01-11-2022
 * @param {JSON} resp 
 */
function createDropdownSpeciality(resp)
{
    for(let i = 0; i < resp.data.length; i++)
    {
        let id = resp.data[i].id;
        let name = resp.data[i].name;
        let element = `<option value="${id}">${name}</option>`;
        $("#speciality").append(element);
    }
}

/**
 * @author Phong-Kaster
 * @since 31-10-2022
 * setup dropdown DOCTOR
 */
 function setupDropdownDoctor(params)
{
    /**Step 1 - make AJAX call */
    $.ajax({
        type: "GET",
        url: API_URL + "/doctors",
        data: params,
        dataType: "JSON",
        success: function(resp) {
            if(resp.result == 1)// result = 1
            {
                createDropdownDoctor(resp);
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
 * @since 01-11-2022
 * @param {JSON} resp 
 */
 function createDropdownDoctor(resp)
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
    //  console.log("setupDropdownDoctor done !");
 }


/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * whenever ADMIN or SUPPORTER choose a speciality when this function will query to get
 * all DOCTORS match with the chosen speciality.
 */
function setupChooseSpeciality()
{
    $("#speciality").click(function(){
        let specialityId = $(this).val();
        if(specialityId != null)
        {
            let params = {
                speciality_id: specialityId
            }
            setupDropdownDoctor(params);
        }
    })
}



function setupDropdownRoom(params)
{
    /**Step 1 - make AJAX call */
    $.ajax({
        type: "GET",
        url: API_URL + "/rooms",
        data: params,
        dataType: "JSON",
        success: function(resp) {
            if(resp.result == 1)// result = 1
            {
                createDropdownRoom(resp);
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


function createDropdownRoom(resp)
 {
    $("#room").empty();
    $("#room").append(`<option selected="" disabled="" value="">Chọn...</option>`);
     for(let i = 0; i < resp.data.length; i++)
     {
         let id = resp.data[i].id;
         let name = resp.data[i].name;
         let location = resp.data[i].location;
         let element = `<option value="${id}">${location}, ${name}</option>`;
         $("#room").append(element);
     }
 }


/**
 * @author Phong-Kaster
 * @since 01-11-2022
 * @param {JSON} params 
 */
 function setupTitle(params)
 {
     let date = params.date;
     let doctor_id = params.doctor_id;
     let search = params.search;
     let status = params.status;
     let speciality_id = params.speciality_id;
 
     let title = "Danh sách khám bệnh";
     if(date)
     {
         title += " -- Ngày: " + date;
     }
     if(doctor_id)
     {
         title += " -- Bác sĩ: " + $("#doctor :selected").text()
     }
     if(search)
     {
         title += " -- Từ khóa: " + search;
     }
     if(status)
     {
         let statusValue = "Đang xử lý";
         switch(status)
         {
            case "processing":
                statusValue = "Đang xử lý";
                break;
            case "done":
                statusValue = "Xong";
                break;
            case "cancelled":
                statusValue = "Hủy bỏ";
                break;
         }
         title += " -- Trạng thái: " + statusValue;
     }
     if(speciality_id)
     {
         title += " -- Chuyên khoa: " + $("#speciality :selected").text()
     }

     $(".row .card-header").first().text(title);
 }


/**
 * @author Phong-Kaster
 * @since 05-11-2022
 */
function setupDropdownService(params)
{
    $.ajax({
        type: "GET",
        url: API_URL + "/services",
        data: params,
        dataType: "JSON",
        success: function(resp) {
            if(resp.result == 1)// result = 1
            {
                createDropdownService(resp);
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
 * @since 05-11-2022
 */
function createDropdownService(resp)
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
    //  console.log("setup Dropdown Service done !");
}


/**
 * @author Phong-Kaster
 * @since 05-11-2022
 * this function returns PROCESSING bookings today
 */
function getBookingQuantity()
{
    let date = getCurrentDate();
    let params = {
        appointment_date: date,
        status: "processing"
    }



    $.ajax({
        type: "GET",
        url: `${API_URL}/bookings`,
        data: params,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            let quantity = resp.quantity + " mới";
            $("#booking-quantity").text(quantity);
        }
        else
        {
            console.log(resp.msg);
            // showMessageWithButton('error','Thất bại', resp.msg);
        }
        },
        error: function(err) {
            Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
        }
    });
}





/**
 * @author Phong-Kaster
 * @since 08-11-2022
 * upload avatar and preview it
 */
function uploadAvatar()
{
    /**
     * HTML
     *<div class="row mb-4"><!--1. AVATAR & BUTTON UPLOAD | https://mdbcdn.b-cdn.net/img/new/avatars/5.webp -->
                <div class="row mb-3 mx-1">
                    <img id="avatar" src="http://localhost:8080/PTIT-Do-An-Tot-Nghiep/api/assets/uploads/avatar_1_1667399080.png?v=040300" 
                        class="rounded-circle mb-3" style="width: 200px;" alt="Avatar" />
                </div>
                <div class="row col-md-4 mb-3 mx-1">
                    <input class="mb-4" type="file" id="file" name="filename"
                    accept="image/png, image/jpeg, image/jpg"/>
                    <button class="file btn btn-secondary" id="button-avatar" type="button" >Cập nhật ảnh đại diện</button>
                </div>
        </div><!-- end 1. AVATAR & BUTTON UPLOAD -->
     */

    /**PREVIEW BUTTON */
    document.getElementById('file').onchange = function (evt) {
        let tgt = evt.target;
        let files = tgt.files;
        
        // FileReader support
        if (FileReader && files && files.length) 
        {
            var fr = new FileReader();
            fr.onload = function () {
                document.getElementById('avatar').src = fr.result;
            }
            fr.readAsDataURL(files[0]);
        }
        
        // Not supported
        else {
            // fallback -- perhaps submit the input to an iframe and temporarily store
            // them on the server until the user's session ends.
        }
    }

    /**BUTTON AVATAR */
    $("#button-avatar").click(function(){
        let file = document.getElementById("file").files[0];
        let formData = new FormData();
     
        formData.append("file", file);
        formData.append("action", "avatar");
        
        $.ajax({
            type: "POST",
            url: `${API_URL}/doctor/profile`,
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(resp) {
            console.log(resp);
            if(resp.result == 1)
            {
                showMessageWithButton('success','Thành công','Cập nhật ảnh đại diện thành công !');
            }
            else
            {
                showMessageWithButton('error','Thất bại', resp.msg);
            }
            },
            error: function(err) {
                console.log(err.responseText);
                showMessageWithButton('error','Thất bại', err);
            }
        });
    });
}


$(document).ready(function(){
    getBookingQuantity();
});