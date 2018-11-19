<?php

use Illuminate\Database\Schema\Blueprint;
use Phinx\Migration\AbstractMigration;

class CreateClinicTypeTable extends BaseMigration
{
	public $set_schema_table = 'clinic_types';

  public function up()
  {
  	$this->schema->create($this->set_schema_table, function (Blueprint $table) {
  		$table->increments('id');
  		$table->string('name', 45)->nullable();
      $table->enum('enabled', ['0','1'])->nullable()->default('0');
      $table->timestamps();
  	});
  }
    
  public function down()
  {
  	$this->schema->dropIfExists($this->set_schema_table);
  }

}
