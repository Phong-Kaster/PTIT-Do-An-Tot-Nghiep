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
     for(let i = 0; i < resp.data.length; i++)
     {
         let id = resp.data[i].id;
         let name = resp.data[i].name;
         let element = `<option value="${id}">${name}</option>`;
         $("#doctor").append(element);
     }
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
        console.log(`speciality: ${specialityId}`);
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
         let element = `<option value="${id}">${name}</option>`;
         $("#room").append(element);
     }
 }
