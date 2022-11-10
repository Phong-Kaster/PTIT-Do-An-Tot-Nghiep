/**
 * @author Phong-Kaster
 * @since 09-11-2022
 * setup booking info
 */
 function setupDoctorInfo(id)
 {
     $.ajax({
         type: "GET",
         url: `${API_URL}/doctors/${id}`,
         dataType: "JSON",
         success: function(resp) {
         if(resp.result == 1)
         {
             let id = resp.data.id;
             let email = resp.data.email;
             let name = resp.data.name;
             let specialityId = resp.data.speciality.id;
             let roomId = resp.data.room.id;
             let phone = resp.data.phone;
             let price = resp.data.price;
             let description = resp.data.description;
             let active =  resp.data.active;
             let role =  resp.data.role;
             let createAt =  resp.data.create_at;
             let updateAt =  resp.data.update_at;
             let avatar = resp.data.avatar ? resp.data.avatar : "default_avatar.jpg";
             

             $("#id").val(id);
             $("#email").val(email);
             $("#name").val(name);
             $("#speciality").val(specialityId);
             $("#room").val(roomId);
             $("#phone").val(phone);
             $("#price").val(price);
             $("#description").val(description);
             $("#create-at").val(createAt);
             $("#update-at").val(updateAt);
             $("#active").val(active);
             $("#role").val(role);
             $("#avatar").attr("src", `${API_URL}/assets/uploads/${avatar}`);
         }
         else
         {
             window.location = `${APP_URL}/error`;
         }
         },
         error: function(err) {
             Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
         }
     });
 }


/**
 * @author Phong-Kaster
 * @since 09-11-2022
 * @returns 
 */
function getNecessaryInfo()
{
    let id = $("#id").val();
    let email = $("#email").val();
    let name = $("#name").val();
    let specialityId = $("#speciality :selected").val();
    let roomId = $("#room :selected").val();
    let phone = $("#phone").val();
    let price = $("#price").val() ? $("#price").val() : "";
    let description = CKEDITOR.instances.description.getData();
        description = encodeURIComponent(description);
    let active = $("#active :selected").val() ? $("#active :selected").val() : "0";
    let role = $("#role :selected").val();


    let params = {
        id: parseInt(id),
        email: email,
        speciality_id: parseInt(specialityId),
        room_id: parseInt(roomId),
        name: name,
        phone: phone,
        price: price,
        description: description,
        active: active,
        role: role
    }

    return params;
}


/**
 * @author Phong-Kaster
 * @since 09-11-2022
 */
function setupButton(id)
{
    let url = `${API_URL}/doctors`;
    let method = "POST";
    if( id > 0 )
    {
        method = "PUT";
        url = `${API_URL}/doctors/${id}`;
        $("#email").prop('disabled', true);
    }
    else//if we are creating new doctor, hide button upload avatar
    {
        $("#button-avatar").prop('disabled', true);
        $("#file").prop('disabled', true);
    }


    /**BUTTON AVATAR - PREVIEW */
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
            url: `${API_URL}/doctors/${id}`,
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(resp) {
            if(resp.result == 1)
            {
                showMessageWithButton('success','Thành công',resp.msg);
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



    /**BUTTON SAVE */
    $("#button-save").click(function(){
        let params = getNecessaryInfo();


        $.ajax({
            type: method,
            url: url,
            data: params,
            dataType: "JSON",
            success: function(resp) {
            if(resp.result == 1)
            {
                showMessageWithButton('success','Thành công',resp.msg);
            }
            else
            {
                showMessageWithButton('error','Thất bại', resp.msg);
            }
            },
            error: function(err) {
                console.log(err.responseText);
                showMessageWithButton('error','Thất bại', err.responseText);
            }
        });
    });
}