# backend

- 해커톤은 짧은 시간에 서비스를 하나 만드는 것이기 때문에 백엔드로 스크립트 언어 선택
- 스크립트 언어들 중에 PHP 선택
- 디비 커넥션 생성 등의 코드 중복 제거를 위해 가벼운 프레임워크 도입
- 몇분안되서 바로 서버 열고 클라이언트와 상호 협력
- 자바와 달리 컴파일/배포 단계가 없으므로 즉각적인 에러 대응
- 팀원들과 정말 재밌게 즐겼다!ð

### 백엔드 스택   
- Apache   
- PHP   
- CodeIgniter   
- AWS EC2   
- MySQL

### DDL
```sql
create table if not exists member
(
	user_id int auto_increment
		primary key,
	id varchar(100) null,
	pw varchar(100) null,
	nickname varchar(100) null,
	image_url varchar(2083) null
);

create table if not exists post
(
	post_id int auto_increment
		primary key,
	owned_user_id int null,
	post_image_url varchar(2083) null,
	status varchar(30) default 'PENDING' null,
	room_id int null,
	created_date date null,
	up int default 0 null,
	down int default 0 null
);

create table if not exists room
(
	room_id int auto_increment
		primary key,
	title varchar(300) null,
	category varchar(100) null,
	content text null
);

create table if not exists room_p
(
	user_id int null,
	room_id int null,
	point int default 0 null,
	participating tinyint default 1 null
);


```

### 클라이언트
피그마: https://www.figma.com/file/yClhrIK6nkMr4YBxEOOoqD/habit_rabbit_design?node-id=0%3A1   
클라이언트: https://github.com/gdg-offtival-2022-4/Habit-Challenge-Android


### 결과물
https://youtu.be/C4UL-zqBvFU

![endingillust (5)](https://user-images.githubusercontent.com/59721293/176052854-c9f98a2c-fe56-4700-9ba1-7f92752ff9bf.png)
