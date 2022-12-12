/**
 * @author Phong-Kaster
 * @since 06-11-2022
 * setup booking info
 */

function setupServiceInfo(id)
{
    $.ajax({
        type: "GET",
        url: `${API_URL}/services/${id}`,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            let serviceImage = resp.data.image ? resp.data.image : "default_service.jpg";
            let serviceName = resp.data.name;
            let serviceDescription = resp.data.description;

            $("#avatar").attr("src", `${API_URL}/assets/uploads/${serviceImage}`);
            $("#name").val(serviceName);
            $("#description").val(serviceDescription);
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

function getNecessaryInfo()
{
    let name = $("#name").val();
    let description = CKEDITOR.instances.description.getData();
        description = encodeURIComponent(description);
    
    let params = {
        name: name,
        description: description
    }

    return params;
}
/**
 * @since 12-12-2022
 * @param {@} id is the id of service 
 */
function setupButton(id)
{
        /**BUTTON SAVE */
        $("#button-confirm").click(function(){

            let params = getNecessaryInfo();
            let url = id > 0 ? `${API_URL}/services/${id}` : `${API_URL}/services`;
            let method = id > 0 ? "PUT" : "POST";
            let msg = method == "PUT" ? "Cập nhật thành công !" : "Tạo mới thành công !";
            
    
            $.ajax({
                type: method,
                url: url,
                data: params,
                dataType: "JSON",
                success: function(resp) {
                if(resp.result == 1)
                {
                    showMessageWithButton('success','Thành công', msg);
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
                        window.location.href = `${APP_URL}/rooms`;
                    } 
                    else
                    {
                        Swal.close();
                    }
                });// end Swal
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
            url: `${API_URL}/services/${id}`,
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(resp) {
            if(resp.result == 1)
            {
                showMessageWithButton('success','Thành công','Cập nhật ảnh đại diện thành công !');
                $("img.avatar-img").attr("src", resp.url);
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


})