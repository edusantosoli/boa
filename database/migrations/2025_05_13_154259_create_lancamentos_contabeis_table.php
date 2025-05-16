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
        Schema::create('lancamentos_contabeis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('centro_de_custo_id');
            $table->string('programa');
            $table->string('pagando_a');
            $table->string('nota_fiscal')->nullable();
            $table->date('data_vencimento');
            $table->date('data_baixa')->nullable();
            $table->decimal('valor_original', 15, 2);
            $table->decimal('valor_pago', 15, 2)->default(0);
            $table->decimal('glosa', 15, 2)->default(0);
            $table->timestamps();
        
            $table->foreign('centro_de_custo_id')->references('id')->on('centros_de_custo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lancamentos_contabeis');
    }
};
