<h1 align="center">Đồ án tốt nghiệp - Học viện Công nghệ Bưu chính viễn thông <br/>
    Ứng dụng Android hỗ trợ bệnh nhân đăng ký khám và điều trị bệnh 
</h1>

<p align="center">
    <img src="./photo/umbrella-corporation-black-donnell-rose.jpg" />
</p>


# [**Table Of Content**](#table-of-content)
- [**Table Of Content**](#table-of-content)
- [**Introduction**](#introduction)
- [**Topic**](#topic)
- [**Database**](#database)
- [**API Document**](#api-document)
- [**Document**](#document)
- [**Features**](#features)
  - [**1. Login**](#1-login)
  - [**2. Sign Up**](#2-sign-up)
  - [**3. Recovery Password**](#3-recovery-password)
- [**Milestone**](#milestone)
- [**Post Script**](#post-script)
- [**Bonus**](#bonus)
- [**Timeline**](#timeline)
  - [**Phase 0: 01-09-2022 to 28-09-2022**](#phase-0-01-09-2022-to-28-09-2022)
  - [**Phase 1: 19-09-2022 to xx-xx-2022**](#phase-1-19-09-2022-to-xx-xx-2022)
- [**Controller Timeline**](#controller-timeline)
- [**Special Thanks**](#special-thanks)
- [**Made with 💘 and PHP <img src="https://www.vectorlogo.zone/logos/php/php-horizontal.svg" width="60">**](#made-with--and-php-)

# [**Introduction**](#introduction)

Chào các bạn, mình tên là Nguyễn Thành Phong. 
Mã số N18DCCN147. 
Niên khóa 2018-2023. 

Lời đầu tiên mình xin chào các bạn và cảm ơn tất cả các bạn đang ở đây. Trong tài liệu này mình sẽ chia sẻ tất cả những gì các bạn cần biết khi làm đồ án 
tốt nghiệp và đề tài do mình thực hiện để các bạn có thể tham khảo. Mình hi vọng phần tài liệu mình viết tiếp theo đây 
sẽ hỗ trợ phần nào cho các bạn khi bước tới ngưỡng cửa quan trọng của cuộc đời mình - tốt nghiệp đại học.

# [**Topic**](#topic)

<p align="center">
    <img src="./photo/topic.png" />
</p>


# [**Database**](#database)

<p align="center">
    <img src="./photo/database-version-2.png" />
</p>
<h3 align="center">

***Sơ đồ cơ sở dữ liệu***
</h3>

# [**API Document**](#api-document)

Mình có soạn thảo và liệt kê chi tiết cách sử dụng các chức năng mà mình đã xây dựng thành tài liệu.
Nếu các bạn có nhu cầu muốn tham khảo, hãy ấn vào [**đây**](https://github.com/Phong-Kaster/PTIT-Do-An-Tot-Nghiep/tree/main/document#readme) để đọc chi tiết cách sử dụng API này.

# [**Document**](#document)

Khi tải repository này về, bạn sẽ thấy một thư mục tên `document`. Trong thư mục này, mình 
để lại cho các bạn một số tài liệu quan trọng

- **Danh-sach-de-tai-tot-nghiep** - là tệp tin excel tổng hợp lại toàn bộ các đề tài của từng sinh khóa D18

- **Tập hợp các tệp tin có tiền tố eshop** - là database mình mở rộng ra từ đồ án thực tập( Rất tiếc là không có cơ hội sử dụng 😥)

- **Noi-dung-quyen-bao-cao** - là các yêu cầu chung để làm báo cáo tốt nghiệp. Tuy nhiên thì tệp tin này do thầy **Nguyễn Anh Hào** soạn 
và gửi cho các sinh viên thầy hướng dẫn. Không biết các giáo viên khác có gửi không nữa😅😅

- **README.md** - là một tệp tin markdown khác, mình tạo ra nó để mô tả cách đọc và sử dụng API do mình viết.

# [**Features**](#features)

Phần này mình sẽ liệt kê các tính năng chính và quan trọng mà API này có thể thực hiện.

## [**1. Login**](#login)

Đầu tiên dĩ nhiên luôn là tính năng đăng nhập rồi. Với tính năng cơ bản nhất và quan trọng này thì mình vẫn sử dụng JWT Token như ở 
[**Đồ án thực tập**](https://github.com/Phong-Kaster/PTIT-Do-An-Thuc-Tap). Còn các thông tin còn lại thì có bao nhiêu thì in ra hết( Dĩ nhiên là trừ cái mật khẩu rồi 😅😅).

<p align="center">
    <img src="./photo/image3.png" />
</p>
<h3 align="center">

***Dữ liệu trả về khi đăng nhập thành công***
</h3>

## [**2. Sign Up**](#2-sign-up)

Có đăng nhập thì dĩ nhiên là phải có đăng ký rồi. Tính năng này thì chẳng có gì lạ lẫm với các bạn cả. Ngoài các thông tin người dùng nhập vào và sau khi kiểm tra hợp lệ
thì họ sẽ được nhận một email thông báo gửi tới email mà được sử dụng để tài khoản như sau:

<p align="center">
    <img src="./photo/image1.png" />
</p>
<h3 align="center">

**Chúc mừng bạn đã trở thành thành viên của tập đoàn Umbrella Corporation 🎇**
</h3>

## [**3. Recovery Password**](#3-recovery-password)

Bạn bị bệnh não cá vàng 🐠🐠? Bạn hay làm trước quên sau ? Lần này thì quên cmn luôn mật khẩu mình vừa mới tạo. 
Đừng lo ! API này hỗ trợ luôn trường hợp bạn quên mật khẩu. Chỉ cần nhập đúng email bạn đã đăng kí tài khoản tại **UMBRELLA CORPORATION** 

BÙM 🔥🔥🔥 Ngạc nhiên chưa !! Nhập cái mã bên dưới và tạo lại mật khẩu nào 

<p align="center">
    <img src="./photo/image2.png" />
</p>
<h3 align="center">

**Lần sau đừng quên mật khẩu nữa nha 😅😅**
</h3>

# [**Milestone**](#milestone)

Phần này mình ghi lại những mốc thời gian quan trọng mà mình đã trải qua trong quá trình làm đồ án. 
Những mốc thời gian dưới đây là của mình & có thể khi các bạn đọc thì các mốc thời gian sẽ khác đi 3-4 ngày. 
Tuy nhiên, Napoleon đã từng nói: 

<p align="center">
    <img src="./photo/napoleon.jpg" width="400px" />
</p>
<h3 align="center">

***"90% sự thành bại của một trận đánh phụ thuộc vào thông tin" - Napoleon***
</h3>

Đối với mình thì câu nói trên chưa bao giờ lại đúng đến vậy. Trong 4 năm rưỡi mài đít quần trên giảng đường,
mình thấy cực kì thiệt thòi vì không ở kí túc xá hoặc ở gần trường học. Trước mỗi kì thi, những sinh viên 
ở kí túc xá thường sẽ biết rất nhiều những thông tin có giá trị trước mỗi kì thi😫😫. Rất nhiều lần do mình không
biết thông tin sớm hơn mà dẫn tới thiệt thòi so với bạn bè. 

Ví dụ: sau kì thi môn Lập trình mạng ở năm 4, thầy Phan Thanh Hy cho phép sinh viên được quyền khiếu nại nếu bị 
điểm thấp do là ông thầy này thường lười & để sinh viên năm 2-3 chấm bài thi cuối kì của lớp mình. Điều này 
dẫn tới hậu quả là nhiều bạn bị điểm thấp hơn so với mong muốn. Và để sửa sai, thầy cho phép sinh viên được quyền khiếu nại về 
điểm số. Thường thì ông thầy sẽ kiểm tra kĩ 1-2 đứa đầu tiên khiếu nại, còn tất cả những sinh viên sau 
đó khiếu nại thì auto là được nâng lên 1-2 điểm. Là đứa ở xa trường, dĩ nhiên là mình biết thông tin 
này quá muộn và không kịp thời gian để khiếu nại rồi.😀 Chán vl!

Lòng vòng như vậy là đủ rồi. Ví dụ bên trên mình chỉ muốn nói là rất nhiều môn thi có thể dễ thở hơn
nếu biết trước được đề thi - thường đề thi hàng năm không bao giờ đổi cả. Nếu có đổi thì chỉ đổi mỗi số thôi, các thầy cô cũng lười đổi đề lắm.😏

Giờ chúng ta sẽ đi vào các mốc thời gian quan trọng mà mình đã trải qua nha

- **28-09-2022**: Công bố danh sách giao nhiệm vụ đề tài & kết quả việc nộp đơn chuyển từ làm Đồ án tốt nghiệp sang học Môn thay thế.

- **01-10-2022**: Sinh viên được liên hệ với giáo viên hướng dẫn để hiệu chỉnh, bổ sung nội dung đề tài đồ án tốt nghiệp đến ngày 01/10/2022.

- **11-10-2022**: Nhà trường công bố lại danh sách giao đồ án tốt nghiệp Đại Học Chính Quy khóa 2018-2023

- **26-10-2022**: Nộp báo cáo đồ án tốt nghiệp lần 1

- **11-11-2022**: Nộp báo cáo đồ án tốt nghiệp lần 2

- **30-11-2022**: Nộp báo cáo đồ án tốt nghiệp lần 3

- **09-12-2022**: Nộp đồ án tốt nghiệp

> Quy tắc đặt tên áp dụng cho cả 3 lần - Lớp (mã chữ)_Mã sinh viên(3 số cuối)_Họ và tên _BCDK1
> 
> VD: CP_147_NguyenThanhPhong_BCĐK1
>
> Lưu ý: đặt tên file giống hướng dẫn, đặt sai sẽ bị thống kê không nộp báo cáo định kỳ, cấm nộp cuốn báo cáo cuối kỳ.
# [**Post Script**](#post-script)

**05-10-2022**

Mình không muốn miệt thị chính mái trường mình đã theo học nhưng thực sự là nhiều cái nó như lìn 😋😋. 
Các bạn cứ đọc tấm ảnh phía dưới là sẽ hiểu.

<p align="center">
    <img src="./photo/truong-nhu-lon.png" />
</p>
<h3 align="center">

***Minh chứng cho sự hãm lìn của trường PTIT***
</h3>

Ban đầu mình không định học làm đồ án tốt nghiệp vì đơn giản là làm đồ án thì khó, mệt và rất áp lực. Học thay thế thì nhẹ nhàng hơn.
Quan trọng hơn cả là kết quả cuối cùng thì mình sẽ vẫn ra trường và xếp loại tốt nghiệp không bị thay đổi. Tức là nếu bạn đạt học lực GIỎI
thì dù bạn học thay thế để ra trường thì nó vẫn là bằng GIỎI.

Vậy mà đùng một cái, nhà trường **từ chối hết tất cả đơn xin chuyển từ đồ án sang học thay thế** 🙂🙂🙂 ( cái dm trường, thế thì ngay từ đầu nói vậy đi cho rồi. Để sinh viên nộp 
đơn cho sướng vào rồi cuối cùng vẫn bắt sinh viên làm đồ án tốt nghiệp). Thực sự là mình cay không thể tả nổi.

Mình đã đánh đổi bằng việc kết thúc sớm chương trình thực tập tại GeoComply ngày 03/10/2022 để đánh đổi bằng việc học thay thế cho khỏe thân.
 Ai dè giờ vẫn phải làm đồ án tốt nghiệp. Chán thực sự chán 😣😣

# [**Bonus**](#bonus)

Dưới đây là cơ sở dữ liệu mà mình phát triển lên từ Đồ án thực tập. Cơ sở dữ liệu này mình thiết kế từ trước phải làm đề tài.
Sau đó thì thay đổi suy nghĩ để chuyển qua làm học thay thế tốt nghiệp.

<p align="center">
    <img src="./document/eshop-diagram.png" />
</p>
<h3 align="center">


# [**Timeline**](#timeline)

## [**Phase 0: 01-09-2022 to 28-09-2022**](#phase-0-01-09-2022-to-28-09-2022)

- **04-09-2022**: Thi vấn đáp với giáo viên hướng dẫn về đồ án thực tập

- **09-09-2022**: Thi vấn đáp với giáo viên phản biện về đồ án thực tập 

- Thời gian còn lại: đi làm và xả hơi sau thời gian dài làm đồ án thực tập căng thẳng.

## [**Phase 1: 19-09-2022 to xx-xx-2022**](#phase-1-xx-xx-2022-to-xx-xx-2022)

- **19-09-2022**: Thiết kế cơ sở dữ liệu với nhiều bảng nhằm triển khai các chức năng phức tạp hơn

- **04-10-2022**: Thiết kế cơ sở dữ liệu theo đề tài nhà trường giao.

- **05-10-2022**: Tối ưu cơ sở dữ liệu và tìm hiểu kĩ các trường hợp đặc biệt của đề tài.

- **06-10-2022 -> 09-10-2022**: Làm một số việc vặt và chủ yếu là ăn chơi, nghỉ việc thấy hụt hẫng quá, hic hic 😥

- **10-10-2022**: 

1. Xây dựng chương trình để viết API

2. Chức năng đăng nhập cho bác sĩ 

3. Specialities Controller & Speciality Controller - Đọc thông tin tất cả các chuyên khoa

4. Nghiên cứu cách gửi email với PHP Mailer.

- **11-10-2022**:

1. Hoàn thiện tính năng gửi email khi có tài khoản mới đăng kí - Sign Up Controller 

2. Hoàn thiện tính năng khôi phục mật khẩu với mã xác thực được gửi qua email - Recovery Controller & Password Reset Controller

- **12-10-2022**:

1. Hoàn thiện tính năng quản lí các địa chỉ phòng khám - Clinics Controller & Clinic Controller

2. Đọc toàn bộ thông tin các bác sĩ và tạo tài khoản mới với mật khẩu được gửi về bằng Email - Doctors Controller 

3. Đọc thông tin chi tiết của một bác sĩ - Read của Doctor Controller.

- **13-10-2022**:

1. Hoàn thiện tính năng thêm - sửa - xóa thông tin bác sĩ - Create Update Delete của Doctor Controller. 

2. Khai báo đầu đủ toàn bộ các model của đồ án.

3. Sửa lại phần Login Controller để xử lý đăng nhập của bệnh nhân bằng OTP gửi tới số điện thoại.

- **14-10-2022**:

1. Các chức năng cập nhật thông tin cá nhân, mật khẩu và ảnh đại diện dành cho bác sĩ - Doctor Profile Controller

2. Quản lý thông tin bệnh nhân

| Tên        | Chức năng                                                                         |
|------------|-----------------------------------------------------------------------------------|
| Read All   | Xem toàn bộ thông tin bệnh nhân                                                   |
| Read By ID | Cập nhật thông tin của 1 bệnh nhân                                                |
| Create     | Không làm vì bệnh nhân đăng nhập bằng PHONE NUMBER và GOOGLE                      |
| Update     | Cập nhật thông tin của 1 bệnh nhân                                                |
| Delete     | Không làm vì có thể đem thông tin bệnh nhân đi bán kiếm tiền 🤑🤑🤑. Kiếm tiền là dễ |

- **15-10-2022**:

1. Sửa lại chỗ tạo JWT token để phân biệt yêu cầu đăng nhập giữa BÁC SĨ và BỆNH NHÂN.

2. Các chức năng cập nhật thông tin cá nhân, mật khẩu và ảnh đại diện dành cho bệnh nhân - Patient Profile Controller

- **16-10-2022**: Chủ nhật rồi, gác lại âu lo thôi 🎉

- **17-10-2022**:

1. Viết báo cáo phân tích đề tài và mô tả các use-case 

2. Thiết kế và mở rộng database 

3. Mô hiệu hóa bảng Clinic( phòng khám ) vì đây là ứng dụng dành cho một phòng khám độc lập.

4. Tạo mới Service Model và Controller tương ứng.

# [**Controller Timeline**](#controller-timeline)

Phần này mình lưu lại trình tự mình viết đồ án này. Nếu các bạn có nhu cầu tham khảo cách xử lý của mình. Các bạn có thể đọc code theo trình tự 
đang được viết theo số thứ tự ở bên dưới.

1. Index Controller

2. Login Controller

3. Specialities Controller

4. Speciality Controller

5. Sign Up Controller

6. Recovery Controller

7. Password Reset Controller

8. Clinics Controller

9. Clinic Controller

10. Doctors Controller 

11. Doctor Controller

12. Doctor Profile Controller 

13. Patients Controller

14. Patient Controller

15. Patient Profile Controller

16. Service Controller 
    
17. Servives Controller 
    
# [**Special Thanks**](#special-thanks)

<table>
        <tr>
            <td align="center">
                <a href="https://github.com/ngdanghau">
                    <img src="./photo/Hau.jpg" width="100px;" alt=""/>
                    <br />
                    <sub><b>Nguyễn Đăng Hậu</b></sub>
                </a>
            </td>
        </tr>    
</table>
 
# [**Made with 💘 and PHP <img src="https://www.vectorlogo.zone/logos/php/php-horizontal.svg" width="60">**](#made-with-love-and-php)