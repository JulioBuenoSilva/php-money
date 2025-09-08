<?php 
    function nomeMes($numeroMes) {
        $meses = [
            1 => 'JANEIRO',
            2 => 'FEVEREIRO',
            3 => 'MARÇO',
            4 => 'ABRIL',
            5 => 'MAIO',
            6 => 'JUNHO',
            7 => 'JULHO',
            8 => 'AGOSTO',
            9 => 'SETEMBRO',
            10 => 'OUTUBRO',
            11 => 'NOVEMBRO',
            12 => 'DEZEMBRO'
        ];
        return $meses[$numeroMes] ?? 'Mês inválido';
    }

    // converte data de formato americano para formato brasileiro
    // se o segundo parametro for true tambem mostra a hora, minuto e segundo
    function toDataBR($data, $mostrarHora = false) {

        return $mostrarHora ? date('d/m/Y H:i:s', strtoTime($data)) : date('d/m/Y', strtoTime($data));
    }

    // converte data de formato brasileiro para formato americano
    function toDataEUA($data) {
        $partes = explode('/', $data);
        if (count($partes) === 3) {
            return "{$partes[2]}-{$partes[1]}-{$partes[0]}";
        }
        return null;
    }

    // retorna um conjunto de anos entre o ano atual e o primeiro registrado
    function comboAnos(array $params) {
        $anoInicial = $params['anoInicial'];
        $anoFinal = date("Y");

        $result = [];
        while ($anoInicial >= $anoFinal) {
            $result += [
                $anoInicial => $anoInicial
            ];
            $anoInicial++;
        }
        return $result;
    }
?>