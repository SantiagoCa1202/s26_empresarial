<?php
//url del proyecto
// const BASE_URL = "http://localhost/s26_empresarial";
//const BASE_URL = "http://192.168.0.104/s26_empresarial";
const BASE_URL = "http://192.168.100.6/s26_empresarial";

//Zona Horaria 
date_default_timezone_set('America/Guayaquil');
setlocale(LC_TIME, "spanish");

//Datos de conexion a base de datos
const DB_HOST = "localhost";
const DB_NAME = "s26_empresarial";
const DB_USER = "root";
const DB_PASSWORD = "S26/3mpr35@r1@1";
const DB_CHARSET = "charset=utf8";

//Deliminadores decimal y milla Ej. 24,1989.00
const SPD = ".";
const SPM = ",";

//Simbolo de moneda
const SMONEY = "$";

//Datos de empresa
const NAME_SENDER = "S26 Empresarial";
const EMAIL_SENDER = "no-reply@s26empresarial.com";

const NAME_COMPANY = "S26 Empresarial";
const WEB_COMPANY = "www.s26-empresarial.com";
