<?php

namespace Xframe\Plugin\Helper;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * @package plugin
 *
 * @see http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/sql-table-prefixes.html
 */
class EmTablePrefixPluginHelper
{
    protected $prefix;

    public function __construct(string $prefix = 'x')
    {
        $this->prefix = $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (!$classMetadata->isInheritanceTypeSingleTable() || $classMetadata->getName() === $classMetadata->rootEntityName) {
            $classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
        }

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] === \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide']) {
                $mappedTableName = $mapping['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }
    }
}
