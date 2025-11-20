<?php

namespace WappoVendor\Doctrine\DBAL\Schema\Visitor;

use WappoVendor\Doctrine\DBAL\Schema\ForeignKeyConstraint;
use WappoVendor\Doctrine\DBAL\Schema\Sequence;
use WappoVendor\Doctrine\DBAL\Schema\Table;
use WappoVendor\Doctrine\DBAL\Schema\TableDiff;
/**
 * Visit a SchemaDiff.
 */
interface SchemaDiffVisitor
{
    /**
     * Visit an orphaned foreign key whose table was deleted.
     *
     * @return void
     */
    public function visitOrphanedForeignKey(ForeignKeyConstraint $foreignKey);
    /**
     * Visit a sequence that has changed.
     *
     * @return void
     */
    public function visitChangedSequence(Sequence $sequence);
    /**
     * Visit a sequence that has been removed.
     *
     * @return void
     */
    public function visitRemovedSequence(Sequence $sequence);
    /** @return void */
    public function visitNewSequence(Sequence $sequence);
    /** @return void */
    public function visitNewTable(Table $table);
    /** @return void */
    public function visitNewTableForeignKey(Table $table, ForeignKeyConstraint $foreignKey);
    /** @return void */
    public function visitRemovedTable(Table $table);
    /** @return void */
    public function visitChangedTable(TableDiff $tableDiff);
}
