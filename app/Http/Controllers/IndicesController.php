<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;
use App\Services\IndicesService;

class IndicesController extends AbstractController
{

    private $indicesService;

    public function __construct(IndicesService $indicesService)
    {
        $this->indicesService = $indicesService;
    }

    public function index()
    {
        $resultados[] = 'Basta rodar as permissões abaixo e clicar no botão "Atualizar tabelas cloud"';
        $resultados[] = '';
        $resultados[] = 'sudo chmod -R 777 public/atualizacaomonetaria/*';
        $resultados[] = 'sudo chmod -R 777 var/tabelas/*';

        return view('welcome')
                ->with('resultados', $resultados)
                ->with('titulo', 'ÍNDICES MONETÁRIOS CALCULADOS');
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

        $anoMesIndice = [];

        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_igpdi);

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

        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_igpm);

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

        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_inpc);

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

        $response = Http::get(parent::url_ipca);
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

        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_selic);

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

        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_ipc_fipe);

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

        $anoMesIndice = $this->indicesService->getDataEcalculos2(parent::url_ipc_fgv);

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

        $anoMesIndice = $this->indicesService->getDataEcalculos(parent::url_tr);

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
        if (isset($_SESSION['cubsp'])) {
            return view('welcome')->with('resultados', $_SESSION['cubsp'])->with('titulo', 'CUBSP');
        }

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

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'CUBSP');
    }
}
