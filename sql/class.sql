drop database if exists class;
create database class;
use class;

CREATE TABLE IF NOT EXISTS `class` (
    `classID` integer auto_increment NOT NULL,
    `courseID` integer NOT NULL,
    `classSize` integer NOT NULL,
    `trainerUserName` varchar(50) NOT NULL,
    `startDate` date NOT NULL,
    `endDate` date NOT NULL,
    `startTime` time NOT NULL,
    `endTime` time NOT NULL,
    `selfEnrollmentStart` date NOT NULL,
    `selfEnrollmentEnd` date NOT NULL,
    constraint class_pk primary key (classID, courseID)
);

/* Adding a foreign key courseID referencing to Course database */
ALTER TABLE class.class
ADD foreign key class_fk(courseID)
REFERENCES course.course(courseID);

/* 1 Class for Course #1 */
insert into class
values (1, 1, 2, "Wei Cheng", "2021-10-05", "2021-10-20", "10:00:00", "16:00:00", "2021-09-15", "2021-11-30");


/* 3 Classes for Course #2 */
insert into class
values (2, 2, 2, "Wei Cheng", "2021-11-30", "2021-11-30", "08:00:00", "17:00:00", "2021-09-15", "2021-11-30");

insert into class
values (3, 2, 2, "Jun Xiang", "2021-11-30", "2021-11-30", "08:00:00", "17:00:00", "2021-09-15", "2021-11-25");

insert into class
values (4, 2, 3, "Alexandra", "2021-10-31", "2021-10-31", "08:00:00", "17:00:00", "2021-09-15", "2021-11-23");


/* 2 class for Course #3 */
insert into class
values (5, 3, 3, "Wei Cheng", "2021-11-30", "2021-11-30", "12:00:00", "15:00:00", "2021-09-15", "2021-11-25");
insert into class
values (6, 3, 3, "Alexandra", "2021-10-05", "2021-10-20", "19:00:00", "22:00:00", "2021-09-15", "2021-11-30");


/* 2 Classes for Course #4 */
insert into class
values (7, 4, 5, "Wei Cheng", "2021-12-05", "2021-12-05", "08:00:00", "17:00:00", "2021-09-15", "2021-12-05");

insert into class
values (8, 4, 3, "Alexandra", "2021-12-15", "2021-12-15", "08:00:00", "17:00:00", "2021-09-15", "2021-12-15");


/* 2 Classes for Course #5 */
insert into class
values (9, 5, 3, "Wei Cheng", "2021-11-30", "2021-11-30", "08:00:00", "17:00:00", "2021-09-15", "2021-11-30");

insert into class
values (10, 5, 5, "Alexandra", "2021-10-17", "2021-10-17", "08:00:00", "17:00:00", "2021-09-15", "2021-12-05");


/* 4 Classes for Course #6 */
insert into class
values (11, 6, 4, "Jun Xiang", "2021-10-31", "2021-10-31", "08:00:00", "17:00:00", "2021-10-24", "2021-11-30");

insert into class
values (12, 6, 3, "Wei Cheng", "2021-12-25", "2021-12-25", "08:00:00", "17:00:00", "2021-10-24", "2021-12-25");

insert into class
values (13, 6, 1, "Alexandra", "2021-11-25", "22021-11-25", "08:00:00", "17:00:00", "2021-10-24", "2021-11-25");

