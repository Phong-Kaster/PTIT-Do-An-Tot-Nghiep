/**
 * @author Phong-Kaster
 * @since 07-11-2022
 * print speciality info by AJAX with its ID
 */
function setupSpecialityInfo(id)
{
    $.ajax({
        type: "GET",
        url: `${API_URL}/specialities/${id}`,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            let id = resp.data.id;
            let name = resp.data.name;
            let description = resp.data.description;
            let image = resp.data.image ? resp.data.image : 'default_avatar.jpg';


            $("#id").val(id);
            $("#name").val(name);
            $("#description").val(description);
            $("#avatar").attr("src", `${API_URL}/assets/uploads/${image}`);
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
 * @since 07-11-2022
 * reset input 
 */
function reset()
{
    $("#id").val("");
    $("#name").val("");
    $("#description").val("");
    $("#cke_1_contents").val("");
}

/**
 * @author Phong-Kaster
 * @since 07-11-2022
 * setup button
 */
function setupButton(id)
{
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
                    window.location.href = `${APP_URL}/specialities`;
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
    });



    /**BUTTON CONFIRM */
    $("#button-confirm").click(function(){
        /**Step 1 */
        let id = $("#id").val();
        let name = $("#name").val();

        let description = CKEDITOR.instances.description.getData();
        description = encodeURIComponent(description);
        let data = { name: name, description: description };

        let method = "POST";
        let url = `${API_URL}/specialities`;
        if( id > 0)
        {
            method = "PUT";
            url = `${API_URL}/specialities/${id}`;
        }
        //console.log(description);
        sendAJAXrequest(method, url, data);
    });



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
            url: `${API_URL}/specialities/${id}`,
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(resp) {
            if(resp.result == 1)
            {
                showMessageWithButton('success','Thành công','Cập nhật ảnh đại diện thành công !');
                //$("img.avatar-img").attr("src", resp.url);
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



/**
 * @author Phong-Kaster
 * @since 07-11-2022
 * send AJAX request to create | update
 */
function sendAJAXrequest(method, url, data)
{
    let message = "Tạo mới chuyên khoa thành công !";
    if( method == "PUT")
    {
        message = "Cập nhật chuyên khoa thành công !";
    }


    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            showMessageWithButton('success','Thành công',message);
            //window.location = `${APP_URL}/specialities`;
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