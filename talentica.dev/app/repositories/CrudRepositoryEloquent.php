<?php
/**
 * Created by PhpStorm.
 * User: santoshm1
 * Date: 26/05/14
 * Time: 5:15 PM
 */

class CrudRepositoryEloquent implements CrudRepository
{
    /**
     * Saves a given entity. Use the returned instance for further operations as the save operation might have changed the
     * entity instance completely.
     *
     * @param entity
     * @return the saved entity
     */

    public function save($entity)
    {
        $this->model->save($entity);
        return $this->findOne($entity->id);
    }

    /**
     * Saves all given entities.
     *
     * @param entities
     * @return the saved entities
     * @throws IllegalArgumentException in case the given entity is (@literal null}.
     */
    public function saveAll($entities)
    {
        //Assume it to be a list of ids
        $result = array();
        if ($this->is_iterable($entities))
        {
            foreach($entities as $entity)
            {
                $result [] = $this->save($entity);
            }
        }
        return $result;
    }

    /**
     * Retrieves an entity by its id.
     *
     * @param must $id
     * @internal param \must $id not be {@literal null}..
     * @return the entity with the given id or {@literal null} if none found
     */

    public function findOne($id)
    {
        $result = null;
        try
        {
           $result = $this->model->findOrFail($id);
        }
        catch (Exception $e)
        {
            //Log the exception
        }
        return $result;
    }

    /**
     * Returns whether an entity with the given id exists.
     *
     * @param must $id
     * @internal param \must $id not be {@literal null}..
     * @return true if an entity with the given id exists, {@literal false} otherwise
     */
    public function exists($id)
    {
        return null === $this->findOne($id);
    }

    /**
     * Returns all instances of the type.
     *
     * @return all entities
     */
    public function findAll()
    {
        return $this->model->all();
    }

    /**
     * Returns all instances of the type with the given IDs.
     *
     * @param ids
     * @return
     */

    public function findAllByIds($ids)
    {
        $result = array();
        if (is_iterable ($ids))
        {
            foreach ($ids as $id)
            {
                $result [] = $this->model->find($id);
            }
        }
        return $result;
    }

    /**
     * Returns the number of entities available.
     *
     * @return the number of entities
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Deletes the entity with the given id.
     *
     * @param must $id
     * @internal param \must $id not be {@literal null}..
     */
    public function delete($id)
    {
          return $this->model->destroy($id);
    }

    /**
     * Deletes a given entity.
     *
     * @param entity
     * @throws IllegalArgumentException in case the given entity is (@literal null}.
     */
    public function deleteEntity($entity)
    {
        return $this->model->destroy($entity->id);

    }

    /**
     * Deletes the given entities.
     *
     * @param entities
     * @return array
     */
    public function deleteEntities($entities)
    {
        $result = array();
        if ($this->is_iterable($entities))
        {
            foreach($entities as $entity)
            {
                $result[$entity->id] = $this->deleteEntity($entity);
            }
        }
        return $result;
    }


    /**
     * Deletes all entities managed by the repository.
     */
    public function  deleteAll()
    {
        //Find and destroy
        $entities = $this->findAll();
        return $this->deleteAll($entities);
    }

    private function is_iterable($var) {
        return (is_array($var) || $var instanceof Traversable);
    }

}