<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class IndicesJsonController extends Controller
{
    /**
     * Define as urls a qual iremos fazer o scraping
     */
    const url_tjsp = "https://debit.com.br/tabelas/tabela-completa.php?indice=aasp";
    const url_ortn = "https://debit.com.br/tabelas/tabela-completa.php?indice=btn";
    const url_ufir = "https://debit.com.br/tabelas/tabela-completa.php?indice=ufir";
    const url_caderneta_poupanca = "https://debit.com.br/tabelas/tabela-completa.php?indice=poupanca";
    const url_igpdi = "https://debit.com.br/tabelas/tabela-completa.php?indice=igp";
    const url_igpm = "https://debit.com.br/tabelas/tabela-completa.php?indice=igpm";
    const url_inpc = "https://debit.com.br/tabelas/tabela-completa.php?indice=inpc"; //https://api.bcb.gov.br/dados/serie/bcdata.sgs.188/dados?formato=json
    const url_ipca = "https://api.bcb.gov.br/dados/serie/bcdata.sgs.433/dados?formato=json";
    const url_selic = "https://debit.com.br/tabelas/tabela-completa.php?indice=selic";
    const url_ipc_fipe = "https://debit.com.br/tabelas/tabela-completa.php?indice=ipc_fipe";
    const url_ipc_fgv = "https://debit.com.br/tabelas/tabela-completa.php?indice=ipc_fgv";
    const url_tr = "https://debit.com.br/tabelas/tabela-completa.php?indice=tr";
    const url_tjmg = "https://debit.com.br/tabelas/tabela-completa.php?indice=tjmg";

    public function __construct()
    {
        $timeout = 300; // 5 minutos
        session_set_cookie_params($timeout);
        ini_set('session.gc_maxlifetime', $timeout);
        session_start();
    }

    public function ajustar()
    {

        $iteracoes = [
            ['indice' => 'tabelaAtualizacaoMonetaria', 'valor' => '2022;1;84.807227', 'funcao' => 'indiceTjsp'],
            ['indice' => 'ORTNOTN', 'valor' => '2022;1;1.719100', 'funcao' => 'indiceOrtn'],
            ['indice' => 'UFIR', 'valor' => '2022;1;4.091500', 'funcao' => 'indiceUfir'],
            ['indice' => 'CADERNETAPOUPANCA', 'valor' => '2022;1;37.475525', 'funcao' => 'indiceCadernetaPoupanca'],
            ['indice' => 'IGPDI', 'valor' => '2022;1;15.814744', 'funcao' => 'indiceIgpdi'],
            ['indice' => 'IGPM', 'valor' => '2022;1;11184.810389', 'funcao' => 'indiceIgpm'],
            ['indice' => 'INPC', 'valor' => '2022;1;412.764786', 'funcao' => 'indiceInpc'],
            ['indice' => 'IPCA', 'valor' => '2022;1;1.479689', 'funcao' => 'indiceIpca'],
            ['indice' => 'SELIC', 'valor' => '2022;1;3.753379', 'funcao' => 'indiceSelic'],
            ['indice' => 'IPC', 'valor' => '2022;1;0.351271', 'funcao' => 'indiceIpcFipe'],
            ['indice' => 'IPCFGV', 'valor' => '2022;1;0.489865', 'funcao' => 'indiceIpcFgv'],
            ['indice' => 'TR', 'valor' => '2022;1;0.765281', 'funcao' => 'indiceTr'],

            ['indice' => 'TJMG', 'valor' => '2000;1;0.230677', 'funcao' => 'indiceTjmg'],
            ['indice' => 'TJDF', 'valor' => '2000;1;0.230677', 'funcao' => 'indiceTjmg'],
            ['indice' => 'TJES', 'valor' => '2000;1;0.230677', 'funcao' => 'indiceTjmg'],
            ['indice' => 'TJRO', 'valor' => '2000;1;0.230677', 'funcao' => 'indiceTjmg'],
            ['indice' => 'ENCOGE', 'valor' => '2000;1;0.230677', 'funcao' => 'indiceTjmg'],

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
                $newData_array[] = ["$value[0];$value[1];$value[2]"];
            }
            $novo_mes = $value[1]+1;
            $newData_array[] = ["$value[0];$novo_mes;$value[2]"];

            // Abre o arquivo para escrita
            $file = fopen($filename, 'w');
            $fileDuplicado = fopen($filenameDuplicado, 'w');

            if ($file) {
                // Escreve os dados antigos de volta no arquivo
                foreach ($data as $row) {
                    fputcsv($file, $row);
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
                foreach ($data as $row) {
                    fputcsv($fileDuplicado, $row);
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

    public function index()
    {
        $resultados[] = 'Basta copiar os resultados e colar em seus respectivos arquivos .csv que ficam nos diretórios:';
        $resultados[] = '';
        $resultados[] = '/home/cloud/var/tabelas/';
        $resultados[] = '/home/cloud/public/atualizacaomonetaria/';

        return view('welcome')
                ->with('resultados', $resultados)
                ->with('titulo', 'ÍNDICES MONETÁRIOS CALCULADOS');
    }

    public static function getCrawler($url)
    {
        $client = new Client();
        $response = $client->request('GET', $url);
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);

        return $crawler;
    }

    /**
     * Trata o texto do endpoint, retornando no padrão desejado
     *
     * @param object $crawler
     * @param string $filtro
     * @return array
     */
    private function getDataIndice($crawler, $filtro): array
    {

        return $crawler->filter($filtro)->each(function ($node) {
            $texto = trim($node->text());

            // separando a data do indice
            $data_indice = explode(" ", $texto);

            // pegando data
            $data = $data_indice[0];

            // pegando indice
            $indice = $data_indice[1];

            // separa ano do mês
            $data = explode("/", $data);
            $mes = $data[0];
            $ano = $data[1];

            return [$ano, $mes, $indice];
        });

    }

    /**
     * Busca os índices TJSP
     *
     * @return string
     */
    public function indiceTjsp()
    {
        $crawler = $this::getCrawler(self::url_tjsp);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        foreach ($anoMesIndice as $key => $value) {
            if (intval($key) >= 2022) {
                foreach ($value as $key2 => $value2) {
                    $resultados[] = [$key, $key2, str_replace(',', '.', str_replace('.', '', $value2))];
                }
            }
        }

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
        $crawler = $this::getCrawler(self::url_ortn);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
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
        $crawler = $this::getCrawler(self::url_ufir);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
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
        $crawler = $this::getCrawler(self::url_caderneta_poupanca);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        $valorCalculadoAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2022) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2022 && $key2 == 1) {
                        $valorCalculadoAnterior = 37.266534;
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $value2) / 100);

                    $valorCalculadoAnterior = $result;

                    $resultados[] = [$key, $key2, number_format($valorCalculadoAnterior, 6)];
                }
            }
        }

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
        $crawler = $this::getCrawler(self::url_igpdi);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2022) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2022 && $key2 == 1) {
                        $valorCalculadoAnterior = 15.619500;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] = [$key, $key2, number_format($valorCalculadoAnterior, 6)];
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = [$key, ($key2+1), number_format($valorCalculadoAnterior, 6)];

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
        $crawler = $this::getCrawler(self::url_igpm);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }
        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2022) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2022 && $key2 == 1) {
                        $valorCalculadoAnterior = 11088.341815;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] = [$key, $key2, str_replace(',', '', number_format($valorCalculadoAnterior, 6))];
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = [$key, ($key2+1), str_replace(',', '', number_format($valorCalculadoAnterior, 6))];

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
        $crawler = $this::getCrawler(self::url_inpc);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2022) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2022 && $key2 == 1) {
                        $valorCalculadoAnterior = 409.773440;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] = [$key, $key2, number_format($valorCalculadoAnterior, 6)];
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = [$key, ($key2+1), number_format($valorCalculadoAnterior, 6)];

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

        $response = Http::get(self::url_ipca);
        $data = $response->json();
        $anoMesIndice = [];

        foreach ($data as $value) {
            $data_indice = explode("/", $value['data']);
            $anoMesIndice[$data_indice[2]][intval($data_indice[1])] = str_replace('.', ',', $value['valor']);
        }

        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2022) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2022 && $key2 == 1) {
                        $valorCalculadoAnterior = 1.468966;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] = [$key, $key2, number_format($valorCalculadoAnterior, 6)];
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = [$key, ($key2+1), number_format($valorCalculadoAnterior, 6)];

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
        $crawler = $this::getCrawler(self::url_selic);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2022) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2022 && $key2 == 1) {
                        $valorCalculadoAnterior = 3.724699;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] =  [$key, $key2, number_format($valorCalculadoAnterior, 6)];
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = [$key, ($key2+1), number_format($valorCalculadoAnterior, 6)];

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
        $crawler = $this::getCrawler(self::url_ipc_fipe);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2022) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2022 && $key2 == 1) {
                        $valorCalculadoAnterior = 0.349279822221;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] =  [$key, $key2, number_format($valorCalculadoAnterior, 6)];
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = [$key, ($key2+1), number_format($valorCalculadoAnterior, 6)];

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
        $crawler = $this::getCrawler(self::url_ipc_fgv);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2022) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2022 && $key2 == 1) {
                        $valorCalculadoAnterior = 0.487088970242;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] =  [$key, $key2, number_format($valorCalculadoAnterior, 6)];
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = [$key, ($key2+1), number_format($valorCalculadoAnterior, 6)];

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
        $crawler = $this::getCrawler(self::url_tr);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        $valorCalculadoAnterior = null;
        $valorAnterior = null;
        foreach ($anoMesIndice as $key => $value) {
            if ($key >= 2022) {
                foreach ($value as $key2 => $value2) {

                    $value2 = str_replace(',', '.', str_replace('.', '', $value2));

                    if ($key == 2022 && $key2 == 1) {
                        $valorCalculadoAnterior = 0.764908;

                        if (intval($key2) == 1) {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $anoMesIndice[intval($key)-1][12]));
                        } else {
                            $valorAnterior = str_replace(',', '.', str_replace('.', '', $value[$key2-1]));
                        }
                    }

                    $result = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);

                    $valorCalculadoAnterior = $result;
                    $valorAnterior = $value2;

                    $resultados[] =  [$key, $key2, number_format($valorCalculadoAnterior, 6)];
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = [$key, ($key2+1), number_format($valorCalculadoAnterior, 6)];

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
        $crawler = $this::getCrawler(self::url_tjmg);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
            foreach ($arrayIndices as $value) {
                $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
            }
        }

        $resultados = [];
        foreach ($anoMesIndice as $key => $value) {
            if (intval($key) >= 2000) {
                foreach ($value as $key2 => $value2) {
                    $resultados[] = [$key, $key2, number_format((1/str_replace(',', '.', str_replace('.', '', $value2))),6)];
                }
            }
        }

        foreach ($resultados as $key => $value) {
            $resultadosArray[] = $resultados[$key][0].';'.$resultados[$key][1].';'.$resultados[$key][2];
        }
        $resultadosArray[] = $resultados[$key][0].';'.($resultados[$key][1]+1).';'.$resultados[$key][2];
        $_SESSION['tjmg'] = $resultadosArray;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    public function indicesDisponiveis()
    {
        $resultados['tjsp'] = self::validaSeTemMesAtual(self::indiceTjsp());
        $resultados['ortn'] = self::validaSeTemMesAtual(self::indiceOrtn());
        $resultados['ufir'] = self::validaSeTemMesAtual(self::indiceUfir());
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

        $_SESSION['totais'] = $resultados;

        header('Content-Type: application/json');
        return json_encode($resultados);
    }

    public function validaSeTemMesAtual($indices)
    {
        $indices = json_decode($indices, true);
        foreach ($indices as $key => $values) {
            if (date('Y') == $values[0] && date('m') == $values[1]) {
                return true;
            }
        }

        return false;

    }
}
