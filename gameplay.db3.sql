CREATE TABLE IF NOT EXISTS `Running` (
	`RmNo`	INTEGER,
	`g-seer`	INTEGER,
	`g-witch`	INTEGER,
	`g-hunter`	INTEGER,
	`g-idiot`	INTEGER,
	`g-knight`	INTEGER,
	`g-guard`	INTEGER,
	`g-deramtaker`	INTEGER,
	`g-magitcian`	INTEGER,
	`w-white`	INTEGER,
	`w-hidden`	INTEGER,
	`w-king`	INTEGER,
	`w-devil`	INTEGER,
	`w-beauty`	INTEGER,
	`w-knight`	INTEGER,
	`w`	INTEGER,
	`death1`	INTEGER,
	`death2`	INTEGER,
	`death3`	INTEGER,
	`start`	INTEGER
);
CREATE TABLE IF NOT EXISTS `Game` (
	`RmNo`	INTEGER NOT NULL,
	`Role`	VARCHAR(25),
	`Werewolf`	INTEGER NOT NULL,
	`Folk`	NUMERIC NOT NULL,
	`PlayerNo`	INTEGER NOT NULL,
	`Creator`	VARCHAR(25) NOT NULL,
	`Round`	VARCHAR(25) NOT NULL,
	`Voting`	INTEGER NOT NULL,
	`Judge`	VARCHAR(25) NOT NULL,
	PRIMARY KEY(`RmNo`)
);
CREATE TABLE IF NOT EXISTS `Player` (
	`Username`	VARCHAR(25),
	`Name`	VARCHAR(25) NOT NULL,
	`RmNo`	INTEGER NOT NULL,
	`Role`	VARCHAR(25) NOT NULL,
	`No`	INTEGER NOT NULL
);
CREATE TABLE IF NOT EXISTS `User` (
	`ID`	INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT UNIQUE,
	`Username`	VARCHAR(25) NOT NULL,
	`Name`	VARCHAR(25) NOT NULL
);
INSERT INTO `Running` VALUES (7,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0);
INSERT INTO `Running` VALUES (26,2,-4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,-4,0,1,0,0);
INSERT INTO `Game` VALUES (7,'g-seer;g-witch',1,1,4,'yse00','0',0,'yse00');
INSERT INTO `Game` VALUES (26,'g-seer;g-witch',2,2,6,'78','0',0,'78');
INSERT INTO `Player` VALUES ('yse00','',7,'2',1);
INSERT INTO `Player` VALUES ('ys','',7,'3',2);
INSERT INTO `Player` VALUES ('dd','',7,'4',3);
INSERT INTO `Player` VALUES ('18','',7,'1',4);
INSERT INTO `Player` VALUES ('78','',26,'5',1);
INSERT INTO `Player` VALUES ('rrr','',26,'2',2);
INSERT INTO `Player` VALUES ('sdff','',26,'1',3);
INSERT INTO `Player` VALUES ('5tg','',26,'4',4);
INSERT INTO `Player` VALUES ('rtx','',26,'6',5);
INSERT INTO `Player` VALUES ('888','',26,'3',6);
INSERT INTO `User` VALUES (1,'','');