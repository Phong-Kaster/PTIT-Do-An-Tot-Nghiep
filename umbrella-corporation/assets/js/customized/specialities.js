/**
 * @author Phong-Kaster
 * @since 07-11-2022
 */
function setupSpecialityTable(params)
{
    /**Step 1 - declare params */
    let url = `${API_URL}/specialities`;

    /**Step 2 - ajax */
    $.ajax({
        type: "GET",
        url: url,
        data: params,
        dataType: "JSON",
        success: function(resp) {
        if(resp.result == 1)
        {
            createSpecialityTable(resp);
            pagination(url, resp.quantity, resp.data.length);
        }
        else
        {
            showMessageWithButton('error','Thất bại', resp.msg);
        }
        },
        error: function(err) {
            showMessageWithButton('error','Thất bại', err);
        }
    });
}



/**
 * @author Phong-Kaster
 * @since 07-11-2022
 */
function createSpecialityTable(resp)
{
    let size = resp.data.length;
    if( size == 0)
    {
        return;
    }
    $("tbody").empty();
    for(let i = 0; i< size; i++)
    {
        let element = resp.data[i];
        let specialityId = element.id;
        let specialityImage = element.image.length > 0 ? element.image : "default_service.png";
        let specialityName = element.name;
        let specialityDescription = element.description;
        let specialityDoctorQuantity = element.doctor_quantity;
        let body = 
            `<tr data-id=${specialityId} class="align-middle">
                <td class="text-center">
                    <div>
                        <img height="100" src="${API_URL}/assets/uploads/${specialityImage}" alt="image">
                    </div>
                </td>


                <td class="fw-semibold">
                <div class="fw-semibold" id="speciality-id">${specialityId}</div>
                </td>


                <td>
                    <div class="fw-semibold" id="speciality-name">${specialityName}</div>
                </td>

                <td>
                    <div class="fw-semibold" id="speciality-doctor-quantity">${specialityDoctorQuantity}</div>
                </td>

                <td>
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <a href="${APP_URL}/speciality/${specialityId}" class="btn btn-outline-warning" type="button">Sửa</a>
                        <button id="button-delete" data-id=${specialityId} class="btn btn-outline-danger" type="button">Xóa</button><div class="btn-group" role="group">
                    </div>
                </td>
            </tr>`;
        $("tbody").append(body);
    }
}



/**
 * @author Phong-Kaster
 * @since 07-11-2022
 * @returns params to filter data
 */
function getFilteringCondition()
{
    let search         = $("#search").val();
    let orderDir       = $("#order-dir :selected").val() ? $("#order-dir :selected").val() : "desc";
    let orderColumn    = $("#order-column :selected").val() ? $("#order-column :selected").val() : "id";


    /**Step 2 - set up parameters */
    let order = {
        "dir": orderDir,
        "column": orderColumn
    };
    let params = {
        search: search,
        order: order,
        length: DEFAULT_LENGTH
    };

    return params;
}



/**
 * @author Phong-Kaster
 * @since 07-11-2022
 */
function setupButton()
{
    /**BUTTON DELETE on ELEMENT */
    $(document).on('click','#button-delete',function(){
        let id = $(this).attr("data-id");

        Swal
        .fire({
            title: 'Bạn chắc chắn muốn thực hiện hành động ngày',
            text: "Hành động ngày không thể khôi phục sau khi thực hiện",
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
                    /*update booking status from PROCESSING to VERIFIED */
                    $.ajax({
                        type: "DELETE",
                        url: `${API_URL}/specialities/${id}`,
                        dataType: "JSON",
                        success: function(resp) {
                            if( resp.result == 1)
                            {
                                showMessageWithButton('success','Thành công', resp.msg);
                                $(`tbody tr[data-id="${id}"]`).remove();
                            }
                            else
                            {
                                showMessageWithButton('info','Không thành công', resp.msg);
                            }
                            
                        },
                        error: function(err) {
                            showMessageWithButton('error','Thất bại', err);
                        }
                    })
                } 
                else
                {
                    Swal.close();
                }
            });// end Swal
    });

    /**BUTTON SEARCH */
    $("#button-search").click(function(){
        let params = getFilteringCondition();
        setupSpecialityTable(params);
    })

    /**BUTTON RESET */
    $("#button-reset").click(function(){
        $("#search").val("");
        $("#order-dir").val("");
        $("#order-column").val("");

        let order = { column:"id", dir:"asc"}
        let params = {
            order: order
        }
        setupSpecialityTable(params);
    })
}



 /**
 * @author Phong-Kaster
 * @since 07-11-2022
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
                          createSpecialityTable(resp);
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
                         
                          createSpecialityTable(resp);
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