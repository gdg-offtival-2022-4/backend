# gdgbackend

### 백엔드 스택   
PHP   
CodeIgniter   
AWS EC2   
MySQL

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

![endingillust](https://user-images.githubusercontent.com/59721293/175798059-ed789250-a0a7-4a8a-bf0c-e91374cda37a.png)
