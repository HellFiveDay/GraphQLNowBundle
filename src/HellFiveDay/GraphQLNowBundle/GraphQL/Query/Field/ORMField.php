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
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\DateTimeType;
use Youshido\GraphQL\Type\Scalar\DateTimeTzType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\TimestampType;
use Youshido\GraphQLBundle\Field\AbstractContainerAwareField;

class ORMField extends AbstractContainerAwareField
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
        return $this->metadata->getTableName();
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $this->entityManger->getRepository($this->metadata->getName())->findOneBy($args);
    }

    public function build(FieldConfig $config)
    {
        $arguments = [];

        foreach($this->metadata->getFieldNames() as $name){
            $mapping = $this->metadata->getFieldMapping($name);

            if($mapping['type'] === 'boolean') {
                $arguments[$name] = new BooleanType();
            }else if($mapping['type'] === 'datetime') {
                $arguments[$name] = new DateTimeType();
            }elseif($mapping['type'] === 'datetimetz') {
                $arguments[$name] = new DateTimeTzType();
            }elseif($mapping['type'] === 'float') {
                $arguments[$name] = new FloatType();
            }elseif($mapping['type'] === 'sdsd') {
                $arguments[$name] = new IdType();
            }elseif($mapping['type'] === 'integer') {
                $arguments[$name] = new IntType();
            }elseif($mapping['type'] === 'string') {
                $arguments[$name] = new StringType();
            }elseif($mapping['type'] === 'timestamp') {
                $arguments[$name] = new TimestampType();
            }else{
                throw new \Exception('Type ' . $mapping['type'] . ' not supported');
            }

            // TODO : Make a service to resolve type
            // TODO : Allow to add new type
            // TODO : Add all type for doctrine
        }

        $config->addArguments($arguments);
    }

    /**
     * @return AbstractObjectType|AbstractType
     */
    public function getType()
    {
        return new MetaType($this->metadata);
    }
}