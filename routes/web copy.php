<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContaContabilController;
use App\Models\ContaContabil;
use App\Http\Controllers\TipoServicoController;
use App\Http\Controllers\ContaController;
use App\Exports\TipoServicosExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TipoServico;
use App\Http\Controllers\CentroDeCustoController;
use App\Http\Controllers\SaldoContabilController;
use App\Http\Controllers\LancamentoContabilController;
use App\Http\Controllers\PagamentoController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::resource('contas', ContaContabilController::class);
Route::resource('tipo-servicos', TipoServicoController::class);
Route::get('/contas/export/pdf', [ContaController::class, 'exportPDF'])->name('contas.export.pdf');
Route::get('/contas/export/excel', [ContaController::class, 'exportExcel'])->name('contas.export.excel');
Route::get('/contas/export/pdf', [ContaController::class, 'exportPDF']);
Route::get('/contas/export/pdf', [ContaController::class, 'exportPDF'])->name('contas.export.pdf');

Route::get('/tipo-servicos/export/excel', function () {
    return Excel::download(new TipoServicosExport, 'tipo_servicos.xlsx');
})->name('tipo-servicos.export.excel');

Route::get('/tipo-servicos/export/pdf', function () {
    $dados = \App\Models\TipoServico::with('contaContabil')->get();
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tipo_servicos.pdf', compact('dados'));
    return $pdf->download('tipo_servicos.pdf');
})->name('tipo-servicos.export.pdf');

Route::resource('centros', CentroDeCustoController::class);
Route::get('/centros/export/excel', [CentroDeCustoController::class, 'exportExcel'])->name('centros.export.excel');
Route::get('/centros/export/pdf', [CentroDeCustoController::class, 'exportPdf'])->name('centros.export.pdf');
Route::resource('saldos', SaldoContabilController::class);

Route::resource('lancamentos', LancamentoContabilController::class);
Route::resource('pagamentos', PagamentoController::class);
Route::post('/pagamentos/importar', [PagamentoController::class, 'importar'])->name('pagamentos.importar');
Route::get('/tipos-servico/por-conta/{contaId}', function ($contaId) {
    return \App\Models\TipoServico::where('conta_contabil_id', $contaId)->get();
    });
Route::get('/tipos-servico/por-conta/{contaId}', [TipoServicoController::class, 'porConta']);
Route::get('/tipos-servico/por-conta/{contaId}', [App\Http\Controllers\TipoServicoController::class, 'porConta']);
Route::post('/importar-pagamentos', [PagamentoController::class, 'importarPagamentos'])->name('pagamentos.importar');
Route::get('/pagamentos/importar', [PagamentoController::class, 'importarPagamentosView'])->name('pagamentos.importar.view');
Route::post('/pagamentos/importar', [PagamentoController::class, 'importarPagamentos'])->name('pagamentos.importar');
Route::resource('pagamentos', PagamentoController::class)->except(['show']);