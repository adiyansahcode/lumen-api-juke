<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('user')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('user')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');

            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('user')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->date('date_of_birth')->nullable();
            $table->string('email', 100)->index();
            $table->string('phone', 100)->index();

            $table->foreignId('regency_id')
                ->nullable()
                ->constrained('regencies')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->text('address')->nullable();
            $table->string('zip_code', 100)->nullable();

            $table->string('ktp', 100);
            $table->text('ktp_image')->nullable();
            $table->text('ktp_image_url')->nullable();

            $table->foreignId('position_id')
                ->nullable()
                ->constrained('position')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');

            $table->foreignId('bank_id')
                ->nullable()
                ->constrained('bank')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->string('bank_account', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee');
    }
}
