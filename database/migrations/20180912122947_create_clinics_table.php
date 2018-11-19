<?php

use Illuminate\Database\Schema\Blueprint;
use Phinx\Migration\AbstractMigration;

class CreateClinicsTable extends BaseMigration
{
	 public $set_schema_table = 'clinics';

    public function up()
    {
      $this->schema->create($this->set_schema_table, function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id');
        $table->integer('clinic_type_id');
        $table->string('name', 100);
        $table->string('email', 100);
        $table->string('phone_number', 15);
        $table->string('website', 45)->nullable();
        $table->string('state', 100);
        $table->string('city', 45);
        $table->string('address', 45);
        $table->text('description')->nullable();
        $table->enum('medical_record_type', ['electronic','paper'])->default('electronic');
        $table->enum('payment_type', ['electronic','cash','both'])->default('electronic');
        $table->string('contact_person_name', 45);
        $table->string('contact_person_email', 100);
        $table->string('latitude', 100);
        $table->string('longitude', 100);
        $table->enum('approved', ['0','1'])->default('1');
        $table->enum('deleted', ['0','1'])->default('0');

        // $table->index(["clinic_type_id"], 'fk_clinic_type_idx');

        // $table->index(["user_id"], 'fk_facility_1_idx');
        $table->timestamps();


        // $table->foreign('user_id', 'fk_facility_1_idx')
        //   ->references('id')->on('users')
        //   ->onDelete('cascade')
        //   ->onUpdate('cascade');

        // $table->foreign('clinic_type_id', 'fk_clinic_type_idx')
        //   ->references('id')->on('clinic_type')
        //   ->onDelete('cascade')
        //   ->onUpdate('cascade');
      });
    }
    
    public function down()
    {
      $this->schema->dropIfExists($this->set_schema_table);
    }

}
