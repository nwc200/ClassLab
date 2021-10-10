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

/* 2 Classes for Course #1 */
insert into class
values (1, 1, 2, "Wei Cheng", "2021-10-5", "2021-10-20", "10:00:00", "16:00:00", "2021-09-15", "2021-09-30");

insert into class
values (2, 1, 20, "Alexandra", "2021-10-5", "2021-10-20", "19:00:00", "22:00:00", "2021-09-15", "2021-09-30");

/* 1 class for Course #3 */
insert into class
values (3, 3, 20, "Wei Cheng", "2021-10-25", "2021-10-30", "12:00:00", "15:00:00", "2021-09-15", "2021-09-30");

/* 3 class for Course #2 */
insert into class
values (1, 2, 10, "Wei Cheng", "2021-10-17", "2021-10-17", "08:00:00", "17:00:00", "2021-10-10", "2015-10-17");
insert into class
values (2, 2, 10, "Wei Cheng", "2021-10-24", "2021-10-24", "08:00:00", "17:00:00", "2021-10-17", "2015-10-24");
insert into class
values (3, 2, 10, "Alexandra", "2021-10-31", "2021-10-31", "08:00:00", "17:00:00", "2021-10-24", "2015-10-31");


/* 2 class for Course #4 */
insert into class
values (1, 4, 20, "Wei Cheng", "2021-10-17", "2021-10-17", "08:00:00", "17:00:00", "2021-10-10", "2015-10-17");
insert into class
values (2, 4, 15, "Alexandra", "2021-10-24", "2021-10-24", "08:00:00", "17:00:00", "2021-10-17", "2015-10-24");



/* 3 class for Course #5 */
insert into class
values (1, 5, 20, "Wei Cheng", "2021-10-17", "2021-10-17", "08:00:00", "17:00:00", "2021-10-10", "2015-10-17");
insert into class
values (2, 5, 15, "Alexandra", "2021-10-17", "2021-10-17", "08:00:00", "17:00:00", "2021-10-10", "2015-10-17");
insert into class
values (3, 5, 15, "Alexandra", "2021-10-24", "2021-10-24", "08:00:00", "17:00:00", "2021-10-17", "2015-10-24");

/* 4 class for Course #6 */
insert into class
values (1, 6, 20, "Wei Cheng", "2021-10-17", "2021-10-17", "08:00:00", "17:00:00", "2021-10-10", "2015-10-17");
insert into class
values (2, 6, 20, "Alexandra", "2021-10-17", "2021-10-17", "08:00:00", "17:00:00", "2021-10-10", "2015-10-17");
insert into class
values (3, 6, 15, "Wei Cheng", "2021-10-24", "2021-10-24", "08:00:00", "17:00:00", "2021-10-17", "2015-10-24");
insert into class
values (4, 6, 15, "Alexandra", "2021-10-24", "2021-10-24", "08:00:00", "17:00:00", "2021-10-17", "2015-10-24");

