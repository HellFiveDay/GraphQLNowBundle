<?php
/**
 * Date: 31.10.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace HellFiveDay\GraphQLNowBundle\GraphQL\Mutation;

use HellFiveDay\GraphQLNowBundle\GraphQL\Mutation\Field\ORMAddField;
use HellFiveDay\GraphQLNowBundle\GraphQL\Mutation\Field\ClearCompletedField;
use HellFiveDay\GraphQLNowBundle\GraphQL\Mutation\Field\DestroyField;
use HellFiveDay\GraphQLNowBundle\GraphQL\Mutation\Field\SaveField;
use HellFiveDay\GraphQLNowBundle\GraphQL\Mutation\Field\ToggleAllField;
use HellFiveDay\GraphQLNowBundle\GraphQL\Mutation\Field\ToggleField;
use Doctrine\ORM\EntityManager;
use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\Object\AbstractObjectType;

class ORMMutationType extends AbstractObjectType
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
       //     $fields[] = new ORMField($m);
        }

        $config->addFields($fields);
    }


    /**
     * @param ObjectTypeConfig $config
     *
     * @return mixed
     */
//    public function build($config)
//    {
//        $config->addFields([
//            new AddTodoField(),
//            new ToggleAllField(),
//            new ToggleField(),
//            new DestroyField(),
//            new SaveField(),
//            new ClearCompletedField()
//        ]);
//    }
}