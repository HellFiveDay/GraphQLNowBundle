<?php
/**
 * Date: 31.10.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace HellFiveDay\GraphQLNowBundle\GraphQL\Query\Field;

use HellFiveDay\GraphQLNowBundle\GraphQL\Type\MetaType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQLBundle\Field\AbstractContainerAwareField;

class ORMListField extends AbstractContainerAwareField
{
    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @var EntityManager
     */
    private $entityManger;

    public function __construct(ClassMetadata $metadata, EntityManager $entityManger, array $config = [])
    {
        $this->metadata = $metadata;
        $this->entityManger = $entityManger;

        parent::__construct($config);
    }

    public function getName()
    {
        return $this->metadata->getTableName() . 's';
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $this->entityManger->getRepository($this->metadata->getName())->findAll();
    }

    /**
     * @return AbstractObjectType|AbstractType
     */
    public function getType()
    {
        return new ListType(new MetaType($this->metadata));
    }
}