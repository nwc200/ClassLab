drop database if exists course;
create database course;
use course;

CREATE TABLE IF NOT EXISTS `course` (
    `courseID` integer auto_increment NOT NULL,
    `courseName` varchar(100) NOT NULL,
    `courseDescription` varchar(150) NOT NULL,  
    constraint course_pk primary key (courseID)
);

CREATE TABLE IF NOT EXISTS `coursePrereq` (
    `courseID` integer NOT NULL,
    `coursePrereq` integer NOT NULL, 
    constraint courseprereq_pk primary key (courseID, coursePrereq),
    constraint courseprereq_fk1 foreign key (courseID) references course(courseID),
    constraint courseprereq_fk2 foreign key (coursePrereq) references course(courseID)
);

/* Course */
insert into course
values (1, "Fundamentals of Xerox WorkCentre 7845", "This course aims to teach the functionalities of Xerox WorkCentre 7845.");

insert into course
values (2, "Programming for Xerox WorkCenter with Card Access and Integration", "This course aims to teach the fundamentals of programming for Xerox WorkCentre.");

insert into course
values (3, "Fundamentals of VersaLink C405", "This course aims to teach the functionalities of VersaLink C405.");

insert into course
values (4, "How to set up VersaLink C405", "This course aims to teach the set up of VersaLink C405.");

insert into course
values (5, "Fundamentals of XMPie Personalization Software", "This course aims to teach how to use the XMPie Personalization Software");

insert into course
values (6, "How to troubleshoot XMPie Personalization Software", "This course aims to teach how to troubleshoot the XMPie Personalization Software");


/* Course Prerequisite */
insert into coursePrereq
values (2, 1);

insert into coursePrereq
values (4, 3);

insert into coursePrereq
values (6, 5);



