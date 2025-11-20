<?php

namespace WappoVendor\Doctrine\DBAL\Schema\Visitor;

use WappoVendor\Doctrine\DBAL\Schema\Column;
use WappoVendor\Doctrine\DBAL\Schema\ForeignKeyConstraint;
use WappoVendor\Doctrine\DBAL\Schema\Index;
use WappoVendor\Doctrine\DBAL\Schema\Schema;
use WappoVendor\Doctrine\DBAL\Schema\Sequence;
use WappoVendor\Doctrine\DBAL\Schema\Table;
/**
 * Abstract Visitor with empty methods for easy extension.
 */
class AbstractVisitor implements Visitor, NamespaceVisitor
{
    public function acceptSchema(Schema $schema)
    {
    }
    /**
     * {@inheritdoc}
     */
    public function acceptNamespace($namespaceName)
    {
    }
    public function acceptTable(Table $table)
    {
    }
    public function acceptColumn(Table $table, Column $column)
    {
    }
    public function acceptForeignKey(Table $localTable, ForeignKeyConstraint $fkConstraint)
    {
    }
    public function acceptIndex(Table $table, Index $index)
    {
    }
    public function acceptSequence(Sequence $sequence)
    {
    }
}
