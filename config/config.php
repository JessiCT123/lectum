<?php

// Detecta automáticamente el host y puerto del servidor actual , para evitar fallos
const BASE_URL = 'http://localhost/lectum';// hay que modificarlo por la url base donde se sirve el proyecto.

//BASE DE DATOS
const DB_HOST = 'localhost'; //aquí se añade el host de la bd del servidor que se use. 
const DB_NAME = 'bd_lectum';
const DB_USER = 'root';
const DB_PASS = '';

//Directorios
const UPLOADS_FOLDER = 'Uploads/';
const FOTOS_FOLDER = 'Vista/assets/fotos/';
define("DIR_BASE", $_SERVER['DOCUMENT_ROOT'] . '/');

//Subida de ficheros
const MAX_FOTO_BYTES = 2 * 1024 * 1024; //2 MB para fotos de perfil (suficiente para JPG/PNG/WEBP de calidad razonable)
const EXTENSIONES_FOTOS_PERFIL = ['jpg', 'jpeg', 'png', 'webp'];

//Cookies
const COOKIE_REMEMBER = 'remember_token';
