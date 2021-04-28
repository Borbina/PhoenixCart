<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CsobToOrder extends AbstractMigration {

    /**
     */
    public function change(): void {
        $table = $this->table('csob', ['id' => false, 'primary_key' => 'payId']);
        $table->addColumn('payId', 'string', ['length' => 15])
                ->addColumn('paymentStatus', 'integer', ['default' => 1])
                ->addColumn('orders_id', 'integer', ['null' => false,'signed'=>false])
                ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->create();
    }

}
