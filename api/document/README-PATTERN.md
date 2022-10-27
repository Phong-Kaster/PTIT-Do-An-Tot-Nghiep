### [**0. Pattern**](#0-pattern)

- **Purpose**: 

- **Method**: 

- **URL**: 

- **Headers**:
   
    | Tên                  | Giá Trị                                                                   |
    |----------------------|---------------------------------------------------------------------------|
    |Authentication        |{{ACCESS_TOKEN}}                                                           |
    |Content-Type          |application/x-www-form-urlencoded                                          |

    | Tên                  | Giá Trị                                                                   |
    |----------------------|---------------------------------------------------------------------------|
    |Authentication        |{{ACCESS_TOKEN_PATIENT}}                                                   |
    |Content-Type          |application/x-www-form-urlencoded                                          |
    |Type                  |Patient                                                                    |

- **Body**:
  
    | Tên                   | Tùy chọn | Ý nghĩa                                                                           |
    |-----------------------|----------|-----------------------------------------------------------------------------------|
    |         |  |                                           |

- **Respone**:

####

- 🟢 **GET**

- 🟡 **POST**

- 🔵 **PUT**

- 🟠 **PATCH**
- 
- 🔴 **DELETE**

####

    | Tên                   |  Ý nghĩa                                                                          |
    |-----------------------|-----------------------------------------------------------------------------------|
    |search                |Từ khóa tìm kiếm                                                                    |           
    |order[dir]            |Chiều sắp xếp kết quả. Nhận 2 giá trị asc(tăng dần) & desc(giảm dần)                |
    |order[column]         |Cột được sử dụng để sắp xếp kết quả. Mặc định là ID                                 |
    |length                |Số lượng kết quả trả về. Mặc định là không giới hạn                                 |
    |start                 |Kết quả tìm kiếm bắt đầu từ vị trí thứ mấy. Ví dụ nhập 1 thì kết quả đầu tiên bị bỏ qua| 