<?php

/**
 * Interfaz que representa una entidad en el sistema.
 * 
 * Una entidad es un objeto que se corresponde con una tabla en la base de datos y tiene un identificador único.
 * Las entidades deben implementar esta interfaz para proporcionar el acceso al identificador único y garantizar
 * que se puedan utilizar de manera consistente en operaciones de almacenamiento, recuperación y actualización.
 */
interface IEntity
{
  /**
   * Obtiene el identificador único de la entidad.
   *
   * Este método debe devolver el identificador único de la entidad, que generalmente es una clave primaria en
   * la base de datos. El tipo del identificador puede ser tanto un entero como una cadena, dependiendo del
   * tipo de clave utilizada.
   *
   * @return int|string El identificador único de la entidad.
   */
  public function getId(): int | string;
}

/**
 * Interfaz que define el comportamiento de un criterio de búsqueda para una entidad.
 * 
 * Un criterio de búsqueda es un conjunto de condiciones que se utilizan para filtrar los resultados
 * al obtener entidades de la base de datos. El objetivo de esta interfaz es proporcionar una forma estandarizada
 * de convertir los criterios de búsqueda en un array asociativo de condiciones para su uso en consultas SQL.
 */
interface IEntityCriteria
{
  /**
   * Devuelve un array asociativo de los campos que no son nulos en el criterio de búsqueda.
   *
   * Este método debe recorrer las propiedades del criterio de búsqueda y devolver un array donde las claves
   * son los nombres de los campos (por ejemplo, `campo = valor`), y los valores son los valores de los campos.
   * Solo deben incluirse en el array aquellos campos cuya propiedad no sea nula. Este array se puede utilizar
   * para generar condiciones en una consulta SQL, por ejemplo, en una cláusula `WHERE`.
   *
   * @return array Un array asociativo campo => valor con los campos no nulos del criterio de búsqueda.
   */
  public function toAssocArray(): array;
}
