<?php
namespace Crud\Practice\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('crud_table'))
            ->addColumn('article_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'ID')
            ->addColumn('title', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable'=>false], 'Title')
            ->addColumn('content', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20, ['nullable'=>false], 'Content')
            >addColumn('created_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, 20, ['nullable'=>false], 'Created At')
            ->setComment('CRUD Table');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();

    }
}
