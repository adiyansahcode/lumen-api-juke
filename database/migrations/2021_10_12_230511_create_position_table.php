<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position', function (Blueprint $table) {
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

            $table->string('name', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('position');
    }
}
