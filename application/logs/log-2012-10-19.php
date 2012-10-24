<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

ERROR - 2012-10-19 16:05:57 --> Query error: Table 'emailing.usuario' doesn't exist
ERROR - 2012-10-19 16:13:45 --> Severity: Warning  --> mysql_insert_id() expects parameter 1 to be resource, boolean given /home/andres/emailing/system/database/drivers/mysql/mysql_driver.php 358
ERROR - 2012-10-19 16:28:27 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`emailing`.`lista_correo`, CONSTRAINT `fk_lista_correo_origen_lista1` FOREIGN KEY (`origen_lista_id_origen`) REFERENCES `origen_lista` (`id_origen`) ON DELETE NO ACTION ON UPDATE NO ACTION)
