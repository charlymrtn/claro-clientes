<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestappCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testapp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corre las pruebas completas de la aplicación y genera el reporte de cobertura.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Iniciando comando
        $this->info("");
        $this->info(" Generador de reporte de cobertura y pruebas unitarias.");
        $this->info("");

        // Barra de progreso
        $oProgressBar = $this->output->createProgressBar(5);
        $oProgressBar->setFormat("   %current%/%max% [%bar%] %percent:3s%% %message%");
        $oProgressBar->setMessage(" Iniciando pruebas de la aplicación...");
        $oProgressBar->start();

        // Revisa ruta de reporte de cobertura
        try {
            // Define variabels
            $fCoverageReportFacadeFile = base_path('vendor/phpunit/php-code-coverage/src/Report/Html/Facade.php');
            $sOldTemplatePath = '__DIR__ . \'/Renderer/Template/\'';
            $sNewTemplatePath = "resource_path('views/vendor/coverage-report-html/')";
            // Abre archivo
            $sPCRFacade = file_get_contents($fCoverageReportFacadeFile);
            // Busca path viejo
            if (strpos($sPCRFacade, $sOldTemplatePath) !== false) {
                // Reemplaza path
                file_put_contents($fCoverageReportFacadeFile, str_replace($sOldTemplatePath, $sNewTemplatePath, $sPCRFacade));
            } else {
                // Ruta vieja no encontrada, asumimos ya está la nueva.
            }
            $oProgressBar->advance();
            $oProgressBar->setMessage(" Reporte de cobertura configurado.");
        } catch (\Exception $e) {
            $this->error("Error al validar templates de reporte de cobertura:" . $e->getMessage());
            return 2;
        }

        // Corre pruebas unitarias y genera reporte
        system('vendor/bin/phpunit');
        $oProgressBar->advance();
        $oProgressBar->setMessage(" Pruebas unitarias terminadas.");

        // Termina
        $oProgressBar->finish();
        $oProgressBar->setMessage(" Pruebas de la aplicación terminadas y reporte generado.");
        $this->info(""); $this->info("");
        $this->info(" Pruebas de la aplicación terminadas y reporte generado.");
        $this->info("");
        return 0;
    }
}