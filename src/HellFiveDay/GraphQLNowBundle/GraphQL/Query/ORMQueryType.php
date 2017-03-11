<?php
/**
 * Date: 31.10.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace HellFiveDay\GraphQLNowBundle\GraphQL\Query;

use HellFiveDay\GraphQLNowBundle\GraphQL\Query\Field\ORMField;
use HellFiveDay\GraphQLNowBundle\GraphQL\Query\Field\ORMListField;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\Object\AbstractObjectType;

class ORMQueryType extends AbstractObjectType
{
    /**
     * @var EntityManager
     */
    private $entityManger;

    /**
     * Schema constructor.
     * @param EntityManager $entityManger
     * @param array $config
     */
    public function __construct(EntityManager $entityManger, $config = [])
    {
        $this->entityManger = $entityManger;

        if(!isset($config['name'])){
            $config['name'] = 'query';
        }

        parent::__construct($config);
    }

    /**
     * @param ObjectTypeConfig $config
     *
     * @return mixed
     */
    public function build($config)
    {
        $meta = $this->entityManger->getMetadataFactory()->getAllMetadata();

        $fields = [];

        /** @var ClassMetadata $m */
        foreach ($meta as $m) {
            $fields[] = new ORMField($m, $this->entityManger);
            $fields[] = new ORMListField($m, $this->entityManger);
        }

        $config->addFields($fields);
    }
}