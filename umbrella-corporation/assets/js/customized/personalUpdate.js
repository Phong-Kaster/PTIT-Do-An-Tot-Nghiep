/**
 * @author Phong-Kaster
 * @since 10-11-2022
 */
function setupAccountInfo()
{
    $.ajax({
        type: "GET",
        url: `${API_URL}/doctor/profile`,
        dataType: "JSON",
        success: function(resp) {
            
            if(resp.result == 1)
            {
                let email = resp.data.email;
                let name = resp.data.name;
                let phone = resp.data.phone;
                let description = resp.data.description;
                let avatar = resp.data.avatar ? resp.data.avatar : "default_avatar.jpg";

                $("#email").val(email);
                $("#phone").val(phone);
                $("#name").val(name);
                $("#description").val(description);
                $("#avatar").attr("src", `${API_URL}/assets/uploads/${avatar}`);
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
}


function getNecessaryInfo()
{
    let name = $("#name").val();
    let phone = $("#phone").val();
    let description = CKEDITOR.instances.description.getData();
        description = encodeURIComponent(description);
    
    let params = {
        action: "personal",
        name: name,
        phone: phone,
        description: description
    }

    return params;
}


function setupButton()
{
    /**BUTTON SAVE */
    $("#button-save").click(function(){
        let params = getNecessaryInfo();
        $.ajax({
            type: "POST",
            url: `${API_URL}/doctor/profile`,
            data: params,
            dataType: "JSON",
            success: function(resp) {
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
            url: `${API_URL}/doctor/profile`,
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
    setupAccountInfo();
})