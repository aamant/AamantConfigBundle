<?php namespace Aamant\ConfigBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 *
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */
class Config
{
    /**
     * @var EntityManager
     */
    private $manager;
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;
    /**
     * @var array
     */
    private $data;

    /**
     * Config constructor.
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository('AamantConfigBundle:Config');
    }

    /**
     * @return $this
     */
    protected function loadData()
    {
        $this->data = $this->repository->findAll();
        return $this;
    }

    /**
     * @param $name
     * @param null $return
     * @return mixed
     */
    public function get($name, $return = null)
    {
        $node = $this->find($name);
        if ($node) return $node->getValue();
        return $return;
    }

    /**
     * @param $name
     * @param $value
     * @param bool $hidden
     * @return $this
     */
    public function set($name, $value, $hidden = false)
    {
        $node = $this->find($name);
        if (!$node){
            $node = new \Aamant\ConfigBundle\Entity\Config();
            $node->setName($name);
        }

        $node->setValue($value)
            ->setHidden($hidden);

        $this->manager->persist($node);
        $this->manager->flush();

        $this->loadData();
        return $this;
    }

    /**
     * @param $name
     * @return boolean
     */
    public function has($name)
    {
        $node = $this->find($name);
        return $node !== null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function delete($name)
    {
        $node = $this->find($name);
        if ($node) {
            $this->manager->remove($node);
            $this->manager->flush($node);
        }

        return true;
    }

    /**
     * @param $name
     * @return \Aamant\ConfigBundle\Entity\Config|null
     */
    protected function find($name)
    {
        if (!$this->data){
            $this->loadData();
        }

        foreach ($this->data as $node){
            if ($node->getName() == $name) return $node;
        }

        return null;
    }

    /**
     * @param bool $hidden
     * @return \Aamant\ConfigBundle\Entity\Config[]|array
     */
    public function findAll($hidden = false)
    {
        return $this->repository
            ->findBy(['hidden' => $hidden], ['name' => 'ASC'])
        ;
    }
}