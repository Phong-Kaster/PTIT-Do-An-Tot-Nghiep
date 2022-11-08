/**
 * @author Phong-Kaster
 * @since 08-11-2022
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
     $("#birthday").val(date);
     $("#birthday").datepicker({ dateFormat: 'yy-mm-dd'});
 }



/**
 * @author Phong-Kaster
 * @since 08-11-2022
 * @param {} id 
 */
 function setupPatientInfo(id)
 {
    $.ajax({
        type: "GET",
        url: `${API_URL}/patients/${id}`,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            printPatientInfo(resp);
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
 * @since 08-11-2022
 */
function printPatientInfo(resp)
{
    let id = resp.data.id;
    let name = resp.data.name;
    let email = resp.data.email;
    let phone = resp.data.phone;
    let gender = resp.data.gender;
    let birthday = resp.data.birthday;
    let address = resp.data.address;
    let avatar = resp.data.avatar ? resp.data.avatar : "default_avatar.jpg";
    avatar = `${API_URL}/assets/uploads/${avatar}`;
    let createAt = resp.data.create_at;
    let updateAt = resp.data.update_at;

    $("#id").val(id);
    $("#name").val(name);
    $("#email").val(email);
    $("#phone").val(phone);
    $("#gender").val(gender);
    $("#birthday").val(birthday);
    $("#address").val(address);
    $("#create-at").val(createAt);
    $("#update-at").val(updateAt);
    $("#avatar").attr("src", avatar);
}

/**
 * @author Phong-Kaster
 * @since 08-11-2022
 */
function getNecessaryInfo()
{
    let id = $("#id").val();
    let name = $("#name").val();
    let email = $("#email").val();
    let phone = $("#phone").val();
    let gender = $("#gender").val();
    let birthday= $("#birthday").val();
    let address =$("#address").val();
    
    let params = {
        id: parseInt(id),
        name: name,
        email: email,
        phone: phone,
        gender: parseInt(gender),
        birthday: birthday,
        address: address
    }

    return params;
}


 /**
  * @author Phong-Kaster
  * @since 08-11-2022
  * set up button
  */
 function setupButton()
 {
    /**BUTTON SAVE */
    $("#button-save").click(function(){
        let params = getNecessaryInfo();
        let id = params.id;
        
        console.log(params);

        $.ajax({
            type: "PUT",
            url: `${API_URL}/patients/${id}`,
            data: params,
            dataType: "JSON",
            success: function(resp) {
            console.log(resp);
            if(resp.result == 1)
            {
                showMessageWithButton('success','Thành công','Yêu cầu đã được hoàn tất !');
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
    })
 }