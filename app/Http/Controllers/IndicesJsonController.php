<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;
use App\Services\IndicesService;

class IndicesJsonController extends AbstractController
{
    private $indicesService;

    public function __construct(IndicesService $indicesService)
    {
        $this->indicesService = $indicesService;
    }

    public function ajustar()
    {

        $iteracoes = [
            ['indice' => 'tabelaAtualizacaoMonetaria', 'valor' => '2024;1;93.168579', 'funcao' => 'indiceTjsp'],
            ['indice' => 'ORTNOTN', 'valor' => '2024;1;1.791000', 'funcao' => 'indiceOrtn'],
            // ['indice' => 'UFIR', 'valor' => '2022;1;4.091500', 'funcao' => 'indiceUfir'],
            ['indice' => 'CADERNETAPOUPANCA', 'valor' => '2024;1;43.697319', 'funcao' => 'indiceCadernetaPoupanca'],
            ['indice' => 'IGPDI', 'valor' => '2024;1;16.062345', 'funcao' => 'indiceIgpdi'],
            ['indice' => 'IGPM', 'valor' => '2024;1;11420.435591', 'funcao' => 'indiceIgpm'],
            ['indice' => 'INPC', 'valor' => '2024;1;453.460321', 'funcao' => 'indiceInpc'],
            ['indice' => 'IPCA', 'valor' => '2024;1;2.643566', 'funcao' => 'indiceIpca'],
            ['indice' => 'SELIC', 'valor' => '2024;1;4.767605', 'funcao' => 'indiceSelic'],
            ['indice' => 'IPC', 'valor' => '2024;1;0.388873', 'funcao' => 'indiceIpcFipe'],
            ['indice' => 'IPCFGV', 'valor' => '2024;1;0.529004', 'funcao' => 'indiceIpcFgv'],
            ['indice' => 'TR', 'valor' => '2024;1;0.791455', 'funcao' => 'indiceTr'],
            ['indice' => 'CUBSP', 'valor' => '2022;1;0.312276', 'funcao' => 'indiceCubsp'],

            ['indice' => 'TJMG', 'valor' => '', 'funcao' => 'indiceTjmg'],
            ['indice' => 'TJDF', 'valor' => '', 'funcao' => 'indiceTjmg'],
            ['indice' => 'TJES', 'valor' => '', 'funcao' => 'indiceTjmg'],
            ['indice' => 'TJRO', 'valor' => '', 'funcao' => 'indiceTjmg'],
            ['indice' => 'ENCOGE', 'valor' => '', 'funcao' => 'indiceTjmg'],

            // ['indice' => 'TJRJ', 'valor' => '2022;1;412.764786', 'funcao' => 'indiceInpc'],
        ];

        foreach ($iteracoes as $key => $iteracao) {

            $filename = "/var/www/storage/app/atualizacaomonetaria/{$iteracao['indice']}.csv";
            $filenameDuplicado = "/var/www/storage/app/tabelas/{$iteracao['indice']}.csv";

            // Lê o arquivo CSV existente
            $file = fopen($filename, 'r');
            $newData = json_decode(self::{$iteracao['funcao']}());

            if ($file) {
                $data = []; // Array para armazenar os dados lidos do CSV

                while (($row = fgetcsv($file)) !== false) {
                    if ($row[0] == $iteracao['valor']) {
                        break;
                    }

                    $data[] = $row; // Armazena os dados lidos no array
                }

                fclose($file);
            } else {
                echo "Não foi possível abrir o arquivo.";
            }

            $newData_array = [];
            foreach ($newData as $key => $value) {
                $newData_array[] = [$value];
            }

            // Abre o arquivo para escrita
            $file = fopen($filename, 'w');
            $fileDuplicado = fopen($filenameDuplicado, 'w');

            if ($file) {
                // Escreve os dados antigos de volta no arquivo
                if (!in_array($iteracao['indice'], ['TJMG', 'TJDF', 'TJES', 'TJRO', 'ENCOGE'])) {
                    foreach ($data as $row) {
                        fputcsv($file, $row);
                    }
                }

                // Adiciona os novos dados ao arquivo
                foreach ($newData_array as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            } else {
                echo "Não foi possível abrir o arquivo para escrita.";
            }

            if ($fileDuplicado) {
                // Escreve os dados antigos de volta no arquivo
                if (!in_array($iteracao['indice'], ['TJMG', 'TJDF', 'TJES', 'TJRO', 'ENCOGE'])) {
                    foreach ($data as $row) {
                        fputcsv($fileDuplicado, $row);
                    }
                }

                // Adiciona os novos dados ao arquivo
                foreach ($newData_array as $row) {
                    fputcsv($fileDuplicado, $row);
                }


                fclose($fileDuplicado);
            } else {
                echo "Não foi possível abrir o arquivo para escrita.";
            }

        }

    }

    /**
     * Busca os índices TJSP
     *
     * @return string
     */
    public function indiceTjsp()
    {
        $crawler = $this::getCrawler(parent::url_tjsp);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = $this->indicesService->getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        foreach ($anoMesIndice as $key => $value) {
            if (intval($key) >= 2024) {
                foreach ($value as $key2 => $value2) {
                    $resultados[] = $key.';'.$key2.';'.str_replace(',', '.', str_replace('.', '', $value2));
                }
            }
        }
        $resultados[] = $key.';'.($key2+1).';'.str_replace(',', '.', str_replace('.', '', $value2));

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['tjsp'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices ORTN
     *
     * @return string
     */
    public function indiceOrtn()
    {
        $crawler = $this::getCrawler(parent::url_ortn);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = $this->indicesService->getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        foreach ($anoMesIndice as $key => $value) {
            if (intval($key) >= 2024) {
                foreach ($value as $key2 => $value2) {
                    $decimalPart = str_replace(',', '.', str_replace('.', '', $value2)).'00';
                    $resultados[] = $key.';'.$key2.';'.str_pad($decimalPart, 8, '0', STR_PAD_RIGHT);
                }
            }
        }
        $decimalPart = str_replace(',', '.', str_replace('.', '', $value2)).'00';
        $resultados[] = $key.';'.($key2+1).';'.str_pad($decimalPart, 8, '0', STR_PAD_RIGHT);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['ortn'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices ufri
     *
     * @return string
     */
    public function indiceUfir()
    {
        $crawler = $this::getCrawler(parent::url_ufir);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = $this->indicesService->getDataIndiceUfir($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        foreach ($anoMesIndice as $key => $value) {
            if (intval($key) >= 2022) {
                foreach ($value as $key2 => $value2) {
                    $resultados[] = [$key, $key2, str_replace(',', '.', str_replace('.', '', $value2)).'00'];
                }
            }
        }

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['ufir'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices CADERNETA POUPANÇA
     *
     * @return string
     */
    public function indiceCadernetaPoupanca()
    {
        $crawler = $this::getCrawler(parent::url_caderneta_poupanca);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = $this->indicesService->getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        $valorCalculadoAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2024) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2024 && $key2 == 1) {
                        $valorCalculadoAnterior = 43.441924;
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $value2) / 100);

                    $valorCalculadoAnterior = $result;

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['caderneta'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices IGPDI
     *
     * @return string
     */
    public function indiceIgpdi()
    {
        $anoMesIndice = [];
        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_igpdi);
        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2024) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2024 && $key2 == 1) {
                        $valorCalculadoAnterior = 15.960200;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['igpdi'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices IGPM
     *
     * @return string
     */
    public function indiceIgpm()
    {
        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_igpm);
        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2024) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2024 && $key2 == 1) {
                        $valorCalculadoAnterior = 11336.545157;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] = $key.';'.$key2.';'.str_replace(',', '', number_format($valorCalculadoAnterior, 6));
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.str_replace(',', '', number_format($valorCalculadoAnterior, 6));
        $resultados[] = $key.';'.($key2+2).';'.str_replace(',', '', number_format($valorCalculadoAnterior, 6));

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['igpm'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices INPC
     *
     * @return string
     */
    public function indiceInpc()
    {
        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_inpc);
        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2024) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2024 && $key2 == 1) {
                        $valorCalculadoAnterior = 450.979932;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['inpc'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices IPCA
     *
     * @return string
     */
    public function indiceIpca()
    {

        $response = Http::get(parent::url_ipca);
        $data = $response->json();
        $anoMesIndice = [];

        foreach ($data as $value) {
            $data_indice = explode("/", $value['data']);
            $anoMesIndice[$data_indice[2]][intval($data_indice[1])] = str_replace('.', ',', $value['valor']);
        }

        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2024) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2024 && $key2 == 1) {
                        $valorCalculadoAnterior = 2.628844;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['ipca'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices SELIC
     *
     * @return string
     */
    public function indiceSelic()
    {
        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_selic);
        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2024) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2024 && $key2 == 1) {
                        $valorCalculadoAnterior = 4.725548;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['selic'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices IPC
     *
     * @return string
     */
    public function indiceIpcFipe()
    {
        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_ipc_fipe);
        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2024) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2024 && $key2 == 1) {
                        $valorCalculadoAnterior = 0.387400;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['ipc'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices IPC FGV
     *
     * @return string
     */
    public function indiceIpcFgv()
    {
        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_ipc_fgv);
        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2024) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2024 && $key2 == 1) {
                        $valorCalculadoAnterior = 0.527474;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['ipcfgv'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices TR
     *
     * @return string
     */
    public function indiceTr()
    {
        $anoMesIndice = $this->indicesService->getDataEcalculos(parent::url_tr);
        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2024) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2024 && $key2 == 1) {
                        $valorCalculadoAnterior = 0.790909;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['tr'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices TJMG
     *
     * @return string
     */
    public function indiceTjmg()
    {
        $crawler = $this::getCrawler(parent::url_tjmg);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = $this->indicesService->getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $return = false;
        foreach ($anoMesIndice as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if ($key.$key2 == date('Y').intval(date('m'))) {
                    $return = true;
                }
            }
        }

        if (!$return) {
            $anoMesIndice = self::indiceTjmgRedundancia();
        }

        foreach ($anoMesIndice as $key => $value) {
            if (intval($key) >= 2000) {
                foreach ($value as $key2 => $value2) {
                    $resultados[] = $key.';'.$key2.';'.number_format((1/str_replace(',', '.', str_replace('.', '', $value2))),6);
                }
            }
        }
        $resultados[] = $key.';'.($key2+1).';'.number_format((1/str_replace(',', '.', str_replace('.', '', $value2))),6);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['tjmg'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    /**
     * Busca os índices TJMG
     *
     * @return string
     */
    public function indiceTjmgRedundancia()
    {
        $client = new Client();
        $response = $client->request('GET', parent::url_tjmg_alternativo);
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);
        $anoMesIndice = [];

        $arrayIndices = $crawler->filter('td')->each(function (Crawler $cell, $i) {
            $txt = $cell->text();
            if (strlen($txt) > 15) {
                return false;
            }
            return $txt;
        });

        $filteredArray = array_filter($arrayIndices, function ($value) {
            return $value !== false && $value !== '';
        });

        $arrayIndices = [];
        $anoMesIndice = [];
        $buscaQual = true;
        $idKey = 0;
        foreach ($filteredArray as $key => $filtered) {
            if ($buscaQual) {
                $data = explode("/", $filtered);

                $arrayIndices[$idKey][] = $data[1];
                $arrayIndices[$idKey][] = parent::mes_numero[$data[0]];
            } else {
                $arrayIndices[$idKey][] = $filtered;
                $idKey++;
            }

            $buscaQual = !$buscaQual;
        }
        foreach ($arrayIndices as $value) {
            $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
        }

        return $anoMesIndice;
    }

    /**
     * Busca os índices CUB-SP (Sinduscon)
     *
     * @return string
     */
    public function indiceCubsp()
    {
        $anoMesIndice = $this->indicesService->getDataEcalculos(parent::url_cubsp);
        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2022) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2022 && $key2 == 1) {
                        $valorCalculadoAnterior = 0.311559112052;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['cubsp'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    public function indicesDisponiveis()
    {
        $resultados['tjsp'] = self::validaSeTemMesAtual(self::indiceTjsp());
        $resultados['ortn'] = self::validaSeTemMesAtual(self::indiceOrtn());
        // $resultados['ufir'] = self::validaSeTemMesAtual(self::indiceUfir());
        $resultados['caderneta'] = self::validaSeTemMesAtual(self::indiceCadernetaPoupanca());
        $resultados['igpdi'] = self::validaSeTemMesAtual(self::indiceIgpdi());
        $resultados['igpm'] = self::validaSeTemMesAtual(self::indiceIgpm());
        $resultados['inpc'] = self::validaSeTemMesAtual(self::indiceInpc());
        $resultados['ipca'] = self::validaSeTemMesAtual(self::indiceIpca());
        $resultados['selic'] = self::validaSeTemMesAtual(self::indiceSelic());
        $resultados['ipc'] = self::validaSeTemMesAtual(self::indiceIpcFipe());
        $resultados['ipcfgv'] = self::validaSeTemMesAtual(self::indiceIpcFgv());
        $resultados['tr'] = self::validaSeTemMesAtual(self::indiceTr());
        $resultados['tjmg'] = self::validaSeTemMesAtual(self::indiceTjmg());
        $resultados['cubsp'] = self::validaSeTemMesAtual(self::indiceCubsp());

        $_SESSION['totais'] = $resultados;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    public function validaSeTemMesAtual($indices)
    {
        $indices = json_decode($indices, true);
        foreach ($indices as $key => $values) {
            if (date('Y') == $values[0] && date('m') == $values[1]) {
                return 'true';
            }
        }

        return 'false';

    }
}
