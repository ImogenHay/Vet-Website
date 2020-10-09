DROP DATABASE vet_db; -- to prevent errors incase database already exists
CREATE DATABASE vet_db; -- creates the sql database that I will use 

USE vet_db; -- selects this database so tables are added to it


DROP TABLE IF EXISTS Company; -- prevents errors if relation already exists in database
CREATE TABLE Company -- creates company relation and defines its attributes/domain
(
    CompanyID INT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE, -- cannot be negative or empty, must be unique
    Name VARCHAR(255) NOT NULL,
    GroomingServices ENUM ('True', 'False') DEFAULT 'False', -- must be either true or false, false by default
    PRIMARY KEY (CompanyID) -- defines primary key of company relation
);

INSERT INTO Company VALUES (111, 'Portland Vets', 'True'); -- adds record/tuple to company relation


DROP TABLE IF EXISTS Building; -- prevents errors if relation already exists in database
CREATE TABLE Building -- creates building relation and defines its attributes/domain
(
    BuildingID INT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    City VARCHAR(255), -- maximum 255 characters
    Postcode VARCHAR(255) NOT NULL,
    Company INT UNSIGNED NOT NULL,
    PRIMARY KEY (BuildingID),
    FOREIGN KEY (Company) REFERENCES Company(CompanyID) -- defines foreign key in relation to link to Company relation
);

INSERT INTO Building VALUES (001, 'London', 'RH19 2NX', 111); -- adds records/tuples to building relation
INSERT INTO Building VALUES (002, 'Bristol', 'BS13 4NZ', 111);


DROP TABLE IF EXISTS Staff; -- prevents errors if relation already exists in database
CREATE TABLE Staff -- creates staff relation and defines its attributes/domain
(
    StaffID INT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    Building INT UNSIGNED NOT NULL,
    PRIMARY KEY (StaffID),
    FOREIGN KEY (Building) REFERENCES Building(BuildingID)
);

INSERT INTO Staff VALUES (1, 'Charlotte Harman', 'chmiah@gmail.com', 001); -- adds records/tuples to staff relation
INSERT INTO Staff VALUES (2, 'Hazel Parker', 'hparker@yahooo.com', 001);
INSERT INTO Staff VALUES (3, 'Marnie Trewern', 'marnie3@yahoo.com', 001);
INSERT INTO Staff VALUES (4, 'Charlie Harrison', 'work@har.cx', 002);
INSERT INTO Staff VALUES (5, 'Ellie Vosper', 'elliev@hotmail.com', 002);
INSERT INTO Staff VALUES (6, 'Alice Gilbert','alice.gilbert@gmail.com', 002);


DROP TABLE IF EXISTS Receptionist; -- prevents errors if relation already exists in database
CREATE TABLE Receptionist -- creates receptionist relation and defines its attributes/domain
(
    ReceptionistID INT UNSIGNED NOT NULL,
    Telephone VARCHAR(255) NOT NULL,
    PRIMARY KEY (ReceptionistID),
    FOREIGN KEY (ReceptionistID) REFERENCES Staff(StaffID) -- foreign key is primary key since subset of staff
);

INSERT INTO Receptionist VALUES (1, '07775 514285'); -- adds records/tuples to receptionist relation
INSERT INTO Receptionist VALUES (6, '07743 345864');


DROP TABLE IF EXISTS Vet; -- prevents errors if relation already exists in database
CREATE TABLE Vet -- creates vet relation and defines its attributes/domain
(
    VetID INT UNSIGNED NOT NULL,
    Speciality VARCHAR(255) NOT NULL,
    PRIMARY KEY (VetID),
    FOREIGN KEY (VetID) REFERENCES Staff(StaffID) -- foreign key is primary key since subset of staff
);

INSERT INTO Vet VALUES (2, 'Rodents'); -- adds records/tuples to vet relation
INSERT INTO Vet VALUES (3, 'Dogs');
INSERT INTO Vet VALUES (4, 'Reptiles');
INSERT INTO Vet VALUES (5, 'Cats');


DROP TABLE IF EXISTS Animal; -- prevents errors if relation already exists in database
CREATE TABLE Animal -- creates animal relation and defines its attributes/domain
(
    AnimalID INT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    Petname VARCHAR(255) NOT NULL,
    Gender ENUM ('F', 'M') NOT NULL,
    DOB DATE,
    PRIMARY KEY (AnimalID)
);

INSERT INTO Animal VALUES (01, 'Muffin', 'F', '2005-05-16'); -- adds records/tuples to animal relation
INSERT INTO Animal VALUES (02, 'Jerry', 'M', '2018-12-01');
INSERT INTO Animal VALUES (03, 'Max', 'M', '2014-10-10');
INSERT INTO Animal VALUES (04, 'Voltaire', 'M', '2010-12-26');
INSERT INTO Animal VALUES (05, 'Portia', 'F', '2005-05-16');
INSERT INTO Animal VALUES (06, 'Widget', 'F', '2020-01-07');


DROP TABLE IF EXISTS Dog; -- prevents errors if relation already exists in database
CREATE TABLE Dog -- creates dog relation and defines its attributes/domain
(
    DogID INT UNSIGNED NOT NULL,
    Breed VARCHAR(255) NOT NULL,
    PRIMARY KEY (DogID),
    FOREIGN KEY (DogID) REFERENCES Animal(AnimalID) -- foreign key is primary key since subset of animals
);

INSERT INTO Dog VALUES (03, 'Labrador'); -- adds records/tuples to dog relation
INSERT INTO Dog VALUES (04, 'French Bulldog');
INSERT INTO Dog VALUES (06, 'Chow Chow');


DROP TABLE IF EXISTS Cat; -- prevents errors if relation already exists in database
CREATE TABLE Cat -- creates cat relation and defines its attributes/domain
(
    CatID INT UNSIGNED NOT NULL,
    Colour VARCHAR(255) NOT NULL,
    PRIMARY KEY (CatID),
    FOREIGN KEY (CatID) REFERENCES Animal(AnimalID) -- foreign key is primary key since subset of animals
);

INSERT INTO Cat VALUES (01, 'Ginger'); -- adds records/tuples to cat relation
INSERT INTO Cat VALUES (05, 'Black');


DROP TABLE IF EXISTS Owner; -- prevents errors if relation already exists in database
CREATE TABLE Owner -- creates owner relation and defines its attributes/domain
(
    OwnerID INT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    PRIMARY KEY (OwnerID)
);

INSERT INTO Owner VALUES (11, 'Antony Sorce', 'antony@sorce.cx'); -- adds records/tuples to owner relation
INSERT INTO Owner VALUES (22, 'Imogen Hay', 'ih002@surrey.ac.uk');
INSERT INTO Owner VALUES (33, 'John Warner', 'jwarner@yahoo.com');
INSERT INTO Owner VALUES (44, 'Katy Tompkins', 'kat.tompkins@gmail.com');
INSERT INTO Owner VALUES (55, 'Dan Hooper', 'drhooper@gmail.com');
INSERT INTO Owner VALUES (66, 'Eliza Rosa', 'elizaroza@hotmail.com');


DROP TABLE IF EXISTS Owner_Animal; -- prevents errors if relation already exists in database
CREATE TABLE Owner_Animal -- creates owner to animal relation and defines its attributes/domain
(
    Owner INT UNSIGNED NOT NULL,
    Animal INT UNSIGNED NOT NULL,
    PRIMARY KEY (Owner, Animal), -- two primary keys since links two relations that have many to many relationship
    FOREIGN KEY (Owner) REFERENCES Owner(OwnerID),
    FOREIGN KEY (Animal) REFERENCES Animal(AnimalID) 
);

INSERT INTO Owner_Animal VALUES (11, 05); -- adds records/tuples to owner to animal relation
INSERT INTO Owner_Animal VALUES (22, 05);
INSERT INTO Owner_Animal VALUES (33, 06);
INSERT INTO Owner_Animal VALUES (44, 01);
INSERT INTO Owner_Animal VALUES (44, 04);
INSERT INTO Owner_Animal VALUES (55, 03);
INSERT INTO Owner_Animal VALUES (66, 02);


DROP TABLE IF EXISTS Appointment; -- prevents errors if relation already exists in database
CREATE TABLE Appointment -- creates appointment relation and defines its attributes/domain
(
    AppointmentID INT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    Room INT NOT NULL,
    Time TIME NOT NULL,
    Vet INT UNSIGNED NOT NULL,
    Animal INT UNSIGNED NOT NULL,
    PRIMARY KEY (AppointmentID),
    FOREIGN KEY (Vet) REFERENCES Vet(VetID),
    FOREIGN KEY (Animal) REFERENCES Animal(AnimalID)
);

INSERT INTO Appointment VALUES (1111, 01, '12:00:00', 2, 06); -- adds records/tuples to appointment relation
INSERT INTO Appointment VALUES (2222, 03, '10:20:00', 5, 02);
INSERT INTO Appointment VALUES (3333, 01, '11:45:00', 2, 01);
INSERT INTO Appointment VALUES (4444, 02, '14:00:00', 3, 03);