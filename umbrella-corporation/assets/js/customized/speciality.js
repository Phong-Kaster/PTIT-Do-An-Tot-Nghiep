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


            $("#id").val(id);
            $("#name").val(name);
            $("#description").val(description);
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
function setupButton()
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