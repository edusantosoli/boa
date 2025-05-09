<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldos_contabeis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conta_contabil_id')->constrained('conta_contabils')->onDelete('cascade');
            $table->foreignId('centro_de_custo_id')->constrained('centros_de_custo')->onDelete('cascade');
            $table->integer('ano');
            $table->decimal('valor', 15, 2);
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
        Schema::dropIfExists('saldos_contabeis');
    }
};
