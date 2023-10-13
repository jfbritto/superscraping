<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class AbstractController extends Controller
{
    /**
     * Define as urls a qual iremos fazer o scraping
     */
    const url_tjsp = "https://debit.com.br/tabelas/tribunal-justica-sp-depre"; //https://debit.com.br/tabelas/tabela-completa.php?indice=aasp
    const url_ortn = "https://www.debit.com.br/tabelas/btn-bonus-do-tesouro-nacional"; //https://debit.com.br/tabelas/tabela-completa.php?indice=btn
    const url_ufir = "https://www.debit.com.br/tabelas/ufir"; //https://debit.com.br/tabelas/tabela-completa.php?indice=ufir
    const url_caderneta_poupanca = "https://www.debit.com.br/tabelas/poupanca"; //https://debit.com.br/tabelas/tabela-completa.php?indice=poupanca
    const url_igpdi = "https://www.debit.com.br/tabelas/igp-fgv-indice-geral-de-precos"; //https://debit.com.br/tabelas/tabela-completa.php?indice=igp
    const url_igpm = "https://www.debit.com.br/tabelas/igpm-fgv-indice-geral-de-precos-mercado"; //https://debit.com.br/tabelas/tabela-completa.php?indice=igpm
    const url_inpc = "https://www.debit.com.br/tabelas/inpc-indice-nacional-de-precos-ao-consumidor"; //https://debit.com.br/tabelas/tabela-completa.php?indice=inpc
    const url_ipca = "https://api.bcb.gov.br/dados/serie/bcdata.sgs.433/dados?formato=json";
    const url_selic = "https://www.debit.com.br/tabelas/selic"; //https://debit.com.br/tabelas/tabela-completa.php?indice=selic
    const url_ipc_fipe = "https://debit.com.br/tabelas/ipc-indice-de-precos-ao-consumidor-fipe"; //https://debit.com.br/tabelas/tabela-completa.php?indice=ipc_fipe
    const url_ipc_fgv = "https://www.debit.com.br/tabelas/ipc-indice-de-precos-ao-consumidor-fgv"; //https://debit.com.br/tabelas/tabela-completa.php?indice=ipc_fgv
    const url_tr = "https://www.debit.com.br/tabelas/tr-bacen"; //https://debit.com.br/tabelas/tabela-completa.php?indice=tr

    const url_tjmg = "https://debit.com.br/tabelas/tribunal-justica-mg"; //https://debit.com.br/tabelas/tabela-completa.php?indice=tjmg
    const url_tjmg_alternativo = "https://www.ecalculos.com.br/utilitarios/justica-estadual.php";

    public function __construct()
    {
        $timeout = 300; // 5 minutos
        session_set_cookie_params($timeout);
        ini_set('session.gc_maxlifetime', $timeout);
        session_start();
    }

    public static function getCrawler($url)
    {
        $client = new Client();
        $response = $client->request('GET', $url);
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);

        return $crawler;
    }

}
