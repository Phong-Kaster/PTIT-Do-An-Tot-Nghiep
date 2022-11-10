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
                $("#description").replaceWith(`<strong>${description}</strong>`);
                $("#create-at").replaceWith(`<p><strong>${createAt}</strong></p>`);
                $("#update-at").replaceWith(`<p><strong>${updateAt}</strong></p>`);
                $("#active").val(active);
                $("#role").val(role);
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


$(document).ready(function(){
    setupAccountInfo();
})