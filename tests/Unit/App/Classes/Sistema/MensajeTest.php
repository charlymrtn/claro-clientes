<?php

namespace Tests\Unit\App\Classes\Sistema;

use App;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Sistema\Mensaje;
use GuzzleHttp\Client as GuzzleClient;

class MensajeTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        // Quita los mocks al finalizar
        Mockery::close();

        parent::tearDown();
    }

    /**
     * Prueba unitaria del metodo envia
     */
    public function test_envia()
    {
        $oGuzzleResponse = Mockery::mock();
        $oGuzzleResponse
            ->shouldReceive('getStatusCode')->andReturn(200)
            ->shouldReceive('getBody')->andReturnSelf()
            ->shouldReceive('getContents')->andReturn('contents');
        $this->mockGuzzleClient = Mockery::mock(GuzzleClient::class);
        $this->mockGuzzleClient
            ->shouldReceive('request')->once()->andReturn($oGuzzleResponse);
        config(['claropagos.' . App::environment() . '.server'  => [
            'server' => [
                'url' => 'server_url',
                'token' => 'server_token',
            ]
        ]]);
        $oMensaje = new Mensaje($this->mockGuzzleClient);
        $oResult = $oMensaje->envia('server', 'url', 'GET', 'mensaje');
        $this->assertArraySubset(
            ["status" => "success", "status_code" => "200"],
            json_decode(json_encode($oResult), true)
        );
        // Prueba excepciones
        $sExMessage = 'Excepción generada por prueba unitaria.';
        $mockGuzzleRequest = Mockery::mock(\GuzzleHttp\Psr7\Request::class);

        // GuzzleHttp\Exception\ClientException
        $this->mockGuzzleClient
            ->shouldReceive('request')->once()->andThrow(
                new \GuzzleHttp\Exception\ClientException(
                    $sExMessage,
                    $mockGuzzleRequest
                ),
                $sExMessage
            );
        $oResult = $oMensaje->envia('server', 'url', 'GET', 'mensaje');
        $this->assertArraySubset(
            ["status" => "fail", "status_code" => "504"],
            json_decode(json_encode($oResult), true)
        );

        // GuzzleHttp\Exception\ConnectException
        $this->mockGuzzleClient
            ->shouldReceive('request')->once()->andThrow(
                new \GuzzleHttp\Exception\ConnectException(
                    $sExMessage,
                    $mockGuzzleRequest
                ),
                $sExMessage
            );
        $oResult = $oMensaje->envia('server', 'url', 'GET', 'mensaje');
        $this->assertArraySubset(
            ["status" => "fail", "status_code" => "502"],
            json_decode(json_encode($oResult), true)
        );

        // GuzzleHttp\Exception\ConnectException
        $this->mockGuzzleClient
            ->shouldReceive('request')->once()->andThrow(
                new \GuzzleHttp\Exception\ConnectException(
                    $sExMessage . ' cURL error 28',
                    $mockGuzzleRequest
                ),
                $sExMessage . ' cURL error 28'
            );
        $oResult = $oMensaje->envia('server', 'url', 'GET', 'mensaje');
        $this->assertArraySubset(
            ["status" => "fail", "status_code" => "504"],
            json_decode(json_encode($oResult), true)
        );

        // GuzzleHttp\Exception\ConnectException
        $this->mockGuzzleClient
            ->shouldReceive('request')->once()->andThrow(
                new \GuzzleHttp\Exception\RequestException(
                    $sExMessage,
                    $mockGuzzleRequest
                ),
                $sExMessage
            );
        $oResult = $oMensaje->envia('server', 'url', 'GET', 'mensaje');
        $this->assertArraySubset(
            ["status" => "fail", "status_code" => "520"],
            json_decode(json_encode($oResult), true)
        );

//        // Exception
//        $this->mockGuzzleClient
//            ->shouldReceive('request')->once()->andThrow(new \Exception($sExMessage), $sExMessage);
//        $oMensaje->envia('server', 'url', 'GET', 'mensaje')
//            ->assertArraySubset(
//                ["status" => "fail", "status_code" => "520"],
//                json_decode(json_encode($oResult), true)
//            );
    }
}