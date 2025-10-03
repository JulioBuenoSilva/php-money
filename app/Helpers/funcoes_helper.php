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
    function toDataBr($dataUsa, $mostrarHora = false) {
        if (empty($dataUsa)) {
            return null;
        }

        // Se vier no formato completo: Y-m-d H:i:s
        if ($mostrarHora && preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})(?::(\d{2}))?$/', $dataUsa, $m)) {
            $hora = $m[4] . ':' . $m[5];
            if (!empty($m[6])) {
                $hora .= ':' . $m[6];
            }
            return $m[3] . '/' . $m[2] . '/' . $m[1] . ' ' . $hora;
        }

        // Só data: Y-m-d
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $dataUsa, $m)) {
            return $m[3] . '/' . $m[2] . '/' . $m[1];
        }

        return null;
    }


    // converte data de formato brasileiro para formato americano
    function toDataEUA($dataBr) {
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dataBr, $m)) {
            return $m[3] . '-' . $m[2] . '-' . $m[1];
        }
        return null; // inválido
    }


    // retorna um conjunto de anos entre o ano atual e o primeiro registrado
    function comboAnos(array $params) {
        $anoInicial = $params['anoInicial'];
        $anoFinal = date("Y");

        $result = [];
        while ($anoInicial <= $anoFinal) {
            $result += [
                $anoInicial => $anoInicial
            ];
            $anoInicial++;
        }
        return $result;
    }
?>