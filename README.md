# 프로젝트 
리눅스 우분투에서의 스노트를 사용한 호스트 기반 침입 탐지 시스템(HIDS)

# 팀원
|<img src="https://avatars.githubusercontent.com/u/169283479?v=4" width="150" height="150"/>|<img src="https://avatars.githubusercontent.com/u/105273042?v=4" width="150" height="150"/>|
|:-:|:-:|
|[@visionn7111](https://github.com/visionn7111)|[@taebong113](https://github.com/taebong113)|

# 1.프로젝트 개요
### 1-1 프로젝트 설명
snort와 barnyard2 기반으로 만든 호스트기반 침입 탐지 시스템(HIDS) snort를 이용해 해당 서버로 오는 네트워크의 정보를 알 수 있고, 특정한 룰(Snort Rule)을 설정해 해당 서버가 네트워크 공격을 받았을 때 공격 유형을 탐지하여 barnyard2를 통해 DB에 로깅하고 Web으로 모니터링 하는 시스템

### 1-2 프로젝트 선정 이유
- 사이버 시대의 도래 : 거의 모든 산업과 생활 영역이 사이버화 되어 있고 사이버 공격이 더욱 심각한 영향을 미칠 수 있다고 판단하여, 개인정보를 보호하고 데이터의 무결성을 유지하는 것이 중요하다고 느낌
- 최근 해킹 공격의 증가 : 최근 몇 년 동안의 해킹 시도의 빈도와 심각성은 모든 종류의 조직, 기업, 개인에 심각한 위협을 주고 모두가 표적이 될 수 있음, 이러한 공격을 대비하기 위한 방어시스템 구축이 필요하다고 느낌

# 2.기술 스택
### OS
![js](https://img.shields.io/badge/Ubuntu-E95420?style=for-the-badge&logo=ubuntu&logoColor=white)
![js](https://img.shields.io/badge/Kali_Linux-557C94?style=for-the-badge&logo=kali-linux&logoColor=white)
### IDE
![js](https://img.shields.io/badge/Made%20for-VSCode-1f425f.svg)
### Language
![js](https://img.shields.io/badge/Shell_Script-121011?style=for-the-badge&logo=gnu-bash&logoColor=white)
![js](https://img.shields.io/badge/HTML-239120?style=for-the-badge&logo=html5&logoColor=white)
![js](https://img.shields.io/badge/CSS-239120?&style=for-the-badge&logo=css3&logoColor=white)
![js](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=JavaScript&logoColor=white)
![js](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
### DB
![js](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
### OPEN Source
  - Snort
https://www.snort.org
<img width="100" alt="스크린샷 2024-05-22 오후 5 59 46" src="https://github.com/visionn7111/snort_IDS/assets/169283479/aa1766e1-4556-48e4-ada7-2796ed7d4c0d">

  - barnyard2
https://github.com/firnsy/barnyard2

# 3.구성도
<img width="666" alt="구성도" src="https://github.com/visionn7111/snort_IDS/assets/169283479/60433f82-4190-471b-bf66-181f64dec8d2">

# 4.화면구성
### 웹 화면
<img width="1193" alt="스크린샷 2024-05-22 오후 6 33 04" src="https://github.com/visionn7111/snort_IDS/assets/169283479/39d713ec-f6ca-4ab9-a6c9-dd2792c9eb6a">

<img width="1188" alt="스크린샷 2024-05-22 오후 6 33 25" src="https://github.com/visionn7111/snort_IDS/assets/169283479/fe472284-5aa1-44c9-adea-67652a1b5f34">

### Snort & barnyard2 침입 탐지 화면
<img width="871" alt="스크린샷 2024-05-22 오후 6 37 47" src="https://github.com/visionn7111/snort_IDS/assets/169283479/9f4630e5-ebb7-4585-9221-e7c05b4d6176">

# 5. 탐지 가능한 네트워크 공격(Snort Rule)
### 5-1 Dos유형
|탐지 가능 공격|Snort Rule|
|------|---|
|Ping of Death 탐지|alert icmp any any -> 192.168.64.15 any (msg:"Ping of Death"; content:"\|585858585858\|"; threshold: type both, track by_src, count 10, seconds 20; sid:2000001;)|
|ICMP Flooding Attack 탐지|alert icmp any any -> 192.168.64.15 any (msg:"TEST: ICMP Flooding Attack"; threshold: type both, track by_src, count 300, seconds 1; sid:2000002; rev:1;)|
|TCP SYN Flooding Attack 탐지| alert tcp any any -> 192.168.64.15 any (msg:"TCP SYN Flooding Attack"; flags:S; threshold: type both, track by_src, count 10, seconds 20; sid:2000003;)|
|TCP ACK Flooding Attack 탐지| alert tcp any any -> 192.168.64.15 any (msg:"TCP ACK Attack"; flags:A; threshold: type both, track by_src, count 10, seconds 20; sid:2000004;)|
|TCP NULL Flooding Attack 탐지|alert tcp any any -> 192.168.64.15 any (msg:"NULL Attack"; flags:0; threshold: type both, track by_src, count 10, seconds 20; sid:2000005;)|
|UDP Flooding Attack 탐지|alert udp any any -> 192.168.64.15 any (msg:"UDP Floofing Attack"; threshold:type threshold, track by_src, count 5, seconds 1; sid:2000006;)|
|HTTP GET Flooding Attack|alert tcp any any -> 192.168.64.15 80 (msg:"HTTP GET Flooding Attack"; content :"GET / HTTP/1."; depth:13; nocase; threshold:type threshold, track by_src, count 100, seconds 1; sid:2000007;)|
#### 5-2 brute force 유형
|탐지 가능 공격|Snort Rule|
|------|---|
|SSH brute force 탐지|alert tcp any any -> 192.168.64.15 22 (msg:"Brute Force SSH"; threshold: type both, track by_src, count 10, seconds 20; content:"SSH-2.0"; sid:2000008;)|

|FTP brute force 탐지|alert tcp any any -> 192.168.64.15 21 (msg:"Brute Force FTP"; threshold: type both, track by_src, count 10, seconds 20; content:"Login incorrect"; sid:2000009;)|
|Telent brute force 탐지|alert tcp any 23 -> any any (msg: "Telnet Brute Force"; content: "Login incorrect"; nocase; threshold: type threshold, track by_dst, count 2, seconds 10; sid:2000010;)|

#### 5-3 원격 접속 시도 유형
|탐지 가능 공격|Snort Rule|
|------|---|
|telnet 탐지(로그인 실패)|alert tcp any 23 -> any any (msg:"INFO TELNET Bad Login"; flow:from_server,established; content:"Login incorrect"; nocase; classtype:bad-unknown; sid:2000011; rev:1;)|
|telnet 탐지(로그인 성공)|alert tcp any any -> 192.168.64.15 23 (msg:"Telnet Success"; content:"Documents and Setting"; nocase; sid:2000012;)|
|SSH 탐지|alert tcp any any -> 192.168.64.15 22 (msg:"SSH Connection"; content:"SSH-2.0"; nocase; sid: 2000013;)|
|XMAS 포트 스캔 탐지|alert tcp any any -> 192.168.64.15 any (msg:"TEST: nmap XMAS SCAN"; flags: FPU,CE;sid:2000014; rev:1;)|

#### 5-4 포트 스캔 탐지 유형
|탐지 가능 공격|Snort Rule|
|------|---|
|TCP Open Scan|alert tcp any any -> 192.168.64.15 any ( msg:"TCP_OpenScan!!!"; flags:S; threshold:type both,track by_src, count 10, seconds 3; sid:2000015; rev:1;)|
|Null Scan|alert tcp any any -> 192.168.64.15 any ( msg:"NULL_SCAN!!!"; flags:0; sid:2000016;)|

#### 5-5 비정상 패킷 탐지 유형
|탐지 가능 공격|Snort Rule|
|------|---|
|Land Attack|alert ip any any -> 192.168.64.15 any (msg:"Land Attack"; sameip; sid:2000017;)|
|SYN+FIN 플래그 조합 비정상 TCP 패킷|alert tcp any any -> 192.168.64.15 any (msg:"SYN+FIN Packet"; flags:SF; sid:2000018;)|
|FIN 플래그만 설정된 비정상 TCP 패킷|alert tcp any any -> 192.168.64.15 any (msg:"Only FIN Packet"; flags:F; sid:2000019;)|
|플래그가 설정되지 않은 비정상 TCP 패킷|alert tcp any any -> 192.168.64.15 any (msg:"no settings flag packet"; flags:!UAPRSF; sid:2000020;)|

# 6.기본 설정(Ubuntu)
### 라이브러리 설치
```
#sudo apt-get install net-tools
#sudo apt install mysql-server
#sudo apt install mysql-client
#sudo apt install libtool
#sudo apt install libpcap-dev
#sudo apt install libdumbnet-dev
#sudo apt install make 
#sudo apt install
```
### Snort 설치
```
#sudo apt-get install snort
```
### MySQL 설정
```
mysql> CREATE DATABASE snoer;
mysql> CREATE USER 'snort'@'localhost' IDENTIFIED BY '1234';
mysql> GRANT ALL PRIVILEGES ON snort.* TO 'snort'@'localhost';
mysql> FLUSH PRIVILEGES;
```
### barnyard2 설정
```
#sudo wget https://github.com/firnsy/barnyard2/archive/master.tar.gz -O barnyard2-master.tar.gz
#tar xvf barnyard2-master.tar.gz
#cd barnyard2-master.tar.gz
#./autogen.sh
#./configure --with-mysql
#make & make install
#cp /root/Desktop/barnyard2-1.9/etc/barnyard2.conf /etc/snort/
#mkdir /var/log/barnyard2
#chmod 666 /var/log/barnyard2
#touch 666 /var/log/barnyard2.waldo
#chown snort.snort /var/log/snort/barnyard2.waldo

schemas/# mysql -p <create_mysql snort
```
### barnyard2.conf DB연동
```
output database: log, mysql, user=snort password=1234 dbname=snort host=localhost
```
### 실행 명령
```
#snort -c /etc/snort/snort.cng
#barnyard2 -c /etc/snort/barnyard2.conf -d /var/log/snort -f snort.log -w /var/log/snort/barnyard2.waldo
```












