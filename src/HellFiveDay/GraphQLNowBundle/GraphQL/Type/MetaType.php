<?php
/**
 * Date: 31.10.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace HellFiveDay\GraphQLNowBundle\GraphQL\Type;

use Doctrine\ORM\Mapping\ClassMetadata;
use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\DateTimeType;
use Youshido\GraphQL\Type\Scalar\DateTimeTzType;
use Youshido\GraphQL\Type\Scalar\DateType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\TimestampType;

class MetaType extends AbstractObjectType
{
    /**
     * @var ClassMetadata
     */
    private $metadata;

    public function __construct(ClassMetadata $metadata,  array $config = [])
    {
        $this->metadata = $metadata;

        if(!isset($config['name'])){
            $config['name'] = $this->metadata->getTableName();
        }

        parent::__construct($config);
    }

    /**
     * @param ObjectTypeConfig $config
     * @throws \Exception
     * @return AbstractType[]
     */
    public function build($config)
    {
        $fields = [];

        foreach($this->metadata->getFieldNames() as $name){
            $mapping = $this->metadata->getFieldMapping($name);

            if($mapping['type'] === 'boolean') {
                $fields[$name] = new BooleanType();
            }else if($mapping['type'] === 'datetime') {
                $fields[$name] = new DateTimeType();
            }elseif($mapping['type'] === 'datetimetz') {
                $fields[$name] = new DateTimeTzType();
            }elseif($mapping['type'] === 'float') {
                $fields[$name] = new FloatType();
            }elseif($mapping['type'] === 'sdsd') {
                $fields[$name] = new IdType();
            }elseif($mapping['type'] === 'integer') {
                $fields[$name] = new IntType();
            }elseif($mapping['type'] === 'string') {
                $fields[$name] = new StringType();
            }elseif($mapping['type'] === 'timestamp') {
                $fields[$name] = new TimestampType();
            }else{
                throw new \Exception('Type ' . $mapping['type'] . ' not supported');
            }

            // TODO : Make a service to resolve type
            // TODO : Allow to add new type
            // TODO : Add all type for doctrine

            if($mapping['nullable'] === false){
                $fields[$name] = new NonNullType($fields[$name]);
            }
        }

        $config->addFields($fields);
    }
}