<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class IndicesController extends Controller
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
    const url_tjmg_alternativo = "https://www.ecalculos.com.br/utilitarios/justica-estadual.php";

    public function __construct()
    {
        session_start();
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
     * Trata o texto do endpoint, retornando no padrão desejado
     *
     * @param object $crawler
     * @param string $filtro
     * @return array
     */
    private function getDataIndiceIpca($crawler, $filtro): array
    {
        return $crawler->filter($filtro)->each(function ($node) {
            $texto = trim($node->text());

            return [$texto];
        });

    }

    /**
     * Busca os índices TJSP
     *
     * @return string
     */
    public function indiceTjsp()
    {
        if (isset($_SESSION['tjsp'])) {
            return view('welcome')->with('resultados', $_SESSION['tjsp'])->with('titulo', 'TJSP');
        }

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
                    $resultados[] = $key.';'.$key2.';'.str_replace(',', '.', str_replace('.', '', $value2));
                }
            }
        }
        $resultados[] = $key.';'.($key2+1).';'.str_replace(',', '.', str_replace('.', '', $value2));

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'TJSP');
    }

    /**
     * Busca os índices ORTN
     *
     * @return string
     */
    public function indiceOrtn()
    {
        if (isset($_SESSION['ortn'])) {
            return view('welcome')->with('resultados', $_SESSION['ortn'])->with('titulo', 'ORTN');
        }

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
                    $resultados[] = $key.';'.$key2.';'.str_replace(',', '.', str_replace('.', '', $value2)).'00';
                }
            }
        }
        $resultados[] = $key.';'.($key2+1).';'.str_replace(',', '.', str_replace('.', '', $value2)).'00';

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'ORTN');
    }

    /**
     * Busca os índices ufri
     *
     * @return string
     */
    public function indiceUfir()
    {
        if (isset($_SESSION['ufir'])) {
            return view('welcome')->with('resultados', $_SESSION['ufir'])->with('titulo', 'UFIR');
        }

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
                    $resultados[] = $key.';'.$key2.';'.str_replace(',', '.', str_replace('.', '', $value2)).'00';
                }
            }
        }
        $resultados[] = $key.';'.($key2+1).';'.str_replace(',', '.', str_replace('.', '', $value2)).'00';

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'UFIR');
    }

    /**
     * Busca os índices CADERNETA POUPANÇA
     *
     * @return string
     */
    public function indiceCadernetaPoupanca()
    {
        if (isset($_SESSION['caderneta'])) {
            return view('welcome')->with('resultados', $_SESSION['caderneta'])->with('titulo', 'POUPANÇA');
        }

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

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'POUPANÇA');
    }

    /**
     * Busca os índices IGPDI
     *
     * @return string
     */
    public function indiceIgpdi()
    {
        if (isset($_SESSION['igpdi'])) {
            return view('welcome')->with('resultados', $_SESSION['igpdi'])->with('titulo', 'IGPDI');
        }

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

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'IGPDI');
    }

    /**
     * Busca os índices IGPM
     *
     * @return string
     */
    public function indiceIgpm()
    {
        if (isset($_SESSION['igpm'])) {
            return view('welcome')->with('resultados', $_SESSION['igpm'])->with('titulo', 'IGPM');
        }

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

                    $resultados[] = $key.';'.$key2.';'.str_replace(',', '', number_format($valorCalculadoAnterior, 6));
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.str_replace(',', '', number_format($valorCalculadoAnterior, 6));
        $resultados[] = $key.';'.($key2+2).';'.str_replace(',', '', number_format($valorCalculadoAnterior, 6));

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'IGPM');
    }

    /**
     * Busca os índices INPC
     *
     * @return string
     */
    public function indiceInpc()
    {
        if (isset($_SESSION['inpc'])) {
            return view('welcome')->with('resultados', $_SESSION['inpc'])->with('titulo', 'INPC');
        }

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

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'INPC');
    }

    /**
     * Busca os índices IPCA
     *
     * @return string
     */
    public function indiceIpca()
    {
        if (isset($_SESSION['ipca'])) {
            return view('welcome')->with('resultados', $_SESSION['ipca'])->with('titulo', 'IPCA');
        }

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

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'IPCA');
    }

    /**
     * Busca os índices SELIC
     *
     * @return string
     */
    public function indiceSelic()
    {
        if (isset($_SESSION['selic'])) {
            return view('welcome')->with('resultados', $_SESSION['selic'])->with('titulo', 'SELIC');
        }

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

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'SELIC');
    }

    /**
     * Busca os índices IPC
     *
     * @return string
     */
    public function indiceIpcFipe()
    {
        if (isset($_SESSION['ipc'])) {
            return view('welcome')->with('resultados', $_SESSION['ipc'])->with('titulo', 'IPC FIPE');
        }

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

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'IPC FIPE');
    }

    /**
     * Busca os índices IPC FGV
     *
     * @return string
     */
    public function indiceIpcFgv()
    {
        if (isset($_SESSION['ipcfgv'])) {
            return view('welcome')->with('resultados', $_SESSION['ipcfgv'])->with('titulo', 'IPC FGV');
        }

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

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'IPC FGV');
    }

    /**
     * Busca os índices TR
     *
     * @return string
     */
    public function indiceTr()
    {
        if (isset($_SESSION['tr'])) {
            return view('welcome')->with('resultados', $_SESSION['tr'])->with('titulo', 'TR');
        }

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

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);
        $resultados[] = $key.';'.($key2+2).';'.number_format($valorCalculadoAnterior, 6);

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'TR');
    }

    /**
     * Busca os índices TJMG
     *
     * @return string
     */
    public function indiceTjmg()
    {
        if (isset($_SESSION['tjmg'])) {
            return view('welcome')->with('resultados', $_SESSION['tjmg'])->with('titulo', 'TJMG');
        }

        $crawler = $this::getCrawler(self::url_tjmg);
        $anoMesIndice = [];

        for ($i=1; $i <= 12; $i++) {
            $filtro = "#preview6 > div > table > tbody > tr:nth-child({$i})";
            $arrayIndices = self::getDataIndice($crawler, $filtro);
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

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'TJMG');
    }

    /**
     * Busca os índices TJMG
     *
     * @return string
     */
    public function indiceTjmgRedundancia()
    {
        $client = new Client();
        $response = $client->request('GET', self::url_tjmg_alternativo);
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);
        $anoMesIndice = [];

        $mes_numero = [
            'janeiro' => '01',
            'fevereiro' => '02',
            'março' => '03',
            'abril' => '04',
            'maio' => '05',
            'junho' => '06',
            'julho' => '07',
            'agosto' => '08',
            'setembro' => '09',
            'outubro' => '10',
            'novembro' => '11',
            'dezembro' => '12'
        ];

        $arrayIndices = $crawler->filter('td')->each(function (Crawler $cell, $i) {
            $txt = $cell->text();
            if (strlen($txt) > 15) {
                return false;
            }
            return $txt;
        });

        $filteredArray = array_filter($arrayIndices, function ($value) {
            return $value !== false;
        });

        $arrayIndices = [];
        $anoMesIndice = [];
        $buscaQual = true;
        $idKey = 0;
        foreach ($filteredArray as $key => $filtered) {
            if ($buscaQual) {
                $data = explode("/", $filtered);

                $arrayIndices[$idKey][] = $data[1];
                $arrayIndices[$idKey][] = $mes_numero[$data[0]];
            } else {
                $arrayIndices[$idKey][] = $filtered;
                $idKey++;
            }

            $buscaQual = !$buscaQual;
        }
        foreach ($arrayIndices as $value) {
            $anoMesIndice[$value[0]][intval($value[1])] = $value[2];
        }

        // foreach ($anoMesIndice as $key => $value) {
        //     if (intval($key) >= 2000) {
        //         foreach ($value as $key2 => $value2) {
        //             $resultados[] = $key.';'.$key2.';'.number_format((1/str_replace(',', '.', str_replace('.', '', $value2))),6);
        //         }
        //     }
        // }
        // $resultados[] = $key.';'.($key2+1).';'.number_format((1/str_replace(',', '.', str_replace('.', '', $value2))),6);
        return $anoMesIndice;
        // return view('welcome')->with('resultados', $resultados)->with('titulo', 'TJMG');
    }
}
