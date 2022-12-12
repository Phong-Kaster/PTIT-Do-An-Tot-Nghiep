 /**
 * @author Phong-Kaster
 * @since 12-12-2022
 * @param {} url is the API_URL to make AJAX
 * this function always get rooms created today
 */
 function setupServiceTable(url, params)
 {  
     /**Step 2 - call AJAX */
     $.ajax({
       type: "GET",
       url: url,
       data: params,
       dataType: "JSON",
       success: function(resp) {
           if(resp.result == 1)// result = 1
           {
                 createServiceTable(resp);
                 pagination(url, resp.quantity, resp.data.length);
           }
           else// result = 0
           {
                 showMessageWithButton('error','Thất bại', resp.msg);
           }
       },
       error: function(err) {
           Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
       }
     })//end AJAX
 }

 /**
 * @author Phong-Kaster
 * @since 31-10-2022
 * setup date picker
 */
 function createServiceTable(resp)
 {
    $("tbody").empty();// empty the table
     /** loop resp to append into table */
     for(let i=0; i< resp.data.length; i++)
     {
         let image = resp.data[i].image != "" > 0 ? resp.data[i].image : "default_service.jpg";
         let id = resp.data[i].id;
         let name = resp.data[i].name;
         let body = `
         <tr data-id=${id} class="align-middle"><!-- TR -->
            <td class="text-center">
                <div>
                    <img height="100" src="${API_URL}/assets/uploads/${image}" alt="image">
                </div>
            </td>

            <td>
                <div class="fw-semibold" id="name">${id}</div>
            </td>

            <td class="fw-semibold">
                <div class="fw-semibold" id="name">${name}</div>
            </td>

            <td>
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <a href="${APP_URL}/service/${id}" class="btn btn-outline-success" type="button">Sửa thông tin</a>
                    <a href="${APP_URL}/service-and-doctor/${id}" class="btn btn-outline-primary" type="button">Danh sách bác sĩ</a>
                    <button id="button-delete" data-id="${id}" class="btn btn-outline-danger" type="button">Xoá</button>
                </div>
            </td>
        </tr>`;

         
         $("tbody").append(body);
     }
 }

   /**
 * @author Phong-Kaster
 * @param {String} url 
 * @param {int} totalRecord 
 * @param {int} currentRecord is the number of AJAX returns for us.
 * For instance, total record is 15 records but DEFAULT_LENGTH is 5 
 * so that AJAX returns first 5 records.
 * The "currentRecord" is used to calculate next step for AJAX.
 */
function pagination(url, totalRecord, currentRecord)
{
    let buttonPrevious = $("ul#pagination li#button-previous");
    let buttonNext = $("ul#pagination li#button-next");
    let page = $("ul#pagination li#current-page");
    buttonNext.removeClass("disabled");
    buttonPrevious.removeClass("disabled");

    let currentPage = 1;
    let quantityOnePage = DEFAULT_LENGTH;
    let totalPage = Math.ceil(totalRecord / quantityOnePage);
    let start = 0;

    // console.log("=====================================");
    // console.log("totalRecord: " + totalRecord);
    // console.log("currentRecord: " + currentRecord);
    // console.log("totalPage: " + totalPage);
    if( totalPage == 1 )
    {
        buttonNext.addClass("disabled");
        buttonPrevious.addClass("disabled");
        page.text(1);
    }
    // if( currentPage == totalPage && totalPage > 1 )
    // {
    //     buttonNext.addClass("disabled");
    //     buttonPrevious.removeClass("disabled");
    // }

    /***********BUTTON PREVIOUS***********/
    buttonPrevious.click(function(){
        if( currentPage == 1)
        {
            buttonPrevious.addClass("disabled");
        }
        else
        {
            currentPage--;
            page.text(currentPage);
            if( currentPage < totalPage && currentPage > 1)/**Case 1 - total page == 3 & current page == 2 => enable */
            {
                buttonNext.removeClass("disabled");
            }
            
            if( currentPage == 1 && totalPage != 1 )/**Case 2 - total page == currentPage == 1 => disable*/
            {
                buttonPrevious.addClass("disabled");
                buttonNext.removeClass("disabled");
            }
            
            if( currentPage > 1)/**Case 3 - current page > 1 */
            {
                buttonPrevious.removeClass("disabled");
            }
            
            /**query */
            start = quantityOnePage*(currentPage-1);
            let params = getFilteringCondition();
            params["start"] = start;
            params["length"] = quantityOnePage;

            /**Step 1 - get filter values */
            $.ajax({
                type: "GET",
                url: url,
                data: params,
                dataType: "JSON",
                success: function(resp) {
                    if(resp.result == 1)// result = 1
                    {
                        createServiceTable(resp);
                    }
                    else// result = 0
                    {
                        console.log(resp.msg);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }
    });



    /*************BUTTON NEXT************/
    buttonNext.click(function(){
        if( totalPage == currentPage )
        {
            buttonNext.addClass("disabled");
        }
        else
        {
            currentPage++;
            page.text(currentPage);
            /**Case 1 - total page == 2 && next current page == 2 => disable NEXT , enable PREVIOUS */
            if( totalPage > 1 && currentPage == totalPage)
            {
                buttonNext.addClass("disabled");
                buttonPrevious.removeClass("disabled");
            }
            /**Case 2 - total page == 3 && next current page == 2 => enable both buttons */
            if( totalPage > 1 && currentPage < totalPage)
            {
                buttonNext.removeClass("disabled");
                buttonPrevious.removeClass("disabled");
            }
            /**Step 3 - set up start where query begin returns the result for us */
            if(currentRecord == quantityOnePage && currentPage == 1)
            {
                start = quantityOnePage;
            }
            if(currentRecord == quantityOnePage && currentPage != 1)
            {
                start = quantityOnePage*(currentPage-1);
            }
            
            let params = getFilteringCondition();
            params["start"] = start;
            // console.log("===next button");
            // console.log("currentPage: " + currentPage);
            // console.log("start: " + start);
            // console.log(params);
            /**Step 2 - call AJAX */
            $.ajax({
                type: "GET",
                url: url,
                data: params,
                dataType: "JSON",
                success: function(resp) {
                    if(resp.result == 1)// result = 1
                    {
                       
                        createServiceTable(resp);
                    }
                    else// result = 0
                    {
                        console.log(resp.msg);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            })//end AJAX
        }
    })
}


 /**
 * @author Phong-Kaster
 * @since 12-12-2022
 * @returns Object params contains filtering conditions
 */
 function getFilteringCondition()
 {
     let search         = $("#search").val() ? $("#service :selected").val() : "";
     let orderDir       = $("#order-dir :selected").val() ? $("#order-dir :selected").val() : "asc";
     let orderColumn    = $("#order-column :selected").val() ? $("#order-column :selected").val() : "id";
 
 
     /**Step 2 - set up parameters */
     let order = {
         "dir": orderDir,
         "column": orderColumn
     };
     let params = {
         search: search,
         order: order,
         length: DEFAULT_LENGTH,
     };
 
     return params;
 }

 /**
  * @author Phong-Kaster
  * @since 12-12-2022
  * setup button
  */
function setupButton()
{
    /**BUTTON RESET */
    $("#button-reset").click(function(){
        $("#search").val("");
        $("#order-dir").val("");
        $("#order-column").val("");
        

        let order = { column:"id", dir:"asc"}
        let params = {
            order: order,
            length: DEFAULT_LENGTH
        }
        setupServiceTable(url, params);
    });



    /** BUTTON SEARCH*/
    $("#button-search").click(function(){
        /**Step 1 - get filter values */
        let params = getFilteringCondition();
       
        
        /**Step 2 - query */
        let url = API_URL + "/services";
        setupServiceTable(url, params);
    });



    /**BUTTON DELETE */
    $(document).on('click','#button-delete',function(){
        Swal
        .fire({
            title: 'Bạn chắc chắn muốn thực hiện hành động ngày',
            text: "Hành động này không thể khôi phục sau khi thực hiện",
            icon: 'warning',
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
                    let id = $(this).attr("data-id");
                    let url = API_URL+"/services/"+id;
                    let method = "delete";
                    $.ajax({
                        type: method,
                        url: url,
                        dataType: "JSON",
                        success: function(resp) {
                        if(resp.result == 1)
                        {
                            showMessageWithButton('success','Thành công','Yêu cầu đã được hoàn tất !');
                            $(`tbody tr[data-id="${id}"]`).remove();
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
                else
                {
                    Swal.close();
                }
            });/**end Swal */
    });/** end BUTTON DELETE */
}// end FUNCTION