<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

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
    const url_inpc = "https://debit.com.br/tabelas/tabela-completa.php?indice=inpc";
    // const url_ipca = "https://debit.com.br/tabelas/ipca-indice-nacional-de-precos-ao-consumidor-amplo.php"; #posição é diferente
    const url_ipca = "https://www.ecalculos.com.br/utilitarios/ipca-ibge.php";
    const url_selic = "https://debit.com.br/tabelas/tabela-completa.php?indice=selic";
    const url_ipc_fipe = "https://debit.com.br/tabelas/tabela-completa.php?indice=ipc_fipe";
    const url_tr = "https://debit.com.br/tabelas/tabela-completa.php?indice=tr";
    const url_tjmg = "https://debit.com.br/tabelas/tabela-completa.php?indice=tjmg";
    
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
     * Define a formatação do retorno
     *
     * @param array $anoMesIndice
     * @return string
     */
    private function tipoRetorno($anoMesIndice)
    {
        return json_encode($anoMesIndice);
        // return $anoMesIndice;
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
                    $resultados[] = $key.';'.$key2.';'.str_replace(',', '.', str_replace('.', '', $value2));
                }
            }
        }

        return view('welcome')->with('resultados', $resultados)->with('titulo', 'TJSP');
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
                    $resultados[] = $key.';'.$key2.';'.str_replace(',', '.', str_replace('.', '', $value2));
                }
            }
        }

        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'ORTN');
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
                    $resultados[] = $key.';'.$key2.';'.str_replace(',', '.', str_replace('.', '', $value2));
                }
            }
        }

        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'UFRI');
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

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }

        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'CADERNETA POUPANÇA');
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

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);

        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'IGPDI');
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

                    $resultados[] = $key.';'.$key2.';'.str_replace(',', '', number_format($valorCalculadoAnterior, 6));
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.str_replace(',', '', number_format($valorCalculadoAnterior, 6));

        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'IGPM');
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

                    $resultados[] = $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);

        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'INPC');
    }

    /**
     * Busca os índices IPCA
     *
     * @return string
     */
    public function indiceIpca()
    {
        // dd("Ainda em implementação");
        $crawler = $this::getCrawler(self::url_ipca);
        $anoMesIndice = [];
        

        $resultados = [];
        for ($j=1; $j <= 12; $j++) { 
            $i = 1;
            for ($i=1; $i <= 12; $i++) { 
                $filtro = "body > div.container-fluid > div:nth-child(3) > div > table > tbody > tr > td > div:nth-child($j) > table > tbody > tr:nth-child(1) > td:nth-child($i)";
                $arrayIndices = self::getDataIndiceIpca($crawler, $filtro);
                foreach ($arrayIndices as $value) {
                    $anoMesIndice[$value[0]] = "texto";
                }
            }
        }
        
        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'IPCA');
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

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);

        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'SELIC');
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

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);

        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'IPC FIPE');
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

                    $resultados[] =  $key.';'.$key2.';'.number_format($valorCalculadoAnterior, 6);
                }
            }
        }
        $valorCalculadoAnterior = $valorCalculadoAnterior + (($valorCalculadoAnterior * $valorAnterior) / 100);
        $resultados[] = $key.';'.($key2+1).';'.number_format($valorCalculadoAnterior, 6);

        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'TR');
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
                    $resultados[] = $key.';'.$key2.';'.number_format((1/str_replace(',', '.', str_replace('.', '', $value2))),6);
                }
            }
        }

        // foreach ($resultados as $key => $value) {
        //     echo $value."<br>";
        // }
        // die;
        return view('welcome')->with('resultados', $resultados)->with('titulo', 'TJMG');
    }
}
