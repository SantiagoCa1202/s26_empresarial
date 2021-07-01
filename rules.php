<?php
  // // ESTADOS  // // // 
  // Eliminado = 0
  // Activo = 1
  // Inactivo = 2

  // // RESPUESTAS  // // // 
  // Datos Agregados Correctamente =  tipo = 1 && res > 0
  // Datos Actualizados Correctamente =  tipo = 2 && res > 0
  // Datos Eliminados Correctamente =  tipo = 3 && res > 0

  // Error al Agregar Dato =  tipo = 1 && res == 0
  // Error al Actualizar Dato =  tipo = 2 && res == 0
  // Error al Eliminar Dato =  tipo = 3 && res == 0


  // Error Al Ingresar o Actualizar Datos = -1
  // El Registro Ya Existe = -2
  // Registro Vinculado a otras tablas = -3
  // No Se Puede Actualizar / Ingresar o Eliminar Este Registro (Registro Especials) = -4
  // No tiene permiso para realizar esta acción = -5
