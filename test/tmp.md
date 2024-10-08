1. Giới thiệu về log và hệ thống quản lý log
Khái niệm log và vai trò của chúng trong an ninh mạng:
Log là gì? Giải thích khái niệm log như các bản ghi lại mọi hoạt động của hệ thống, ứng dụng, và người dùng. Tầm quan trọng trong việc giám sát và bảo mật hệ thống.
Vai trò của log: Log đóng vai trò quan trọng trong việc giám sát hiệu năng, phát hiện sự cố, và điều tra các vấn đề an ninh. Chúng cung cấp thông tin chi tiết về hoạt động mạng, hệ điều hành, và các ứng dụng.
Các loại log chính trong hệ thống:
Windows logs:
Vị trí lưu trữ: Logs trên Windows được lưu tại Event Viewer với các phân loại như Application, Security, System.
Cấu trúc và định dạng log: Giải thích về cấu trúc log (Event ID, Log Source, User Account, Time Stamp).
Ví dụ cụ thể về sự kiện bảo mật: Giới thiệu một vài sự kiện điển hình trong Windows logs như sự kiện đăng nhập (Event ID 4624) hoặc sự kiện thay đổi quyền hệ thống.
Linux logs:
Vị trí lưu trữ và loại log: Các log chính trên Linux như syslog, auth.log, dmesg, và được lưu trữ tại /var/log/.
Cấu trúc log trên Linux: Giới thiệu về cú pháp và định dạng của Linux logs (Timestamp, Log Level, Service Name, Message).
Ví dụ về log bảo mật trên Linux: Giải thích một số ví dụ như ghi nhận đăng nhập không hợp lệ từ auth.log.
Tại sao cần quản lý log tập trung: Khó khăn trong việc quản lý log từ nhiều nguồn khác nhau và sự cần thiết của một hệ thống quản lý log tập trung.
2. Quản lý log tập trung và hệ thống SIEM
Tổng quan về SIEM (Security Information and Event Management):

Chức năng của SIEM: Khả năng thu thập, lưu trữ, phân tích, và tương quan các sự kiện từ nhiều nguồn khác nhau để phát hiện các mối đe dọa và sự bất thường trong hệ thống.
Sự kết hợp giữa SEM và SIM: Quản lý sự kiện bảo mật (Security Event Management - SEM) và Quản lý thông tin bảo mật (Security Information Management - SIM) hợp nhất trong SIEM để theo dõi và phân tích sự kiện theo thời gian thực.
Lợi ích của hệ thống SIEM:

Giám sát sự kiện an ninh: SIEM giúp phát hiện các hành vi tấn công hoặc dấu hiệu xâm nhập bất thường.
Phân tích và điều tra: SIEM hỗ trợ quá trình điều tra sau khi xảy ra sự cố bằng cách cung cấp lịch sử chi tiết về sự kiện.
Tự động hóa bảo mật: SIEM có thể tự động cảnh báo khi phát hiện các dấu hiệu bất thường, giảm tải công việc cho đội ngũ bảo mật.
Cách thu thập log trong SIEM:

Push log: Các thiết bị gửi log trực tiếp đến hệ thống SIEM theo thời gian thực.
Pull log: SIEM tự động truy vấn và thu thập log từ các thiết bị theo chu kỳ.
Các sản phẩm SIEM phổ biến:

AlienVault OSSIM, Splunk, Q1Labs: Giới thiệu sơ lược về các giải pháp SIEM thương mại và mã nguồn mở, nêu bật tính năng và ưu điểm.
3. Phân tích và chuẩn hóa log
Tổng quan về quá trình chuẩn hóa log:

Khái niệm chuẩn hóa log: Chuyển đổi các loại log khác nhau từ nhiều nguồn về một định dạng chuẩn để dễ dàng phân tích và xử lý.
Vai trò của chuẩn hóa: Đảm bảo rằng tất cả các log từ các thiết bị và hệ điều hành khác nhau đều có thể được phân tích một cách nhất quán và chính xác.
Quy trình chuẩn hóa log:

Phân tích log ban đầu: Các log từ hệ thống ban đầu có thể có định dạng khác nhau và cần phải được chuẩn hóa.
Định dạng chuẩn chung: Giới thiệu một số định dạng chuẩn mà SIEM hoặc các hệ thống log sử dụng để chuẩn hóa log như JSON hoặc syslog RFC5424.
Phân tích log từ Windows và Linux:

Windows logs: Sử dụng các sự kiện cụ thể như log đăng nhập thành công/thất bại (Event ID 4625) để minh họa cách phân tích log bảo mật.
Linux logs: Minh họa bằng các log từ auth.log cho thấy việc đăng nhập SSH thành công hoặc thất bại.
Tương quan sự kiện từ nhiều nguồn log:

Ý nghĩa của tương quan log: Làm thế nào SIEM có thể liên kết các sự kiện từ nhiều nguồn khác nhau (Windows, Linux, ứng dụng web) để xác định tấn công mạng.
Ví dụ tương quan sự kiện: Mô tả một tình huống tấn công như Brute Force login từ nhiều thiết bị khác nhau.
4. Thực hành triển khai hệ thống quản lý log
Công cụ thu thập và phân tích log:

Giới thiệu về ELK Stack (Elasticsearch, Logstash, Kibana):
Elasticsearch: Cơ sở dữ liệu tìm kiếm và lưu trữ log mạnh mẽ.
Logstash: Công cụ thu thập và chuyển đổi log từ nhiều nguồn khác nhau.
Kibana: Giao diện đồ họa để hiển thị và phân tích log.
Syslog-NG và Fluentd: Các giải pháp mã nguồn mở khác để thu thập và gửi log.
Lab thực hành:

Bài Lab 1: Cài đặt và cấu hình ELK Stack.
Hướng dẫn từng bước cài đặt Elasticsearch, Logstash và Kibana trên hệ thống Linux.
Cách thu thập log từ hệ thống Windows và Linux.
Hiển thị và phân tích log bằng giao diện Kibana.
Bài Lab 2: Sử dụng Syslog-NG và Fluentd.
Cấu hình Fluentd để thu thập log từ ứng dụng và hệ thống, sau đó chuyển đến server.
Tích hợp Syslog-NG với các hệ thống mạng để thu thập log từ các thiết bị mạng.
5. Dự án của bạn: Xây dựng hệ thống thu thập và xử lý log đơn giản
Mục tiêu của dự án:

Phát triển hệ thống thu thập log tập trung, sử dụng một agent trên máy Windows để thu thập log và đẩy về server.
Mô hình kiến trúc:

Agent trên Windows: Sử dụng Windows API hoặc Event Forwarding để thu thập log bảo mật và hệ thống, sau đó gửi log đến server.
Server xử lý log:
Nhận log: Sử dụng các công cụ như Logstash hoặc Fluentd để thu thập và chuẩn hóa log.
Phân tích log: Áp dụng Sigma rules để tự động phát hiện các sự kiện bất thường hoặc tấn công.
Quy trình phân tích log với Sigma:

Giới thiệu Sigma rules: Các mẫu quy tắc phát hiện mối đe dọa dựa trên các sự kiện bảo mật cụ thể.
Ví dụ: Áp dụng một Sigma rule để phát hiện brute force hoặc tấn công bằng mã độc.
Kết quả và ứng dụng thực tế:

Kết quả triển khai: Mô hình hoạt động tốt với log bảo mật từ hệ thống Windows và các sự kiện an ninh được phân tích chính xác.
Ý nghĩa thực tiễn: Ứng dụng trong giám sát bảo mật hệ thống trong môi trường doanh nghiệp nhỏ hoặc vừa.
