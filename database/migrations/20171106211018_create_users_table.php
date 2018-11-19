<?php

use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends BaseMigration
{
    public $set_schema_table = 'users';

    public function up()
    {
        $this->schema->create($this->set_schema_table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number', 15)->nullable();
            $table->string('password');
            $table->text('token')->nullable();
            $table->string('bio')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('type')->default('0')->comment('0 => user; 1=> doctor');
            $table->tinyInteger('status')->default('0')->comment('0 => inactive; 1 => active');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists($this->set_schema_table);
    }
}
