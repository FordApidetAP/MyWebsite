CREATE DATABASE testaddress DEFAULT CHARACTER SET utf16 
COLLATE utf16_general_ci;

use testaddress;

CREATE TABLE tbType (
    cTypeid                  INT(10)                  UNSIGNED  NOT NULL AUTO_INCREMENT primary key,
    cTypename                VARCHAR(10)              COLLATE utf16_general_ci  NULL

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbProvince (
   cProvinceid  int(5)  NOT NULL primary key,
   codeProvince varchar(2) COLLATE utf8_unicode_ci  NULL,
   namethProvince varchar(150) COLLATE utf8_unicode_ci  NULL,
   nameenProvince varchar(150) COLLATE utf8_unicode_ci  NULL

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbAmphures (
   cAmphuresid int(5)  NOT NULL primary key,
   codeAmphures varchar(4) COLLATE utf8_unicode_ci  NULL,
   namethAmphures varchar(150) COLLATE utf8_unicode_ci  NULL,
   nameenAmphures varchar(150) COLLATE utf8_unicode_ci  NULL,
   cProvinceid int(5)  NULL,

    constraint FK_District_Province                     foreign key (cProvinceid)                   references tbProvince(cProvinceid)                   

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbDistricts (
  cDistrictsid 				varchar(6) 			NOT NULL primary key,
  zip_code 					int(11)  			NULL,
  namethDistricts 			varchar(150) 		COLLATE utf8_bin  NULL,
  nameenDistricts 			varchar(150) 		COLLATE utf8_bin  NULL,
  cAmphuresid 				int(11)  			NULL,

    constraint FK_Subdistrict_District                  foreign key (cAmphuresid)                   references tbAmphures(cAmphuresid)                  

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbLogin (
    cLoginid                    INT(10)                 UNSIGNED  NOT NULL AUTO_INCREMENT primary key,
    cEmail                      VARCHAR(30)             COLLATE utf16_general_ci  NULL UNIQUE,
    cPassword                   VARCHAR(255)             COLLATE utf16_general_ci  NULL,
    cTypeid                     INT(10)                 UNSIGNED  NULL ,

    constraint FK_Login_Type                    foreign key (cTypeid)                   references tbType(cTypeid) 

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbUser (
    cUserid                     INT(10)                 UNSIGNED  NOT NULL AUTO_INCREMENT primary key,
    cImguser					blob					null,
    cUsername                   VARCHAR(20)             COLLATE utf16_general_ci  NULL UNIQUE,
    cFirstName                  VARCHAR(20)             COLLATE utf16_general_ci NULL,
    cLastName                   VARCHAR(20)             COLLATE utf16_general_ci  NULL,
    cLoginid                    INT(10)                 UNSIGNED  NULL,
    cPhoneNumber                INT(10)                  NULL UNIQUE,
    cAddress                    VARCHAR(255)            COLLATE utf16_general_ci NULL,
    cDistrictsid 				varchar(6) 				 NULL,

    constraint FK_User_Login              foreign key (cLoginid)            references tbLogin(cLoginid)                    ,
    constraint FK_User_Subdistrict        foreign key (cDistrictsid)      references tbDistricts(cDistrictsid)        

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbCategory (
    cCategoryid                     INT(10)                     UNSIGNED  NOT NULL AUTO_INCREMENT primary key,
    cCategoryName                   VARCHAR(20)                 COLLATE utf16_general_ci  NULL

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbPayment (
    cPaymentid                      INT(10)                     UNSIGNED  NOT NULL AUTO_INCREMENT primary key,
    cPaymentName                    VARCHAR(20)                 COLLATE utf16_general_ci  NULL

)ENGINE=InnoDB default charset=utf16;   

CREATE TABLE tbProduct (
    cProductid                      INT(10)                     UNSIGNED  NOT NULL AUTO_INCREMENT primary key,
    file_name                   	VARCHAR(255)                NULL,
	file_path                  		VARCHAR(255)                NULL,
    cProductName                    VARCHAR(20)                 COLLATE utf16_general_ci  NULL,
    cDetails                        VARCHAR(50)                 COLLATE utf16_general_ci  NULL,
    cPrice                          INT(10)                     UNSIGNED  NULL,
	cCategoryid                     INT(10)                     UNSIGNED  NULL ,
    cUserid                     	INT(10)                     UNSIGNED  NULL ,

    constraint FK_Product_Category                     foreign key (cCategoryid)                   references tbCategory(cCategoryid),
	constraint FK_Product_User                     	   foreign key (cUserid)                   	   references tbuser(cUserid)         

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbaddress (
    cAAddressid                     INT(10)                 UNSIGNED  NOT NULL AUTO_INCREMENT primary key,
    cAUserid                        INT(10)                 NULL,
    cAFirstName                  VARCHAR(20)             COLLATE utf16_general_ci NULL,
    cALastName                   VARCHAR(20)             COLLATE utf16_general_ci  NULL,
    cAPhoneNumber                INT(10)                  NULL ,
	cAEmail                      VARCHAR(30)             COLLATE utf16_general_ci  NULL ,
    cAAddress                    VARCHAR(255)            COLLATE utf16_general_ci NULL,
    cDistrictsid 				varchar(6) 				 NULL,

    constraint FK_address_Subdistrict        foreign key (cDistrictsid)      references tbDistricts(cDistrictsid)        

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbOrder (
    cOrderid                        INT(10)                     UNSIGNED NOT NULL AUTO_INCREMENT primary key,
    cUserid                         INT(10)                     UNSIGNED  NULL,
    cOrderDate                      DATETIME                    default current_timestamp,
    cTotalAmount                    INT(10)                     UNSIGNED  NULL,
    cPaymentid                      INT(10)                     UNSIGNED  NULL,
    cAAddressid                     INT(10)                     UNSIGNED NULL,    

    constraint FK_Order_User                     foreign key (cUserid)                   references tbUser(cUserid)                      ,
    constraint FK_order_Payment                  foreign key (cPaymentid)                references tbPayment(cPaymentid),
	constraint FK_order_aaddress                 foreign key (cAAddressid)                references tbaddress(cAAddressid)  

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbOrderdetail (
    cOrderdetailid                 INT(10)                     UNSIGNED NOT NULL AUTO_INCREMENT primary key,
    cProductid                      INT(10)                     UNSIGNED NOT NULL,
    cOrderid                        INT(10)                     UNSIGNED NOT NULL,

    constraint FK_Orderdetail_Product                    foreign key (cProductid)                            references tbProduct(cProductid),
    constraint FK_Orderdetail_cOrderid                    foreign key (cOrderid)                            references tbOrder(cOrderid)       

)ENGINE=InnoDB default charset=utf16;

CREATE TABLE tbcart (
  cCartid 						INT(10)					 UNSIGNED  NOT NULL AUTO_INCREMENT primary key,
  cUserid 						INT(10)					 UNSIGNED  NULL,
  cProductid 					INT(10) 				 UNSIGNED  NULL,
  price 						INT(10)					 UNSIGNED  NULL,
  
   constraint FK_Cart_User                     foreign key (cUserid)                   	  references tbUser(cUserid)                      ,
   constraint FK_Cart_Product                  foreign key (cProductid)                	  references tbProduct(cProductid)                        
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `tbtype`(`cTypename`) VALUES ('Admin');
INSERT INTO `tbtype`(`cTypename`) VALUES ('User');

INSERT INTO `tbcategory`(`cCategoryName`) VALUES ('Nature');
INSERT INTO `tbcategory`(`cCategoryName`) VALUES ('People');
INSERT INTO `tbcategory`(`cCategoryName`) VALUES ('Animals');
INSERT INTO `tbcategory`(`cCategoryName`) VALUES ('Sports');
INSERT INTO `tbcategory`(`cCategoryName`) VALUES ('Travel');

INSERT INTO `tbpayment`(`cPaymentName`) VALUES ('TrueMoney Wallet');
INSERT INTO `tbpayment`(`cPaymentName`) VALUES ('Mobile Banking');
INSERT INTO `tbpayment`(`cPaymentName`) VALUES ('Paypal');
INSERT INTO `tbpayment`(`cPaymentName`) VALUES ('Credit Card');
INSERT INTO `tbpayment`(`cPaymentName`) VALUES ('Debit Card');




INSERT INTO `tbtype` (`cTypeid`, `cTypename`) VALUES
(1, 'Admin'),
(2, 'User');

INSERT INTO `tbcategory` (`cCategoryid`, `cCategoryName`) VALUES
(1, 'Nature'),
(2, 'People'),
(3, 'Animals'),
(4, 'Sports'),
(5, 'Travel');

INSERT INTO `tbpayment` (`cPaymentid`, `cPaymentName`) VALUES
(1, 'TrueMoney Wallet'),
(2, 'Mobile Banking'),
(3, 'Paypal'),
(4, 'Credit Card'),
(5, 'Debit Card');

INSERT INTO tbProvince(`cProvinceid`, `codeProvince`, `namethProvince`, `nameenProvince`) VALUES
(1, '10', 'กรุงเทพมหานคร', 'Bangkok'),
(2, '11', 'สมุทรปราการ', 'Samut Prakan'),
(3, '12', 'นนทบุรี', 'Nonthaburi'),
(4, '13', 'ปทุมธานี', 'Pathum Thani'),
(5, '14', 'พระนครศรีอยุธยา', 'Phra Nakhon Si Ayutthaya');

INSERT INTO tbAmphures (`cAmphuresid`, `codeAmphures`, `namethAmphures`, `nameenAmphures`, `cProvinceid`) VALUES
(1, '1001', 'เขตพระนคร', 'Khet Phra Nakhon', 1),
(2, '1002', 'เขตดุสิต', 'Khet Dusit', 1),
(3, '1003', 'เขตหนองจอก', 'Khet Nong Chok', 1),
(4, '1004', 'เขตบางรัก', 'Khet Bang Rak', 1),
(5, '1005', 'เขตบางเขน', 'Khet Bang Khen', 1);

INSERT INTO tbDistricts (`cDistrictsid`, `zip_code`, `namethDistricts`, `nameenDistricts`, `cAmphuresid`) VALUES
('100101', 10200, 'พระบรมมหาราชวัง', 'Phra Borom Maha Ratchawang', 1),
('100102', 10200, 'วังบูรพาภิรมย์', 'Wang Burapha Phirom', 1),
('100103', 10200, 'วัดราชบพิธ', 'Wat Ratchabophit', 1),
('100104', 10200, 'สำราญราษฎร์', 'Samran Rat', 1);

INSERT INTO `tblogin` (`cLoginid`, `cEmail`, `cPassword`, `cTypeid`) VALUES
(1, 'UserFord@gmail.com', '$2y$10$xzalJo20s2xJxyKVp8F9v.uyFZtkI6vul6kK6ZMIDSkVTfUsP2Zpi', 2),
(2, 'AdminFord@gmail.com', '$2y$10$MdxhLvKCmc.6Hy/r.YYfSOjGiIv1cgIH81jQ4pHxTgc61REOtamkS', 1),
(3, 'Mochi@gmail.com', '$2y$10$aOFXjBEVPBeOK5josSQ2QupoM3Idwj2KDg2fdnWtFOmMeThEn0OIq', 2),
(4, 'capybara@gmail.com', '$2y$10$kUEM.Jr8g1QcpDlNFkN/lOr1Cimtn8wa2RdrFd5xxgNLE2MytFDnW', 2);

INSERT INTO `tbaddress` (`cAAddressid`, `cAUserid`, `cAFirstName`, `cALastName`, `cAPhoneNumber`, `cAEmail`, `cAAddress`, `cDistrictsid`) VALUES
(1, 1, 'Apidet', 'Fungfaung', 622380534, 'test1@gmail.com', '120/140 ถนนติณสูลานนท์', '800809');

INSERT INTO `tbcart` (`cCartid`, `cUserid`, `cProductid`, `price`) VALUES
(19, 1, 7, 430),
(20, 1, 12, 650);

INSERT INTO `tbproduct` (`cProductid`, `file_name`, `file_path`, `cProductName`, `cDetails`, `cPrice`, `cCategoryid`, `cUserid`) VALUES
(2, 'IMG_3669.jpeg', 'upload/IMG_3669.jpeg', 'Mochi', 'ภาพความทรงจํา', 200, 1, 3),
(3, 'IMG_3672.jpeg', 'upload/IMG_3672.jpeg', 'Mochi', 'น้องเมี๊ยว', 430, 1, 3),
(4, 'IMG_3662.jpeg', 'upload/IMG_3662.jpeg', 'Mochi', 'เจ้าโบ้', 340, 1, 3),
(5, 'IMG_3661.jpeg', 'upload/IMG_3661.jpeg', 'Mochi', 'ดอกไม้กับเจ้าเมี๊ยวข้างทาง', 520, 1, 3);