/*CREATE DATABASE bd_lectum;
use bd_lectum;
*/
CREATE TABLE generos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100)
);

INSERT INTO generos (nombre) VALUES
('ficcion'),
('ciencia-ficcion'),
('fantasia'),
('romance'),
('misterio'),
('no-ficcion'),
('biografia'),
('historia');

CREATE TABLE libros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255),
  autor VARCHAR(255),
  genero_id INT,
  valoracion DECIMAL(2,1),
  lecturas INT,
  anio INT,
  paginas INT,
  portada VARCHAR(100),
  descripcion TEXT,
  FOREIGN KEY (genero_id) REFERENCES generos(id)
);

INSERT INTO libros VALUES
(1,'Cien años de soledad','Gabriel García Márquez',1,4.8,15420,1967,471,'imagen1.jpg','La obra maestra del realismo mágico que narra la historia de la familia Buendía en Macondo.'),
(2,'1984','George Orwell',2,4.7,12350,1949,328,'imagen2.jpg','Una distopía totalitaria que explora el totalitarismo y la vigilancia masiva.'),
(3,'El nombre del viento','Patrick Rothfuss',3,4.6,9870,2007,662,'imagen3.jpg','La historia de Kvothe, un legendario músico y mago.'),
(4,'Orgullo y prejuicio','Jane Austen',4,4.5,11200,1813,432,'imagen4.jpg','Una historia de amor y crítica social en la Inglaterra del siglo XIX.'),
(5,'El código Da Vinci','Dan Brown',5,4.2,18900,2003,454,'imagen5.jpg','Un thriller que mezcla arte, historia y conspiraciones religiosas.'),
(6,'Sapiens','Yuval Noah Harari',6,4.6,14500,2011,443,'imagen6.jpg','Una breve historia de la humanidad desde sus orígenes.'),
(7,'La sombra del viento','Carlos Ruiz Zafón',5,4.4,10800,2001,576,'imagen7.jpg','Un joven descubre un libro misterioso en el Cementerio de los Libros Olvidados.'),
(8,'Harry Potter y la piedra filosofal','J.K. Rowling',3,4.7,25000,1997,309,'imagen8.jpg','El inicio de la saga del joven mago más famoso del mundo.'),
(9,'El principito','Antoine de Saint-Exupéry',1,4.8,20100,1943,96,'imagen9.jpg','Un cuento filosófico sobre la amistad y el amor.'),
(10,'Don Quijote de la Mancha','Miguel de Cervantes',1,4.5,8900,1605,1056,'imagen10.jpg','Las aventuras del ingenioso hidalgo y su fiel escudero.'),
(11,'Steve Jobs','Walter Isaacson',7,4.3,7650,2011,656,'imagen11.jpg','La biografía autorizada del fundador de Apple.'),
(12,'Los pilares de la Tierra','Ken Follett',8,4.4,9200,1989,1008,'imagen12.jpg','Una épica sobre la construcción de una catedral en la Inglaterra medieval.'),
(13,'Dune','Frank Herbert',2,4.5,11000,1965,688,'imagen13.jpg','Una saga épica de política, religión y ecología en un planeta desértico.'),
(14,'Crimen y castigo','Fiódor Dostoyevski',1,4.4,7800,1866,671,'imagen14.jpg','Un joven estudiante comete un crimen y enfrenta las consecuencias morales.'),
(15,'El alquimista','Paulo Coelho',1,4.2,16500,1988,208,'imagen15.jpg','Un pastor andaluz viaja en busca de un tesoro y descubre su leyenda personal.');

CREATE TABLE tienda (
  id INT AUTO_INCREMENT PRIMARY KEY,	
  nombre VARCHAR(50),
  icono VARCHAR(100),
  estrellas double,
  envio VARCHAR(50)
);

INSERT INTO tienda (nombre, icono, estrellas, envio) VALUES
('Amazon', 'iconoAmazon.jpg',5,'Envío gratis con Prime'),
('Casa del Libro', 'iconoCasa.jpg',5,'Envío gratis Envío gratis +19€'),
('El Corte Inglés', 'iconoCorte.jpg',5,'Envío gratis Envío gratis +50€'),
('Fnac', 'iconoFac.jpg',4,'Envío gratis Envío gratis +29€'),
('Iberlibro', 'iconoIber.jpg',4,'Variable por vendedor');

CREATE TABLE precios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  libro_id INT,
  tienda_id INT,
  precio DECIMAL(6,2),
  url TEXT,
  FOREIGN KEY (libro_id) REFERENCES libros(id),
  FOREIGN KEY (tienda_id) REFERENCES tienda(id)
);

INSERT INTO precios (libro_id, tienda_id, precio, url) VALUES
(1,1,12.30,'https://www.amazon.es/soledad-CONTEMPORANEA-Gabriel-Garcia-Marquez/dp/8497592204/ref=sr_1_2?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=1K38LBY21245L&dib=eyJ2IjoiMSJ9.AmrjDuOtIVX5tiEL9AF8sSJPG0vo2ywk7Ag5kQ9SwjbHk4dRu1T65vl_-Ua1wAy2vE3MG6HFnkQz_2jQR7m4GBsnIwy5PolVpKlwuSEppGtLs_2boneFcpI0rxKGahqt7ODWBFheVDT8dorPvzeO42lN6TDN1ad0WdP7CCsY1HmGs2ucYwAIHcM4wRvY6IqFP5g4b3cMzy61j5AFDLpRwCe-D51rv3uzU4aNHeUC7V0nMFDt1KG_G2qEVpEhqhTQIULlxHIy32qdtUbbhESBuhJjVj9SPOIGjDAMoaQmWAg.zccsZ996G-qCdcfQwPQUEpT_TkKm6eBSHEiNlyY8Iuo&dib_tag=se&keywords=Cien+a%C3%B1os+de+soledad&qid=1775572369&sprefix=cien+a%C3%B1os+de+soledad%2Caps%2C156&sr=8-2'),
(1,2,12.30,'https://www.casadellibro.com/libro-cien-anos-de-soledad/9788497592208/873717'),
(1,3,12.95,'https://www.fnac.es/SearchResult/ResultList.aspx?Search=Cien+a%C3%B1os+de+soledad&sft=1&sa=0'),
(1,4,23.85,'https://www.iberlibro.com/Cien-a%C3%B1os-soledad-Gabriel-Garc%C3%ADa-M%C3%A1rquez/32405669525/bd'),
(1,5,12.85,'https://www.elcorteingles.es/libros/A1824959-cien-anos-de-soledad-bolsillo-tapa-blanda/?stype=text_box&parentCategoryId=999.54302013&color=Sin+especificar'),
(2,1,14.55,'https://www.amazon.es/1984-George-Orwell-English-Illustrated/dp/1917067100/ref=sr_1_2_sspa?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=2XJWNKA6BBNEF&dib=eyJ2IjoiMSJ9.G_WinnGDxHcnQxui7bmPgvmVAPRIyFUfJiMOo7xVWghBV1h2lwYL1V0vpawQeIlgNoybl9_88axX9wdhgAj1DYkfkVCQprvZ5t5DGgYTUFt23NIEoVzAMOmvW5fg0-JQ_V10V5R88Cw0EiZ__cRHFBGQBdOwJI0DW33VaiqogS8c7VLSEIShpFhFPLBCswrPt4kmhoBwlPA6Q8daQRct2UzH0hkKriW8AdSHVrXS61pLaLnTYFoVA7Ubqvms0x2DZdGQ85Qj'),
(2,2,9.50,'https://www.casadellibro.com/libro-1984/9791387788452/18051357'),
(2,3,10.40,'https://www.fnac.es/SearchResult/ResultList.aspx?Search=1984&sft=1&sa=0'),
(2,4,9.99,'https://www.iberlibro.com/1984-GEORGE-ORWELL-EDAF/32251783184/bd'),
(2,5,15.15,'https://www.elcorteingles.es/libros/A56076575-1984-edicion-definitiva-avalada-por-the-tapa-blanda-tapa-dura/?stype=text_box&color=Sin+especificar'),
(3,1,14.20,'https://www.amazon.es/nombre-viento-Cr%C3%B3nica-asesino-Seller/dp/8466354026/ref=sr_1_1?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=1G6HCGLQTVE1K&dib=eyJ2IjoiMSJ9.aaGsJhzxgsa0mjjR4N4osDjn2rod74SGLwl-L8yxcX6hs0ysbHsEu85mwyfSjtAw2J_oDA1wJqdODXKEQCVo7ZZLg06qqR3coii5FxemQLQP15lZef6W2QJ5xeGDMITyDwPZxz42AjddTUSFIluM90Qq45EzZsLH1DNvoLiDY8A2UBNPXGUtO_uKQ0wmeDp5q_3mC07-sj7pr7gWKp628DutNZKuBfDRRDyLLYISFzWrgfWZRvQCyzq_jU8GNR72GCHWPNdA_bi2FZ5TuUC9OPm0DloRrp6O3Oamn9Cw9aI.7DM7TGsppzD6hOeWUP5osVEyLN'),
(3,2,14.20,'https://www.casadellibro.com/libro-el-nombre-del-viento-cronica-del-asesino-de-reyes-1/9788466354028/14452347'),
(3,3,14.20,'https://www.fnac.es/a10621723/Gemma-Rovira-Ortega-El-nombre-del-viento-Cronica-del-asesino-de-reyes-1'),
(3,4,8.34,'https://www.iberlibro.com/nombre-viento-Cr%C3%83-nica-asesino-reyes/32167389078/bd'),
(3,5,14.20,'https://www.elcorteingles.es/libros/A49900612-el-nombre-del-viento-cronica-del-asesino-de-reyes-1-tapa-dura/?stype=text_box&color=Sin+especificar'),
(4,1,13.95,'https://www.amazon.es/Pride-Prejudice-Orgullo-Prejuicio-English-Spanish/dp/B09KNCWS45/ref=sr_1_1_sspa?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=3RCRU07O0H7SO&dib=eyJ2IjoiMSJ9.JHzIpwFGSZqRsuCoqbHqWcjVLdUELgucx_gQBwOmDH0oRk9J1enjT60L6Szx-vcDM0eWsEAqd-TuHNFuhHCzwUbsN16q7RxLNvCiBFQx_iT8-IbIwfCLNub9GZRc2NyyiCPKRu00VroNimOztBU'),
(4,2,9.50,'https://www.casadellibro.com/libro-orgullo-y-prejuicio/9791387575755/17632610'),
(4,3,14.20,'https://www.fnac.es/a11832466/Jane-Austen-Orgullo-y-prejuicio'),
(4,4,9.99,'https://www.iberlibro.com/ORGULLO-PREJUICIO-VINTAGE-AUSTEN-JANE-NEWTON/32357765642/bd'),
(4,5,9.50,'https://www.elcorteingles.es/libros/A57394697-orgullo-y-prejuicio-tapa-dura/?stype=text_box&color=Sin+especificar'),
(5,1,20.88,'https://www.amazon.es/El-c%C3%B3digo-Vinci-Volumen-independiente-ebook/dp/B009SEGZ76/ref=sr_1_1?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=G50IL5FVKP42&dib=eyJ2IjoiMSJ9.Y81_R-W-sSWjzm-9mQeTyJo9h91c6oGq_9UTPI-1_LPj4fD3VE15iYFRtlWxThecKpK8AB-cULAmdOUsmdFfEMoiC72XsQllvDk_GCcCInoEF5lGG39-1laoY75esjo3yeD6oGH0MQY__08nfNOeyRvDnu0AHKrCAdxrFlr6Bts-jJV5BaVt7SqIJMs'),
(5,2,16.10,'https://www.casadellibro.com/libro-el-codigo-da-vinci-edicion-especial-con-cantos-decorados/9788408307938/17418392'),
(5,3,16.10,'https://www.fnac.es/a12282268/Dan-Brown-El-codigo-Da-Vinci-Edicion-especial-con-cantos-decorados'),
(5,4,14.94,'https://www.iberlibro.com/servlet/SearchResults?kn=El%20c%F3digo%20Da%20Vinci&ref_=ds_ac_d_18&sts=t'),
(5,5,14.20,'https://www.elcorteingles.es/libros/A55605434-el-codigo-da-vinci-serie-robert-langdon-bolsillo/?stype=text_box&color=Sin+especificar'),
(6,1,19.85,'https://www.amazon.es/Sapiens-animales-dioses-historia-humanidad/dp/8499926223/ref=sr_1_1?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=3O0TUICCV8UKY&dib=eyJ2IjoiMSJ9.UbLEjmBKHl-1-6Lbsm0_eigOYQQIiAA-VbjoF0-4RQxedbnTsKqODgiQaqnYtoWL_IqJhFBg_KZaWlCsqY1UJ_cNabO0etTib3tHOzvd2pTewjSix8NxH5tIl9QR0dTIJnhraDtUsLwlGFdwGlfI4zFQP8LzoYbLzcoI_ZG8rk3Ww0yyJJ1KwfrjXUl1ScCyyiF-kP1knlTWo7NBvEWQajOu_a-1m_wQGT29-mzt5v8.TvVhmq38O3HSQuGs5bZag30CMNA2H3lhXte6DI9cvww&dib_tag=se&keywords=Sapiens&qid=1775575232&s=books&sprefix=sapiens%2Cstripbooks%2C193&sr=1-1'),
(6,2,19.85,'https://www.casadellibro.com/libro-sapiens-de-animales-a-dioses/9788499926223/2655217'),
(6,3,19.85,'https://www.fnac.es/a1171727/Yuval-Noah-Harari-Sapiens-De-animales-a-dioses'),
(6,4,13.77,'https://www.iberlibro.com/S%C3%A0piens-edici%C3%B3-R%C3%BAstica-Breu-Hist%C3%B2ria-Humanitat/32401833427/bd'),
(6,5,14.20,'https://www.elcorteingles.es/libros/A16812126-sapiens-de-animales-a-dioses-breve-historia-de-la-humanidad-tapa-blanda/?stype=text_box&color=Sin+especificar'),
(7,1,18.90,'https://www.amazon.es/Sombra-Viento-Cementerio-Libros-Olvidados/dp/8408092642/ref=tmm_pap_swatch_0?_encoding=UTF8&dib_tag=se&dib=eyJ2IjoiMSJ9.MJEZi5lHUOLJGbFhwVcs8w8sz89aVpKOZq22WmKEzYujS8WSQVWYEIRZEBZiwnRt5Vhe9YluAInqlu2ePLOTJcTSjU9J0EUyAoaWBUbyLG2eFQvww0E1fR10bgkQjraoY8VKHrKgZD_dDU-bgtSKGcWFWVwUR3oxj9guT76i19wvnXOjAvcqt23qDJXVrP8tVWg0rRV5g9CEVqhj2FYzos82Vr4CoXb5nOgnvb0_wDA.nPV8xXzZ9oqHJb0MW6y0WkxIVn3uuDGHDkqj6T2TTfA&qid=1775575679&sr=1-1'),
(7,2,21.75,'https://www.casadellibro.com/libro-la-sombra-del-viento-serie-el-cementerio-de-los-libros-olvidados-1/9788408163350/3104109'),
(7,3,12.30,'https://www.fnac.es/a1289066/Carlos-Ruiz-Zafon-La-Sombra-del-Viento'),
(7,4,14.94,'https://www.iberlibro.com/Sombra-Viento-Carlos-Ruiz-Zaf%C3%B3n-Booket/32182610197/bd'),
(7,5,12.30,'https://www.elcorteingles.es/libros/A20373100-la-sombra-del-viento-el-cementerio-de-los-libros-olvidados-1-bolsillo-tapa-blanda/?stype=text_box&color=Sin+especificar'),
(8,1,14.20,'https://www.amazon.es/Harry-Potter-Piedra-Filosofal-Colecci%C3%B3n/dp/8498386314/ref=tmm_pap_swatch_0?_encoding=UTF8&dib_tag=se&dib=eyJ2IjoiMSJ9.QDR1YS_t7iPTUKAp74S1EgoXRDwNy_5cwBvInR5SkgXen715exWBw30StymGQekwedHCtgatNoIac8_TO08_cm4zoI2VAB9anvNI1GuiZH_mxs27z0AXfh3kI37LsEeXHzxBinZtpJ-Qp0Fad9GXX0E87wYmRK_2s1UEfjdlfI02AFpyarCxbCp0B7LJFcwzsqD7-MaZ4R6wdk1hEbdKvmtRw-0VRg4i3in8HpBKsBE.CnxW1_AL4MSysBSOZSDo0OzZdDPhGPrkXtDYxBgZT8I&qid=1775575891&sr=1-2-spons'),
(8,2,16.10,'https://www.casadellibro.com/libro-harry-potter-y-la-piedra-filosofal/9788478884452/644736'),
(8,3,12.30,'https://www.fnac.es/a384266/Harry-Potter-Harry-Potter-y-la-piedra-filosofal-Harry-Potter-1-J-K-Rowling'),
(8,4,5.00,'https://www.iberlibro.com/Harry-Potter-piedra-filosofal-J.K.Rowling-Salamandra/32420554540/bd'),
(8,5,16.10,'https://www.elcorteingles.es/libros/A1870800-harry-potter-y-la-piedra-filosofal-harry-potter-edicion-con-la-portada-ilustrada-por-dolores-avendano-1-tapa-dura/?stype=text_box&color=Sin+especificar'),
(9,1,6.60,'https://www.amazon.es/Principito-edici%C3%B3n-original-acuarelas-autor/dp/8498381495/ref=sr_1_4?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=1DCRZNGSRAF7F&dib=eyJ2IjoiMSJ9.y5IpI8O36-EXiBxeSuHvLp6UNNOg_UuG8xX_ilx9jipL5i_F3pjZVKrzRJQf77E-OvkfYDNBn_g0NpUdkUYjrumtC2PW0g86OTdDoVce6Z7nAHs4OHRPemMuo3y-xqnKhWUk3DOh_rtsEoclnZvSZ6nSRfK20hXt8wjCLFWpd2plt94gZkuqiVa3bmMwX1wo1UEZfwNW1fZTR0YFf1iak15ysO5Z_sXkTn679yp97SM.5J6vUE0zIaKgAaomn9nFuJY1n61TDNg98XaDOSoQP10&dib_tag=se&keywords=El+principito&qid=1775576165&s=books&sprefix=el+principito%2Cstripbooks%2C261&sr=1-4'),
(9,2,5.65,'https://www.casadellibro.com/libro-el-principito/9788408308201/17086415'),
(9,3,6.60,'https://www.fnac.es/a298205/Antoine-de-Saint-Exupery-El-Principito-Edicion-Oficial'),
(9,4,10.95,'https://www.iberlibro.com/PRINCIPITO-EDICION-CONMEMORATIVA-ANTOINE-SAINT-EXUPERY-PENGUIN/32320570678/bd'),
(9,5,3.70,'https://www.elcorteingles.es/libros/A54274989-el-principito-tapa-blanda/?stype=text_box&color=Sin+especificar'),
(10,1,20.80,'https://www.amazon.es/Quijote-Mancha-Edici%C3%B3n-conmemorativa-ASALE/dp/8420412147/ref=sr_1_2_sspa?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=2HZ0H78KN699G&dib=eyJ2IjoiMSJ9.Qrx6Rcagus2ehngdXgSJtAjK3zMwRzLPMoR_hsMpXB1hTy1zzSMVh28aK6YFlaG6Iu6O-HIPnNw6mS1HzdrNsjKdduhTOmn-2b7T4D1is9A9KS9XYw6Ul4b83jjso33YnNZgG5tenPvSOCK77OZHEN96FYBzUtdlc5iqqbYMyL5YLRSK10qFDtiPmgAk-UZPvHh6L0L5IzZQ4Q_a0hwChS8vyOXNZSRFDiCSKJU_3bY.T6EcCMhK83VCqXOe9G0O46W9TGZ7UeQKuDZ-w2AQByg&dib_tag=se&keywords=Don+Quijote+de+la+Mancha&qid=1775576589&s=books&sprefix=don+quijote+de+la+mancha%2Cstripbooks%2C153&sr=1-2-spons&aref=QvwbwaHtoS&sp_csd=d2lkZ2V0TmFtZT1zcF9hdGY&psc=1'),
(10,2,20.80,'https://www.casadellibro.com/libro-don-quijote-de-la-mancha-edicion-conmemorativa-iv-centenario/9788420412146/2593016'),
(10,3,20.81,'https://www.fnac.es/a1133784/Miguel-de-Cervantes-Don-Quijote-De-La-Mancha-Edicion-Conmemorativa-De-La-Rae-Y-La-Asale'),
(10,4,10.95,'https://www.iberlibro.com/Don-Quijote-Mancha-Andr%C3%A9s-Trapiello-Austral/32132200748/bd'),
(10,5,11.35,'https://www.elcorteingles.es/libros/A22067353-don-quijote-de-la-mancha-tapa-dura/?stype=text_box&color=Sin+especificar'),
(11,1,14.20,'https://www.amazon.es/Steve-Jobs-SELLER-Walter-Isaacson/dp/8499897312/ref=sr_1_1?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=D9Z8WUBO2LKY&dib=eyJ2IjoiMSJ9.6aeZM59KJdd_oJTQegIC4_ZOVWSVHbLZqRBDkbGhwLBMBEtjEer52cNlSwzGRfskRj11X0fNsvY6dwvi_Ap5oO7ofsR2cLLrXMZDoMnh03DoTj_Cfa_Az10ZhyJJ7b_A0l_Snd4yhOwRSSuuQHzS7PzPlNFqBIAPBnfQM-cxBf2hQ8hpvOZay1qHm8nsU_Y3KsXKhb-MwN7qAHC-_Q6AbvZPfcIzvp1YLcE2ai2-_Vs.UOMgyxVFI6TNSZqqZTt4bVAJ_I9DumN7j4kq6RTjTU0&dib_tag=se&keywords=Steve+Jobs&qid=1775576841&s=books&sprefix=steve+jobs%2Cstripbooks%2C200&sr=1-1'),
(11,2,24.60,'https://www.casadellibro.com/libro-steve-jobs-la-biografia/9788499921846/2000238'),
(11,3,14.20,'https://www.fnac.es/a851571/Walter-Isaacson-Steve-Jobs'),
(11,4,7.19,'https://www.iberlibro.com/Steve-Jobs-Walter-Isaacson-Abacus/32279814078/bd'),
(11,5,14.20,'https://www.elcorteingles.es/libros/A8001316-steve-jobs-bolsillo-tapa-blanda/?stype=text_box&color=Sin+especificar'),
(12,1,24.60,'https://www.amazon.es/Los-pilares-Tierra-Saga-EXITOS/dp/8401328519/ref=sr_1_1?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=24VA5V8JS7ZE4&dib=eyJ2IjoiMSJ9.5d352iE6GFr2t_iL8haFnY0wUoB2wfbRclRFURM1TNyoWZVJb4MXe48ew8Zz2ViNb1X3TrA2fTauDshSSYxZdxxfYNGykKuPvv1XUP6R9yGGKVtvkX0EVz8NVOh7NBVsBZz4QcVYDoDtEcoMElHPQdHotl7S6mhW9VVAC1Yzr8lhPGY-Y8LDQ1EgbfAIzfIxI_a6T0DwUyMhPsQac6HbetNTa7Ssq3E94ehMCUxQPMM.5iShZV8JkxP2ZxhLI_hFwhTO54gSZSivR-ImjyIm3VE&dib_tag=se&keywords=Los+pilares+de+la+Tierra&qid=1775580705&s=books&sprefix=los+pilares+de+la+tierra%2Cstripbooks%2C221&sr=1-1'),
(12,2,24.60,'https://www.casadellibro.com/libro-los-pilares-de-la-tierra/9788401328510/747441'),
(12,3,14.20,'https://www.fnac.es/a398003/Ken-Follett-Los-pilares-de-la-Tierra-Saga-Los-pilares-de-la-Tierra-1'),
(12,4,9.51,'https://www.iberlibro.com/pilares-tierra-Ken-Follett-Debolsillo/32376615398/bd'),
(12,5,14.20,'https://www.elcorteingles.es/libros/A1841270-los-pilares-de-la-tierra-saga-los-pilares-de-la-tierra-1-bolsillo-tapa-blanda/?stype=text_box&color=Sin+especificar'),
(13,1,9.83,'https://www.amazon.es/Dune-Remembering-Tomorrow-Frank-Herbert/dp/0441172717/ref=sr_1_3?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=1UFRYKSNSS2LR&dib=eyJ2IjoiMSJ9.OTAMpyJh0YhTMS3K-E0PqQARXFnciFJj3XUo_QGjC025UfC-odyZPqAhgXWsziEydFvOT3fxamqXxBMoppqm1nH6qVnUQnlTlQ2nNgOR_bc587uq1oI-izBV3ZcJpeRKsm1EJpdmZgzu5aBXDVQFAStB-O3TK2kPGEtUUk_4oUXR-o0R-WsNKc7fN7_hrPvuN5RMe72paACLhrR9XE5QyPTEODPBPE9BNXnCFGRkVCo.bAEttoya80fN3Y7N3IVEPqx_GsQ4MVOj93HS2fP-0v8&dib_tag=se&keywords=Dune&qid=1775580986&s=books&sprefix=dune%2Cstripbooks%2C135&sr=1-3'),
(13,2,14.21,'https://www.casadellibro.com/libro-dune-nueva-edicion-las-cronicas-de-dune-1/9788466353779/11543708'),
(13,3,14.20,'https://www.fnac.es/a7450764/Frank-Herbert-Dune-Nueva-edicion-Las-cronicas-de-Dune-1'),
(13,4,9.90,'https://www.iberlibro.com/Dune-Herbert-Frank-Debolsillo-Barcelona/32375707770/bd'),
(13,5,16.10,'https://www.elcorteingles.es/libros/A48816322-dune-las-cronicas-de-dune-1-bolsillo-tapa-dura/?stype=text_box&color=Sin+especificar'),
(14,1,12.30,'https://www.amazon.es/Crimen-y-castigo-PENGUIN-CL%C3%81SICOS/dp/849105006X/ref=sr_1_3?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=WCZ3OF0KUY16&dib=eyJ2IjoiMSJ9.OcjcvtE_br5P7OrIfdwSUKkaf3DYpYfTxBNE4oDyNe0ZsRuYrQ7LMc-OUxWdCAbLPoCu8gtWJwW9bAT0J-7vPvncAxyPJbQFIehz1NDTLeiaxwa66sPeCXkVvwiG8BGvPvNIiVgp1-lzmdiv_HHV1s0repENeUUB1CahW4q5Qws6QViC4P5nr-7dF7vLnNWBmNwmVxuWgYiOtJe2YVK16EOmXhyIvM6XMYU_Sjt3oCs.v15uxJA5g09A3kgwtXyHvVq29Tb9dD0yRCzn_cTfJ1c&dib_tag=se&keywords=Crimen+y+castigo&qid=1775581220&s=books&sprefix=crimen+y+castigo%2Cstripbooks%2C151&sr=1-3'),
(14,2,12.30,'https://www.casadellibro.com/libro-crimen-y-castigo/9788491050063/2549214'),
(14,3,12.30,'https://www.fnac.es/a1126312/Fiodor-Dostoyevski-Crimen-y-castigo'),
(14,4,11.03,'https://www.iberlibro.com/CRIMEN-CASTIGO-DOSTOIEVSKI-FIODOR-EDIMAT-EDITORIAL/32154948574/bd'),
(14,5,15.15,'https://www.elcorteingles.es/libros/A56888802-crimen-y-castigo-edicion-especial-en-tapa-dura-tapa-dura/?stype=text_box&color=Sin+especificar'),
(15,1,10.40,'https://www.amazon.es/Alquimista-Biblioteca-Bolsillo-Paulo-Coelho/dp/8408253107/ref=sr_1_1?__mk_es_ES=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=286HLS47QKAK7&dib=eyJ2IjoiMSJ9.ec014WwlXJYpPkOiWoN0cTRfO-u7padZ866uWl_tQeMleLaZloLU1PFAK5jDR_GisvXpcWkkPTcuCy9uqdqaxxmy2-dGdFpUpSV127JnJoLAjCDacFoWyVk93VdaYLl07bL36gzx1ATIBFVWxIvEqyA7zBy_V7Q0pUy7Br5q0Dx5dkUN7ItfIu7jUH_0rn6J38D07wlW7wXYqdiGPBFz6aL4_dEwfmPlQEIpp41J-Wk.zaLUChLmvYGsSoMGDn9Hp0nVWMhnmFwL1_82VKLe3tE&dib_tag=se&keywords=El+alquimista&qid=1775581415&s=books&sprefix=el+alquimista%2Cstripbooks%2C203&sr=1-1'),
(15,2,10.40,'https://www.casadellibro.com/libro-el-alquimista/9788408253105/12657256'),
(15,3,10.40,'https://www.fnac.es/a8866472/Paulo-Coelho-El-Alquimista'),
(15,4,8.94,'https://www.iberlibro.com/Alquimista-Paulo-Coelho-Booket/32215977407/bd'),
(15,5,10.40,'https://www.elcorteingles.es/libros/A56176387-lalquimista-bolsillo/?stype=text_box&color=Sin+especificar')
;
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50),
  usuario VARCHAR(50),
  email VARCHAR(100),
  password VARCHAR(100),
  fecha_registro datetime NOT NULL DEFAULT current_timestamp()
);

CREATE TABLE resenias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  libro_id INT,
  usuario_id INT,
  valoracion INT,
  texto TEXT,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (libro_id) REFERENCES libros(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
/* pruebas
INSERT INTO resenias (libro_id, usuario_id, valoracion, texto, fecha) VALUES
(1, 1, 5, 'Una obra maestra absoluta. García Márquez nos transporta a un mundo mágico e inolvidable.', '2026-01-15'),
(2, 2, 5, 'Más relevante que nunca. Una advertencia sobre los peligros del totalitarismo.', '2026-01-10'),
(8, 3, 4, 'Perfecto para todas las edades. Me hizo enamorarme de la lectura.', '2026-01-08'),
(9, 4, 5, 'Simple pero profundo. Cada relectura revela nuevos significados.', '2026-01-05');
*/
CREATE TABLE usuario_libros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  libro_id INT,
  estado ENUM('pendiente','leyendo', 'terminado'),
  valoracion DECIMAL(2,1),
  resenia TEXT,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  FOREIGN KEY (libro_id) REFERENCES libros(id)
);

CREATE TABLE remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    f_caducidad DATETIME NOT NULL,
    f_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
