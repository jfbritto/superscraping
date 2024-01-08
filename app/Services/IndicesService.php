<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class IndicesService
{

    const mes_numero = [
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

    /**
     * Trata o texto do endpoint, retornando no padrão desejado
     *
     * @param object $crawler
     * @param string $filtro
     * @return array
     */
    public function getDataIndice($crawler, $filtro): array
    {

        return $crawler->filter($filtro)->each(function ($node) {
            $texto = trim($node->text());

            // Obtém a primeira parte da string contendo a data
            $data = substr($texto, 0, 7); // 8 é o comprimento de "mes/ano"

            // Obtém a segunda parte da string contendo o indice
            $indice = str_replace($data, "", $texto);

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
     * @param string $url
     * @return array
     */
    public function getDataEcalculos($url): array
    {

        $client = new Client();
        $response = $client->request('GET', $url);
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
            return $value !== false && $value !== '' && strpos($value, "\u{A0}") === false;
        });

        $arrayIndices = [];
        $anoMesIndice = [];
        $buscaQual = true;
        $idKey = 0;

        foreach ($filteredArray as $key => $filtered) {

            if ($buscaQual) {
                $data = explode("/", $filtered);
                $arrayIndices[$idKey][] = $data[1];
                $arrayIndices[$idKey][] = self::mes_numero[$data[0]];
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
     * Trata o texto do endpoint, retornando no padrão desejado
     *
     * @param string $url
     * @return array
     */
    public function getDataEcalculos2($url): array
    {

        $client = new Client();
        $response = $client->request('GET', $url);
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);
        $anoMesIndice = [];

        $arrayIndices = $crawler->filter('tr')->each(function (Crawler $row, $i) {
            // Utilizar XPath para selecionar as duas primeiras células (colunas) de cada linha
            $cells = $row->filterXPath('//td[position() <= 2]');

            // Verificar se há pelo menos duas células na linha
            if ($cells->count() < 2) {
                return null; // Ou outra ação adequada, dependendo dos requisitos
            }

            // Obter os textos das duas primeiras células e retornar como um array
            $rowData = [];
            $cells->each(function (Crawler $cell) use (&$rowData) {
                $rowData[] = $cell->text();
            });

            return $rowData;
        });

        // Remover entradas nulas (linhas que não têm duas células)
        $arrayIndices = array_filter($arrayIndices);

        array_shift($arrayIndices);

        $filteredArrayAr = array_filter($arrayIndices, function ($value) {
            return $value !== false && $value !== '';
        });

        foreach ($filteredArrayAr as $value) {
            foreach ($value as $value2) {
                $filteredArray[] = $value2;
            }
        }

        $arrayIndices = [];
        $anoMesIndice = [];
        $buscaQual = true;
        $idKey = 0;

        foreach ($filteredArray as $key => $filtered) {

            if ($buscaQual) {
                $data = explode("/", $filtered);
                $arrayIndices[$idKey][] = $data[1];
                $arrayIndices[$idKey][] = self::mes_numero[$data[0]];
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
     * Trata o texto do endpoint, retornando no padrão desejado
     *
     * @param object $crawler
     * @param string $filtro
     * @return array
     */
    public function getDataIndiceUfir($crawler, $filtro): array
    {

        return $crawler->filter($filtro)->each(function ($node) {
            $texto = trim($node->text());

            // Obtém a primeira parte da string contendo a data
            $data = substr($texto, 0, 10); // 8 é o comprimento de "mes/ano"

            // Obtém a segunda parte da string contendo o indice
            $indice = str_replace($data, "", $texto);

            // separa ano do mês
            $data = explode("/", $data);
            $dia = $data[0];
            $mes = $data[1];
            $ano = $data[2];

            return [$ano, $mes, $indice];
        });

    }


}
