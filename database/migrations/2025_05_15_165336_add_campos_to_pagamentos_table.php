<?
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->string('cp', 10)->nullable();
            $table->string('pagado', 255)->nullable();
            $table->string('notafiscal', 50)->nullable();
            $table->date('vencimento')->nullable();
            $table->decimal('valor_original', 15, 2)->nullable();
            $table->string('glosa', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn(['cp', 'pagado', 'notafiscal', 'vencimento', 'valor_original', 'glosa']);
        });
    }
};