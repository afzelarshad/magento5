<?php
namespace Mastering\SampleModule\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        

        $setup->startSetup();

        $table = $setup->getConnection()
            ->newTable($setup->getTable('mastering_sample_item'))
            ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Item ID')
            ->addColumn('name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,['nullable'=>false], 'Item Name')
            ->addIndex($setup->getIdxName('mastering_sample_item',['name']),['name'])
            ->setComment('Sample Item');
            $setup->getConnection()->createTable($table);

            $setup->endSetup();

    }
}
