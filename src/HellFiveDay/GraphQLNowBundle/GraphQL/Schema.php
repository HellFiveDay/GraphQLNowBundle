<?php

namespace HellFiveDay\GraphQLNowBundle\GraphQL;

use Youshido\GraphQL\Schema\AbstractSchema;
use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Type\Object\AbstractObjectType;

class Schema extends AbstractSchema
{
    /**
     * @var AbstractObjectType
     */
    private $queryType;

    /**
     * @var AbstractObjectType
     */
    private $mutationType;

    /**
     * Schema constructor.
     * @param AbstractObjectType $queryType
     * @param AbstractObjectType $mutationType
     * @param array $config
     */
    public function __construct(AbstractObjectType $queryType, AbstractObjectType $mutationType, $config = [])
    {
        $this->queryType = $queryType;
        $this->mutationType = $mutationType;

        parent::__construct($config);
    }

    /**
     * @param SchemaConfig $config
     */
    public function build(SchemaConfig $config)
    {
        $config
           // ->setMutation($this->mutationType)
            ->setQuery($this->queryType);
    }
}

