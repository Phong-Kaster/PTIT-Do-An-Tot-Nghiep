<h1 align="center"> Học viện Công nghệ Bưu chính viễn thông <br/>
    Đồ án tốt nghiệp - 
    Ứng dụng Android hỗ trợ bệnh nhân đăng ký khám và điều trị bệnh 
</h1>

<p align="center">
    <img src="./photo/umbrella-corporation-black-donnell-rose.jpg" />
</p>


# [**Table Of Content**](#table-of-content)
- [**Table Of Content**](#table-of-content)
- [**Introduction**](#introduction)
- [**Topic**](#topic)
- [**API Document**](#api-document)
- [**Database**](#database)
- [**Document**](#document)
- [**Features**](#features)
  - [**1. Login**](#1-login)
  - [**2. Sign Up**](#2-sign-up)
  - [**3. Recovery Password**](#3-recovery-password)
- [**Milestone**](#milestone)
- [**Post Script**](#post-script)
    - [**05-10-2022**](#05-10-2022)
    - [**20-10-2022**](#20-10-2022)
  - [**12-12-2022**](#12-12-2022)
  - [**18-12-2022**](#18-12-2022)
  - [**24-12-2022**](#24-12-2022)
  - [**26-12-2022**](#26-12-2022)
  - [**28-12-2022**](#28-12-2022)
- [**29-12-2022**](#29-12-2022)
- [**Bonus**](#bonus)
- [**Timeline**](#timeline)
  - [**Phase 0: 01-09-2022 to 28-09-2022**](#phase-0-01-09-2022-to-28-09-2022)
  - [**Phase 1: 19-09-2022 to 25-10-2022**](#phase-1-19-09-2022-to-25-10-2022)
  - [**Phase 2: 26-10-2022 to 13-11-2022**](#phase-2-26-10-2022-to-13-11-2022)
  - [**Phase 3: 15-11-2022 to 03-12-2022**](#phase-3-15-11-2022-to-03-12-2022)
  - [**Phase 4: 08-12-2022 to 12-12-2022**](#phase-4-08-12-2022-to-12-12-2022)
  - [**Phase 5: 16-12-2022 to 24-12-2022**](#phase-5-16-12-2022-to-24-12-2022)
- [**Controller Timeline**](#controller-timeline)
- [**Special Thanks**](#special-thanks)
- [Made with 💘 and PHP ](#made-with--and-php-)

# [**Introduction**](#introduction)

Chào các bạn, mình tên là Nguyễn Thành Phong. 
Mã số N18DCCN147. 
Niên khóa 2018-2023. 

Lời đầu tiên mình xin chào các bạn và cảm ơn tất cả các bạn đang ở đây. Trong tài liệu này mình sẽ chia sẻ tất cả những gì các bạn cần biết khi làm đồ án 
tốt nghiệp và đề tài do mình thực hiện để các bạn có thể tham khảo. Mình hi vọng phần tài liệu mình viết tiếp theo đây 
sẽ hỗ trợ phần nào cho các bạn khi bước tới ngưỡng cửa quan trọng của cuộc đời mình - tốt nghiệp đại học.

Đề tài của mình có tổng cộng 3 thành phần chính là 
[API](#), 
[website](https://github.com/Phong-Kaster/PTIT-Do-An-Tot-Nghiep-Website) và 
[ứng dụng Android](https://github.com/Phong-Kaster/PTIT-Do-An-Tot-Nghiep-Android).

Các bạn đang đọc phần API của đồ án này.

# [**Topic**](#topic)

<p align="center">
    <img src="./photo/topic.png" />
</p>


Có thể giải thích yêu cầu đề tài ngắn gọn như sau:

**Website** - Đóng vai trò là ứng dụng quản trị viên. Hỗ trợ bệnh viện quản lý thông tin bác sĩ & bệnh nhân,
sắp xếp lịch khám bệnh giữa bác sĩ và bệnh nhân.

**Android** - Ứng dụng để bệnh nhân đặt lịch khám bệnh, theo dõi phác đồ điều trị và bệnh án của mình. Có thể đặt lịch khám bệnh
cho người thân trong gia đình như ông, bà, bố, mẹ & không nhất thiết người khám bệnh phải là bản thân mình.

Chúng ta sẽ cân phân tích đề tài kĩ hơn vì rất dễ gây nhầm lẫn. Cụ thể chính thầy hướng dẫn và thầy giáo 
phản biện đồ án của mình đã nghĩ thành 2 hướng khác nhau:

**Thầy Nguyễn Anh Hào - giáo viên hướng dẫn**: ứng dụng chỉ để bệnh nhân cung cấp thông tin cá nhân & rút ngắn thời gian khám bệnh. 
Vẫn có chức năng đặt lịch hẹn khám nhưng chỉ để cung cấp thông tin cá nhân, nhằm rút ngắn thời gian khám của bác sĩ. Hoạt động trên 
nguyên tắc `ai đến trước thì được khám trước`, không chấp nhận việc đặt giờ trả tiền trước để vào khám. Điều này ưu tiên 
những bệnh nhân nghèo, không thông thạo các thao tác trên di động, ưu tiên những người đã bỏ thời gian ra xếp hàng để khám bệnh.

**Thầy Huỳnh Trung Trụ - giáo viên phản biện**: ứng dụng là đặt lịch hẹn với bác sĩ. Tức cho chọn chuyên khoa, chọn bác sĩ khám bệnh trước & dĩ nhiên 
là chọn giờ khám luôn. Hoạt động trên nguyên tắc `tôi đặt lịch hẹn thì đúng giờ đó tui phải được vào khám`.

Như trên thì lý luận của 2 thầy đều đúng và Phong thì thiết kế chương trình theo giáo viên hướng dẫn của mình.👼👼👼
# [**API Document**](#api-document)

Mình có soạn thảo và liệt kê chi tiết cách sử dụng các chức năng mà mình đã xây dựng thành tài liệu.
Nếu các bạn có nhu cầu muốn tham khảo, hãy ấn vào [**đây**](https://github.com/Phong-Kaster/PTIT-Do-An-Tot-Nghiep-API-Document) để đọc chi tiết cách sử dụng API này.

# [**Database**](#database)

<p align="center">
    <img src="./photo/database-version-12-prototype.png" />
</p>
<h3 align="center">

***Sơ đồ cơ sở dữ liệu***
</h3>

Tớ sẽ giải thích qua về ý nghĩa các bảng xuất hiện trong database nha

**BẢNG PATIENTS** - bảng này chứa thông tin của bệnh nhân.

**BẢNG BOOKING** - bảng này chứa các lịch hẹn mà bệnh nhân đăng ký khám bệnh.

**BẢNG APPOINTMENTS** - bảng này chứa thứ tự lượt khám thực tế của trong ngày của các bác sĩ

**BẢNG TREATMENTS** - bảng này chứa phác đồ điều trị sau khi đã khám xong. Phác đồ điều trị là hướng
dẫn mà bệnh nhân phải tuân thủ như: lịch uống thuốc, lịch tái khám,.....

**BẢNG APPOINTMENTS RECORDS** - lưu trữ bệnh án của bệnh nhân. Mỗi bệnh án đi kèm với một lịch khám thực tế ( tức appointment )

**BẢNG DOCTORS** - lưu trữ thông tin của các bác sĩ.

**BẢNG SPECIALITIES** - bảng này thể hiện tên chuyên khoa của bác sĩ. Ví dụ: Nội khoa, Răng - hàm - mặt,..

**BẢNG NOTIFICATIONS** - mỗi khi một lịch khám thực tế được thực hiện với bác sĩ. Bảng này sẽ chứa thông báo cho bác sĩ đó. 

**BẢNG SERVICES** - chứa tên các dịch vụ mà ứng dụng hỗ trợ. Ví dụ: Khám sức khỏe tổng quát,
khám thai, xét nghiệm PCR COVID-19,...

**BẢNG DOCTOR AND SERVICE** - thể hiện mối quan hệ một nhiều. Khi một bác sĩ có thể phục vụ nhiều 
loại hình dịch vụ khác nhau. Ví dụ, một bác sĩ chuyên khoa Nội tổng hợp thì vẫn có thể khám về Da liệu.

**BẢNG DRUGS** - bảng này chứa tên các loại thuốc. Bác sĩ chỉ cần nhập các chữ cái
đầu trong tên thuốc bởi trong thực tế thì sẽ không để bác sĩ nhập tay tên thuốc có thể dẫn tới nhầm lẫn.

>Fact 1: Để đến được phiên bản cuối cùng của database này mình đã trải quả tổng cộng 12 lần sửa đổi. 
>
>
>Fact 2: Trong thư mục photo của dự án này, với phiên bản database từ 10 đến 12, các bạn sẽ thấy có 2
kiểu ảnh. Kiểu có hậu tố prototype là theo thiết kế dạng chuẩn 3 (và cũng là các kiểu mà thầy 
sẽ thích hơn). 
>
>Ngược lại, với các phiên bản không có đuôi prototype như `database-version-12.png` chẳng hạn
thì mới thực sự là database thật của mình. Mình thiết kế theo hướng này vì ban đầu mình 
cũng suy nghĩ hướng của thầy Trụ và mình định làm chức năng đăng ký hộ cho người nhà. Tức người đăng ký và 
người khám bệnh là khác nhau

# [**Document**](#document)

Khi tải repository này về, bạn sẽ thấy một thư mục tên `document` nằm trong thư mục api. Trong thư mục này, mình 
để lại cho các bạn một số tài liệu quan trọng

- **Danh-sach-de-tai-tot-nghiep** - là tệp tin excel tổng hợp lại toàn bộ các đề tài của từng sinh khóa D18

- **Tập hợp các tệp tin có tiền tố eshop** - là database mình mở rộng ra từ đồ án thực tập( Rất tiếc là không có cơ hội sử dụng 😥)

- **Noi-dung-quyen-bao-cao** - là các yêu cầu chung để làm báo cáo tốt nghiệp. Tuy nhiên thì tệp tin này do thầy **Nguyễn Anh Hào** soạn 
và gửi cho các sinh viên thầy hướng dẫn. Không biết các giáo viên khác có gửi không nữa😅😅

- **README.md** - là một tệp tin markdown khác, mình tạo ra nó để mô tả cách đọc và sử dụng API do mình viết.

- **Các file DoAnTotNghiep-MySQL-v(x).txt** - với x là số thứ tự - đây chính là file để khởi tạo 
database và dữ liệu đi kèm ban đầu. `DoAnTotNghiep-MySQL-v12.txt` là file tạo database phien bản cuối cùng
của mình.

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

- **02-12-2022**: Lớp trưởng nhận phiếu giao nhiệm vụ tại Văn Phòng Khoa Quận 9

- **Từ 05-12-2022 đến 09-12-2022**: Nộp file báo cáo lên Google Drive trước khi bảo vệ đồ án

- **12-12-2022**: Nhà trường công bố danh sách giáo viên phản biện trước khi chấm đồ án

- **Từ 12-12-2022 đến 22-12-2022**: Sinh viên tự liên hệ với giáo viên phản biện để chấm.

- **18-12-2022**: Báo cáo với giáo viên hướng dẫn(thầy Nguyễn Anh Hào) và giáo viên phản biện(Với mình là thầy Huỳnh Trung Trụ).

Lưu ý: Mỗi giáo viên phản biện sẽ có hình thức chấm khác nhau. Thầy Trụ thì online nhưng có thầy cô khác thì phải lên trực tiếp trường 
để chấm.

- **Từ 24-12-2022 đến 26-12-2022**: Bảo vệ đồ án tốt nghiệp trước hội đồng nhà trường tại Quận 1 
& nộp 03 cuốn tóm tắt đề tài tại Hội đồng chấm bảo vệ đồ án tốt nghiệp tại Quận 1

- **26-12-2022**: lễ bảo vệ đồ án tốt nghiệp chính thức niên khóa 2018-2023.

- **05-01-2023**: nộp 02 quyển bìa cứng đã có chữ ký xác nhận của Giáo Viên Hướng Dẫn và 
Giáo Viên Phản Biện và file dữ liệu tại Văn Phòng Khoa Quận 9.


(*) Quy tắc đặt tên áp dụng cho cả 3 lần - Lớp (mã chữ)_Mã sinh viên(3 số cuối)_Họ và tên _BCDK1
> 
> VD: CP_147_NguyenThanhPhong_BCĐK1
>
> VD: CP_147_NguyenThanhPhong_BCĐK2
>
> VD: CP_147_NguyenThanhPhong_BCĐK3
>
> Lưu ý: đặt tên file giống hướng dẫn, đặt sai sẽ bị thống kê không nộp báo cáo định kỳ, cấm nộp cuốn báo cáo cuối kỳ.
>
> Link: https://drive.google.com/drive/folders/1uIiNBMOLkPi9sHxEU5y7fqAoXa8bKaOT


(*) Quy tắc đặt tên cho thư mục chứa đồ án tốt nghiệp 
> Tên folder:  Lớp (mã chữ)_Mã sinh viên(3 số cuối)_Họ và tên _BCCK
>
> VD: CP_147_NguyenThanhPhong_BCCK
> 
> Link: https://drive.google.com/drive/folders/16T43kMyM_4VS9ESoapeT1pfPyi87SSUy


(*) Link nộp đồ án trên Google Drive thì phải tới ngày nộp nhà trường sẽ gửi qua Email, những link bên trên chỉ có hiệu lực với khóa D18 của Phong.

# [**Post Script**](#post-script)

### [**05-10-2022**](#)

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

### [**20-10-2022**](#)

Chúc mừng ngày Phụ nữ Việt Nam 2022 

Yeah, hôm qua là lần cuối cùng mình nộp tiền học phí cho trường 😂😁😂. Thế là hết nợ rồi.

Nếu chỉ vậy thì mình sẽ không viết những dòng này. Trường mình có một hệ thống làm việc rất quan liêu và hết sức vớ vẩn.
Làm đồ án tốt nghiệp hay học môn thay thế các bạn đều **PHẢI ĐÓNG HỌC PHÍ** với niên khóa của mình - 2018-2023 - thì số tiền là 
**3.300.000 đồng**. Tuy nhiên đó chưa phải là điều mình bức xúc nhất. 

Điều bức xúc ở đây là sự quan liệu tới mức vô tổ chức. Nhà trường không hề viết một công văn, một thông báo chính thức gì để gửi tới
sinh viên cả 😤😤😤😤😤( điên máu thực sự). Rất may mắn là bạn lớp trưởng lớp mình chủ động hỏi phòng tài chính về thời hạn đóng 
học phí thì....

**ỐI GIỜI ƠI ! Hạn đóng học phí là từ ngày 14-10-2022 đến ngày 19-10-2022** nhưng tụi mình chỉ mới nắm thông tin ngày 18-10-2022.
Chán thực sự chán luôn ạ. Làm ăn vớ va vớ vẩn.

Nếu học phí của đồ án( hoặc học thay thế) không nộp đúng hạn thì nhà trường sẽ **cho rằng chúng ta tự ý bỏ học và hủy toàn bộ kết 
quả học tập trong suốt 4 năm vừa qua**. 

Việc hủy kết quả học tập tưởng chừng chỉ là đòn hù của nhà trường nhưng không nha các bạn. Tớ
đã chứng kiến việc nộp học phí muộn mà kết quả học tập 1 học kì bị hủy và bạn đó phải học lại những môn của kì đó rồi. Do vậy dĩ nhiên 
là nộp chậm học phí cho đồ án ( học môn thay thế ) không phải là một lời đe dọa ạ. Méo hiểu sao tiền thì thu nhanh và không bớt 
một đồng nhưng việc giải nhân tiền học bổng và sửa chữa cơ sở vật chất thì.... Nói chung là chán lắm, không muốn nói nữa🥱🥱🥱🥱

## [**12-12-2022**](#)

TUI, NGUYỄN THÀNH PHONG, XIN TRÂN TRỌNG THÔNG BÁO VỚI TOÀN THỂ QUÝ ZỊ 🔊🔊🔊 NGÀY HÔM NAY, 
TỨC NGÀY 12-12-2022, TÔI ĐÃ CHÍNH THỨC HOÀN THÀNH ĐỒ ÁN TỐT NGHIỆP CỦA MÌNH VÀ 100% YÊU CẦU CỦA THẦY NGUYỄN ANH HÀO ✌✌

## [**18-12-2022**](#)

Làm đúng ý thầy Hào thì lại sai ý thầy giáo phản biện. Chán vãi l*n, giờ lại phải è cổ ra sửa lại 
cho đúng.

## [**24-12-2022**](#)

Hôm nay mới thực sự là xong !

## [**26-12-2022**](#)

LỄ BẢO VỀ ĐỒ ÁN TỐT NGHIỆP ĐẠI HỌC CHÍNH QUY 
NGÀY CÔNG NGHỆ THÔNG TIN, AN TOÀN THÔNG TIN & CÔNG NGHỆ ĐA PHƯƠNG TIỆN - KHÓA HỌC 2018-2022

<p align="center">
    <img src="./photo/ngay-le-bao-ve-do-an.jpg" width="600px"/>
</p>

Tui, Nguyễn Thành Phong đã chính thức xuất sắc hoàn thành đồ án tốt nghiệp với đánh giá 
XUẤT SẮC, điểm quy đổi là 9.2 

## [**28-12-2022**](#)

Hôm nay, là đã 2 ngày trôi qua kể từ buổi lễ bảo vệ đồ án tốt nghiệp của mình. Vậy là chặng 
đường 4 năm rưỡi ngồi trên giảng đường đại học đã kết thúc. Một chương mới trong cuộc đời đang 
mở ra. Cháy hết mình nào 🔥🔥🔥.

Với đánh giá đồ án thuộc loại XUẤT SẮC và điểm là 9.2. Mình cảm thấy rất tự hào về chính những 
thành tựu mình đã đạt được. Thời gian làm đồ án cũng thuộc dạng là ngắn. Mình ước tính khoảng 2 tháng rưỡi.
Mình đã xây dựng được trọn bộ API + Website + ứng dụng Android để thực hiện đồ án này.

Thực sự với riêng bản thân mình thì quãng thời gian thực tập & tốt nghiệp khá là căng thẳng. Khi ngày nào 
mình cũng phải ngồi làm việc 10-12 tiếng mỗi ngày để kịp thời hoàn thiện được đồ án này. Đó là một khoảng thời gian 
khó khăn cho dù mình đã chuẩn bị tâm lý từ rất sớm.

Tuy nhiên, với việc bảo vệ đồ án thành công thì mình cảm thấy những khó khăn vừa qua chỉ như 1 khoảnh khắc trong cuộc đời.
Mọi nỗ lực đã được đền đáp xứng đáng. Tự hào là 2 từ duy nhất lúc này mình cảm nhận khi viết những dòng lưu bút ngày.

# [**29-12-2022**](#)

Cuối cùng thì sau bao ngày chờ mong thì học bổng Xuất sắc cũng đã xướng tên Nguyễn Thành Phong tui.
Zui 😍😍😎😎. 

<p align="center">
    <img src="./photo/HocBongCuoiCung.png">
</p>
<h3 align="center">

***Học bổng Xuất sắc học kì 2 - năm học 2021-2022***
</h3>

>Fact: Mình có tổng cộng 3 lần đạt học bổng vào các năm 
>* Học kì 2 - năm học 2020-2021( tức kì 2 năm 4 ) - học bổng giỏi
>* Học kì 1 - năm học 2021-2022( tức kì 1 năm 4 ) - học bổng giỏi 
>* Học kì 2 - năm học 2021-2022( tức kì 2 năm 4 ) - học bổng xuất sắc

# [**Bonus**](#bonus)

Dưới đây là cơ sở dữ liệu mà mình phát triển lên từ Đồ án thực tập. Cơ sở dữ liệu này mình thiết kế từ trước phải làm đề tài.
Sau đó thì thay đổi suy nghĩ để chuyển qua làm học thay thế tốt nghiệp.

<p align="center">
    <img src="./photo/eshop-diagram.png" />
</p>
<h3 align="center">


# [**Timeline**](#timeline)

## [**Phase 0: 01-09-2022 to 28-09-2022**](#phase-0-01-09-2022-to-28-09-2022)

- **04-09-2022**: Thi vấn đáp với giáo viên hướng dẫn về đồ án thực tập

- **09-09-2022**: Thi vấn đáp với giáo viên phản biện về đồ án thực tập 

- Thời gian còn lại: đi làm và xả hơi sau thời gian dài làm đồ án thực tập căng thẳng.

## [**Phase 1: 19-09-2022 to 25-10-2022**](#phase-1-19-09-2022-to-25-10-2022)

(1) Mục tiêu: Giai đoạn này xây dựng RestfulAPI - là linh hồn của toàn bộ đồ án này.

(2) Uớc tính: 14 ngày 

(3) Thực tế: Bắt đầu ngày 04-10-2022 & kết thúc ngày 27-10-2022. Suy ra, mất 22 ngày để hoàn thiện. 
Trong đó có một tuần mình chưa làm gì bởi vấn đề tâm lý sau khi nghỉ việc

- **19-09-2022**: Thiết kế cơ sở dữ liệu với nhiều bảng nhằm triển khai các chức năng phức tạp hơn

- **03-10-2022**: Chính thức nghỉ việc tại GeoComply - công ty đầu tiên trong sự nghiệp của mình😥😥😥😥😥😥😥😥

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

- **18-10-2022**:

1. Quản lý các dịch vụ mà một bác sĩ hỗ trợ - Doctors And Services Controller 

2. Chức năng tạo lịch khám bệnh cho phía bênh nhân - Patient Booking Controller - kiểm 
tra dữ liệu đầu vào lâu vl, nhất là thời gian đặt lịch khám.

- **19-10-2022**:

1. Viết tài liệu API phần Authentication và Patients

- **20-10-2022**:

1. Thêm Patient Booking Controller để hỗ trợ bệnh nhân xem chi tiết một booking, hỗ trợ hủy booking.

2. Quản lý các Booking cho bác sĩ vai trò ADMIN và SUPPORTER

| Tên        | Chức năng                                                                                                                                                           |
|------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Read All   | Xem toàn bộ thông tin các lịch hẹn                                                                                                                                  |
| Read By ID | Đọc thông tin chi tiết của 1 lịch hẹn                                                                                                                               |
| Create     | Tạo mới 1 lịch hẹn nhưng KHÔNG KHUYẾN KHÍCH. Thay vào đó tạo thẳng lịch khám bệnh luôn                                                                              |
| Update     | Thay đổi thông tin của 1 lịch hẹn với điều kiện trạng thái phải là processing                                                                                       |
| Confirm    | Trả lời lịch hẹn của bệnh nhân. Nếu CANCELLED thì cập nhật trạng thái, nếu VERIFIED thì ngoài cập nhật trạng thái sẽ tiến hành tạo mới lịch khám bệnh(appointment)  |

3. Sửa lý phần đặt lịch hẹn cho bệnh nhân.

Tóm tắt:

- **Trường hợp 1** - khách vãng lai không đặt lịch qua Android thì ngày khám mặc định là ngày hôm nay & lấy số thứ tự luôn.

- **Trường hợp 2** - khách đặt qua Android thì ngày khám sẽ là ngày được đặt trong Android và sẽ KHÔNG ĐƯỢC lấy số thự ngay. Chỉ khi họ 
xuất hiện ở bệnh viện thì mới có số thứ tự.

- **21-10-2022**:

1. Viết tài liệu cho API Patient Profile 

2. Viết tài liệu cho API Patient Booking

3. Các chức năng chỉnh sửa cho lịch khám bệnh (appointment) - Appoitment Controller

- **22-10-2022**

1. Viết chức năng quản lý phác đồ điều trị và chỉnh sửa thông tin - Treatments Controller và Treatment Controller
Các đơn thuốc hay hướng dẫn này chỉ được thay đổi khi appointment đang là PROCESSING.

- **23-10-2022**

1. Viết chức năng quản lý bệnh án cho mỗi lịch khám - Appointment Records và Appointment Record.

- **24-10-2022**

1. Tách riêng lịch khám bệnh từ BOOKING và NORMAL thành 2 nhánh riêng biệt.

2. Tạo Appointment Queue để truy vấn các appointment với các thông tin tối giản - Appoitment Queue Controller

3. Tạo Appointment Queue Now để lấy danh sách khám bệnh hiện tại cho cả BOOKING và NORMAL. - Appointment Queue Now Controller

4. Quản lý phòng khám để xác định vị trí bác sĩ làm việc - Rooms Controller và Room Controller.

- **25-10-2022**

1. Viết báo cáo định kì lần 1

## [**Phase 2: 26-10-2022 to 13-11-2022**](#phase-2-26-10-2022-to-13-11-2022)

(1) Mục tiêu: giai đoạn này viết website với các API đã được xây dựng ở bên trên

(2) Uớc tính: 20 ngày

(3) Thực tế: làm liên tục từ ngày 26-10-2022 đến ngày 13-11-2022 thì hoàn thành các chức năng cho website. Suy ra mất 19 ngày để hoàn thành website.

- **26-10-2022**

1. Hoàn thiện báo cáo định kì lần 1 và nộp 

2. Tìm kiếm Front-end template cho Admin

3. Gộp website và api vào chung một dự án này, bao gồm:

| Tên        | Chức năng                               |
|------------|-----------------------------------------|
|Thư mục API |Chứa toàn bộ các RestfulAPI của đồ án này|
|Thư mục Website| Chứa website quản lý dành cho bác sĩ |

4. Dựng giao diện màn hình đăng nhập 

- **27-10-2022**:

1. Dựng giao diện cho chức năng đăng ký 

2. Dựng giao diện cho chức năng đăng nhập

3. Dựng giao diện chưa qua xử lý cho dashboard

- **28-10-2022**:

1. Tách các thành phần cho dashboard và các trang web còn lại

2. Dựng thanh điều hướng bên trái 

3. Dựng thanh điều hướng bên trên

- **29-10-2022**: 
  
1. Dựng xong nội dung trang dashboard.
   
2. Viết chart controller để tạo ra 2 biểu đồ cho dashboard

- **30-10-2022**: Chủ nhật rồi, gác lại âu lo thôi 

- **31-10-2022**:

1. Sửa lại câu truy vấn cho Charts Controller lấy đúng số lượt khám trong 7 ngày gần nhất. 
Tính từ ngày hôm nay

2. Sửa lại luồng xử lý xếp lịch hẹn giống với gợi ý thầy Hào. 

Tức đặt lịch hẹn chỉ là hình thức để bác sĩ biết trước bệnh án và ưu tiên cho những bệnh 
nhân bị các bệnh đặc biệt mà không thể đợi lâu. Chúng ta sẽ không phát số thứ tự 
cho những bệnh nhân BOOKING.

Thay vào đó, họ tới bệnh viện thì mới bắt đầu phát số. Nếu họ bị bệnh đặc biệt, ví dụ: bệnh trĩ.... hoặc người 
bệnh đã đặt thời gian vào khám thích hợp thì HỖ TRỢ VIÊN sẽ tiến hành sắp xếp thứ tự khám cho họ

3. Chỉnh sửa phần top navigation để hiển thị đúng thông tin

4. Xây dựng giao diện THỨ TỰ LỊCH KHÁM

5. Đổ dữ liệu vào bảng THỨ TỰ LỊCH KHÁM

- **01-11-2022**:

1. Xử lý jQuery các nút chức năng trong THỨ TỰ LỊCH KHÁM

2. Đổ dữ liệu vào các bộ lọc tìm kiếm trong THỨ TỰ LỊCH KHÁM

3. Sửa lại chức năng getAll() trong Appointments Controller để nhiều chi tiết dữ liệu hơn

- **02-11-2022**:

1. Hàm get All của Specialities | Doctors | Appointments Controller giờ sẽ được truy cập bởi 
tất cả bác sĩ ADMIN | SUPPORTER | MEMBER.

2. Trong phần quản lý THỨ TỰ KHÁM BỆNH

Nếu trạng thái lịch khám bệnh là PROCESSING thì hiển thị đủ 3 nút XONG | HỦY | XÓA
Nếu trạng thía lịch khám bệnh không là PROCESSING  thì ẩn 3 nút chức năng XONG | HỦY | XÓA

<p align="center">
    <img src="./photo/image4.png" />
</p>

Nếu bác sĩ đang nhập là MEMBER thì nút KHÁC sẽ có BỆNH ÁN và PHÁC ĐỒ ĐIỀU TRỊ.
Nếu bác sĩ không là MEMBER thì nút KHÁC sẽ chỉ có CHI TIẾT và SỬA.

<p align="center">
    <img src="./photo/image5.png" width=200 />
</p>

3. Chỉnh lại bộ câu lệnh SQL script version 4 và thêm một số dữ liệu mẫu để khi copy vào là có thể chạy được luôn. 

- **03-11-2022**:

1. Viết xong phần phân trang cho website bằng Javascript kết hợp JQuery(trước giờ chưa bao giờ làm kĩ 
phần này nên mất nhiều thời gian quá)

2. Dựng giao diện cho việc thêm và chỉnh sửa THỨ TỰ LỊCH KHÁM

- **04-11-2022**:

1. Chỉnh sửa giao diện cho 2 nút XÁC NHẬN và HỦY BỎ trong phần chỉnh sửa THỨ TỰ LỊCH KHÁM 

2. Viết jQuery xử lý sự kiện

3. Hoàn thành việc xử lý thêm & sửa thông tin lịch hẹn

4. Dựng giao diện phần sắp xếp thứ tự lịch khám

- **05-11-2022**:

1. Hoàn thiện chức năng sắp xếp lịch thứ tự khám.

2. Xem - xóa - lọc dữ liệu với LỊCH HẸN khám bệnh.

- **06-11-2022**:

1. Hoàn thiện phân trang | chức năng hủy  tạo thứ tự khám từ lịch hẹn | sửa thông tin LỊCH HẸN.

2. Chức năng sắp xếp lịch hẹn sẽ hiển thông tin của 2 người đang và khám kết tiếp nhưng 
không thể sắp xếp lịch thứ tự của họ.

- **07-11-2022**:

1. Sửa một số lỗi khi đặt THỨ TỰ KHÁM BỆNH từ lịch hẹn 

2. Sửa lại cách xử lý khi sắp xếp thứ tự khám bệnh.

3. Đổ dữ liệu chuyên khoa.

4. Bộ lọc tìm kiếm chuyên khoa 

5. Chức năng xóa chuyên khoa.

6. Dựng giao diện quản lý bệnh nhân, lọc dữ liệu

- **08-11-2022**:

1. Sửa một số lỗi vặt ở dashboard 

2. Chức năng sửa thông tin người bệnh 

3. Chức năng chọn hình ảnh đại diện để xem trước và cập nhật hình đại diện

- **09-11-2022**:

1. Tạo | thêm phòng khám mới 

2. Toàn bộ các chức năng thêm - sửa - xóa cho quản lý BÁC SĨ.

3. Phân quyền các menu cho các vai trò ADMIN - MEMBER - SUPPORTER

- **10-11-2022**:

1. Xem thông tin cá nhân 

2. Cập nhật ảnh đại diện & thông tin cá nhân 

3. Chức năng thay đổi mật khẩu cá nhân

- **11-11-2022**: Viết báo cáo đồ án tốt nghiệp định kì lần 2
  
- **12-11-2022**: Chức năng quản lý bệnh án

- **13-11-2022**: Hoàn thành chức năng LẬP PHÁC ĐỒ ĐIỀU TRỊ - ĐƠN THUỐC.

- **14-11-2022**: Nghỉ ngơi 

## [**Phase 3: 15-11-2022 to 03-12-2022**](#phase-3-15-11-12022-to-03-12-2022)

(1) Mục tiêu: giai đoạn này viết ứng dụng Android và hoàn tất đồ án

(2) Uớc tính: 20 ngày

(3) Thực tế: 25 ngày để hoàn thiện toàn bộ các chức năng chính của toàn bộ ứng dụng Android này.
Thời gian còn lại tính từ ngày 05-12-2022 trở đi sẽ được dùng để chỉnh sửa lại một số chức năng 
cho đẹp hơn. Sau này nếu có ai đó tham khảo code mình làm sẽ dễ đọc dễ làm quen hơn

- **15-11-2022**: Dựng thư mục Android và màn hình đăng nhập

- **16-11-2022**:

1. Android - Tích hợp Firebase để đăng nhập bằng số điện thoại 

2. Android - Tích hợp Google API để đăng nhập bằng tài khoản Google

3. API - Xử lý yêu cầu đăng nhập bằng tài khoản Google

4. Hoàn thiện chức năng đăng nhập cho ứng dụng Android

- **17-11-2022**

1. Dựng màn hình Home cho Android 

2. Thêm trường image cho Service và Speciality trong API và Website.

3. Dựng giao diện Trang chủ cho Android

- **18-11-2022**: Nghiên cứu cách Android gửi yêu cầu tới Server mỗi 5 phút.

- **19-11-2022**: Nghiên cứu thành công IntentService để gửi yêu cầu tới Server mỗi 5 phút - Chức năng khó nhất đã được giải quyết

- **20-11-2022**: 

1. Tạo màn hình hiển thị thông tin cá nhân của bác sĩ

- **21-11-2022**:

1. Tạo màn hình hiển thị thông tin chuyên khoa

2. Màn hình tìm kiếm thông tin

- **22-11-2022**:

1. Thêm description cho bảng `tn_services`

2. Android - Sửa lại layout cho các kết quả tìm kiếm trong màn hình tìm kiếm thông tin

3. Android - Sửa lại luồng xử lý khi bệnh nhân đăng nhập bằng số điện thoại di động

4. Android - Thiết kế giao diện màn hình đặt lịch khám.

- **23-11-2022**:

1. Android - Tách Booking Activity thành 2 fragment riêng biệt.

2. Android - hoàn thiện chức năng Booking

3. Android - màn hình hướng dẫn thủ tục khám bệnh tích hợp Google Map

- **24-11-2022**:

1. API - thêm bảng notification và xử lý đọc thông báo cho BỆNH NHÂN

2. Android - hoàn thiện màn hình hướng dẫn thủ tục khám bệnh

3. Android - màn hình hiện thị chi tiết thông tin BOOKING

4. Android - xử lý để cập nhật trạng thái thông báo & số lượng thông báo chưa đọc

- **25-11-2022**:

1. API - Thêm patient appointments để người bệnh xem thông tin lịch khám

- **26-11-2022**:

1. Android - Thiết kế giao diện hiển thị lịch khám.

2. Android - Hoàn thiện tính năng nhắc thông báo cho bệnh nhân khi có lịch khám.

- **27-11-2022**:

1. Android - màn hình xem thông lịch khám

- **28-11-2022**:

1. Android - thêm danh sách hàng đợi vào màn hình thông tin lịch khám

2. API - Thêm Patient Records & Patient Record Controller để người dùng xem lại bệnh án của mình.

3. Android - màn hình xem bệnh án theo lượt khám

- **29-11-2022**: Chuẩn bị báo cáo định kì lần 3

- **30-11-2022**: 

1. Android - Thêm Swipe Refresh Layout vào màn hình thông tin lịch khám

2. Android - Màn hình liệt kê lịch sử khám bệnh 

3. Android - giao diện menu trong mục cá nhân

4. Android - giao diện xem phác đồ điều trị

5. Android - chế độ ban đêm & hỗ trợ đa ngôn ngữ

- **01-12-2022**: Tối ưu chức năng lịch sử khám bệnh

- **02-12-2022**:

1. Android - giao diện và chức năng cập nhật thông tin cá nhân

2. Android - chức năng cập nhật ảnh đại diện

- **03-12-2022**: Hoàn thiện chức năng gửi email

- **04-12-2022**: Chủ nhật rồi, gác lại âu lo thôi

- **05-12-2022**: Nghiên cứu thành công cách tạo lời nhắc thực hiện phác đồ điều trị bằng ứng dụng
Đồng hồ mặc định trên mọi thiết bị di động.

Chính thức hoàn thành siêu phẩm của một huyền thoại PTIT.

## [**Phase 4: 08-12-2022 to 12-12-2022**](#phase-4-08-12-2022-to-12-12-2022)

(1) Mục tiêu: bổ sung một số chức năng còn thiếu. Đặc biệt là chức năng bổ sung hồ 
sơ bệnh án trước khi khám của bệnh nhân do thầy Hào nhắc nên mình mới nhớ ra

(2) Uớc tính: 4 ngày

(3) Thực tế: 5 ngày.

- **08-12-2022**: 

1. API - Thêm bảng booking_photo để làm chức năng cung cấp bệnh án trước khi khám bệnh.

2. API - Thêm Booking Photos và Booking Photo xem toàn bộ ảnh của một booking

3. API - Thêm và xóa ảnh cung cấp để dành cho phía bệnh nhân

4. Web - Chức năng xem hồ sơ bệnh án do bệnh nhân cung cấp dưới dạng hình ảnh.

- **09-12-2022**: Web - Chức năng xem phác đồ điều trị

- **10-12-2022**:

1. Android - Chức năng xem lịch sử đặt lịch hẹn 

2. Android - Chức năng đăng ảnh để cung cấp cho bác sĩ xem đơn thuốc, bệnh án trước đó trong phần xem lịch sử đặt lịch hẹn 

- **11-12-2022**:

1. Android - Chức năng đăng ảnh để cung cấp cho bác sĩ sau khi đã tạo xong lịch hẹn

2. Web - Chỉnh sửa lại phần tạo lịch khám dựa vào lịch hẹn.

- **12-12-2022**:
  
1. Web - Chức năng quản lí các dịch vụ

2. Web - Chức năng tạo/ sửa thông tin dịch vụ

## [**Phase 5: 16-12-2022 to 24-12-2022**](#phase-5-16-12-2022-to-24-12-2022)

(1) Mục tiêu: chuẩn bị các thứ cuối cùng trước ngày lễ bảo vệ đồ án

(2) Uớc tính: Không có ước tính vì đây là quãng thời gian không nằm trong kế hoạch

(3) Thực tế: 9 ngày.

- **16-12-2022**: Soạn Power Point để tóm tắt đề tài & kiểm tra lần cuối ứng dụng Android.

- **18-12-2022**: Kiểm tra lại lần cuối trước khi báo cáo hôm nay với cả giáo viên hướng dẫn ( thầy Hào) và giáo viên phản biện ( thầy Trụ ).

- **19-12-2022**: 
1. Thêm cơ chế tự động lựa chọn bác sĩ phù hợp với nhu cầu khám bệnh.
Từ nay sẽ không phải chỉ định bằng tay nếu không bắt buộc

1. Android - Thêm nút tạo lịch hẹn với profile của bác sĩ

- **20-12-2022**: Android - Thêm mục "Cẩm nang" và "Tạp chí sức khỏe" trên màn hình chính

- **21-12-2022**:

1. Android - Thêm layout với kích thước hiển thị khác nhau cho "Cẩm nang" và "Tạp chí sức khỏe"

2. Web & API - mục phác đồ điều trị thêm cột "repeat_days" và "repeat_time"

- **22-12-2022**: Android - hoàn thiện lần cuối cùng.

- **23-12-2022**: Soạn lại power point để chuẩn bị báo cáo 

- **24-12-2022**: Hoàn thiện lần cuối cùng power point

# [**Controller Timeline**](#controller-timeline)

Phần này mình lưu lại trình tự mình viết đồ án này. Nếu các bạn có nhu cầu tham khảo cách xử lý của mình. Các bạn có thể đọc code theo trình tự 
đang được viết theo số thứ tự ở bên dưới.

01. Index Controller

02. Login Controller

03. Specialities Controller

04. Speciality Controller

05. Sign Up Controller

06. Recovery Controller

07. Password Reset Controller

08. Clinics Controller

09. Clinic Controller

10. Doctors Controller 

11. Doctor Controller

12. Doctor Profile Controller 

13. Patients Controller

14. Patient Controller

15. Patient Profile Controller

16. Service Controller 
    
17. Servives Controller 

18. Doctors And Services Controller

19. Patient Booking Controller 
     
20. Bookings Controller 

21. Booking Controller

22. Patient Bookings Controller

23. Appointments Controller 

24. Appointment Controller

25. Treatements Controller 

26. Treatment Controller.

27. Appointment Records Controller.

28. Appointment Records Controller.

29. Appoitment Queue Controller.

30. Appoitment Queue Now Controller

31. Rooms Controller 

32. Room Controller

33. Charts Controller

34. Login With Google Controller

35. Patient Notifications Controller 

36. Patient Notification Controller

37. Patient Appointments Controller 

38. Patient Appointment Controller

39. Patient Records Controller 

40. Patient Record Controller

41. Patient Treatments Controller

42. Booking Photos Controller 

43. Booking Photo Controller

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
 
# [Made with 💘 and PHP <img src="https://www.vectorlogo.zone/logos/php/php-horizontal.svg" width="60">](#made-with-love-and-php)