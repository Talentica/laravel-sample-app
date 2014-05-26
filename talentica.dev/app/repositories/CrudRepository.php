<?php

//PHP implementation of Spring's CrudRepository
//https://github.com/spring-projects/spring-data-commons/blob/master/src/main/java/org/springframework/data/repository/CrudRepository.java

interface CrudRepository extends Repository
{
    /**
     * Saves a given entity. Use the returned instance for further operations as the save operation might have changed the
     * entity instance completely.
     *
     * @param entity
     * @return the saved entity
     */
     public function save($entity);

    /**
     * Saves all given entities.
     *
     * @param entities
     * @return the saved entities
     * @throws IllegalArgumentException in case the given entity is (@literal null}.
     */
     public function saveAll($entities);

    /**
     * Retrieves an entity by its id.
     *
     * @param must $id
     * @internal param \must $id not be {@literal null}..
     * @return the entity with the given id or {@literal null} if none found
     */

    public function findOne($id);

    /**
     * Returns whether an entity with the given id exists.
     *
     * @param must $id
     * @internal param \must $id not be {@literal null}..
     * @return true if an entity with the given id exists, {@literal false} otherwise
     */
    public function exists($id);

    /**
     * Returns all instances of the type.
     *
     * @return all entities
     */
    public function findAll();

    /**
     * Returns all instances of the type with the given IDs.
     *
     * @param ids
     * @return
     */

    public function findAllByIds($ids);

    /**
     * Returns the number of entities available.
     *
     * @return the number of entities
     */
    public function count();

    /**
     * Deletes the entity with the given id.
     *
     * @param must $id
     * @internal param \must $id not be {@literal null}..
     */
    public function delete($id);

    /**
     * Deletes a given entity.
     *
     * @param entity
     * @throws IllegalArgumentException in case the given entity is (@literal null}.
     */
    public function deleteEntity($entity);

    /**
     * Deletes the given entities.
     *
     * @param entities
     * @throws IllegalArgumentException in case the given {@link Iterable} is (@literal null}.
     */
    public function deleteEntities($entities);

    /**
     * Deletes all entities managed by the repository.
     */
    public function  deleteAll();
}