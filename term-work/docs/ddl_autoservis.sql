create table Typ_Opravy
(
  ID_Typ_opravy int auto_increment
    primary key,
  Nazev_opravy  varchar(48) null,
  Cena          int         null
);

create table Uzivatele
(
  Email       varchar(48)                    not null,
  ID_Uzivatel int auto_increment
    primary key,
  Heslo       varchar(256)                   not null,
  Role        varchar(16) default 'Uzivatel' not null,
  Vytvoreno   datetime                       null
);

create table Auta
(
  ID_Auto      int auto_increment
    primary key,
  Spz          varchar(12) not null,
  Nazev        varchar(48) null,
  ID_Uzivatele int         not null,
  constraint Auto_Spz_uindex
  unique (Spz),
  constraint Auto_Uzivatel_ID_Uzivatel_fk
  foreign key (ID_Uzivatele) references Uzivatele (ID_Uzivatel)
);

create table Predani_auta
(
  ID_Predani_auta int auto_increment
    primary key,
  Komentar        varchar(512)            null,
  Potvrzeno       varchar(6) default 'ne' null,
  Cas_vystaveni   datetime                null,
  Cas_potvrzeni   datetime                null,
  ID_Auta         int                     null,
  constraint Predani_auta_Auta_ID_Auto_fk
  foreign key (ID_Auta) references Auta (ID_Auto)
);

create table Soubor_oprav
(
  ID_Soubor_oprav int auto_increment
    primary key,
  Vytvoreno       datetime                          null,
  ID_Auto         int                               null,
  Stav_Souboru    varchar(48) default 'Nezaplaceno' null,
  constraint Soubor_oprav_Auta_ID_Auto_fk
  foreign key (ID_Auto) references Auta (ID_Auto)
);

create table Oprava
(
  ID_Oprava        int auto_increment
    primary key,
  Skutecna_cena    int         null,
  ID_Souboru_oprav int         null,
  ID_Typ_opravy    int         null,
  Stav             varchar(48) null,
  constraint Oprava_Soubor_oprav_ID_Souborr_oprav_fk
  foreign key (ID_Souboru_oprav) references Soubor_oprav (ID_Soubor_oprav),
  constraint Oprava_Typ_Opravy_ID_Typ_opravy_fk
  foreign key (ID_Typ_opravy) references Typ_Opravy (ID_Typ_opravy)
);


