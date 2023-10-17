<?php

namespace App\Services;

class IndicesService
{

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
