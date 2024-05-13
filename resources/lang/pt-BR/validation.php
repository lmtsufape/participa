<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted' => ':Attribute deve ser aceito.',
    'active_url' => ':Attribute não é uma URL válida.',
    'after' => ':Attribute deve ser uma data depois de :date.',
    'after_time' => ':attribute deve ser em um horário depois do inicio.',
    'after_or_equal' => ':attribute deve ser uma data posterior ou igual a:date.',
    'alpha' => ':Attribute deve conter somente letras.',
    'alpha_dash' => ':Attribute deve conter letras, números e traços.',
    'alpha_num' => ':Attribute deve conter somente letras e números.',
    'array' => ':Attribute deve ser um array.',
    'before' => ':Attribute deve ser uma data antes de :date.',
    'between' => [
        'numeric' => ':Attribute deve estar entre :min e :max.',
        'file' => ':Attribute deve estar entre :min e :max kilobytes.',
        'string' => ':Attribute deve estar entre :min e :max caracteres.',
        'array' => ':Attribute deve ter entre :min e :max itens.',
    ],
    'boolean' => ':Attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação de :attribute não confere.',
    'date' => ':Attribute não é uma data válida.',
    'date_format' => ':Attribute não confere com o formato :format.',
    'different' => ':Attribute e :other devem ser diferentes.',
    'digits' => ':Attribute deve ter :digits dígitos.',
    'digits_between' => ':Attribute deve ter entre :min e :max dígitos.',
    'email' => ':Attribute deve ser um endereço de e-mail válido.',
    'exists' => 'O :attribute selecionado é inválido.',
    'filled' => ':Attribute é um campo obrigatório.',
    'gte' => [
        'numeric' => 'O campo :attribute deve ser maior ou igual a :value.',
        'file' => 'O campo :attribute deve ser maior ou igual a :value kilobytes.',
        'string' => 'O campo :attribute deve ser maior ou igual a :value caracteres.',
        'array' => 'O campo :attribute deve conter :value itens ou mais.',
    ],
    'image' => ':Attribute deve ser uma imagem.',
    'in' => ':Attribute é inválido.',
    'integer' => ':Attribute deve ser um inteiro.',
    'ip' => ':Attribute deve ser um endereço IP válido.',
    'json' => ':Attribute deve ser um JSON válido.',
    'lte' => [
        'numeric' => 'O campo :attribute deve ser menor ou igual a :value.',
        'file' => 'O campo :attribute deve ser menor ou igual a :value kilobytes.',
        'string' => 'O campo :attribute deve ser menor ou igual a :value caracteres.',
        'array' => 'O campo :attribute não deve conter mais que :value itens.',
    ],
    'max' => [
        'numeric' => ':Attribute não deve ser maior que :max.',
        'file' => ':Attribute não deve ter mais que :max kilobytes.',
        'string' => ':Attribute não deve ter mais que :max caracteres.',
        'array' => ':Attribute não deve ter mais que :max itens.',
    ],
    'mimes' => ':Attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'numeric' => ':Attribute deve ser no mínimo :min.',
        'file' => ':Attribute deve ter no mínimo :min kilobytes.',
        'string' => ':Attribute deve ter no mínimo :min caracteres.',
        'array' => ':Attribute deve ter no mínimo :min itens.',
    ],
    'not_in' => 'O :attribute selecionado é inválido.',
    'numeric' => ':Attribute deve ser um número.',
    'regex' => 'O formato de :attribute é inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_unless' => 'O :attribute é necessário a menos que :other esteja em :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values estão presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum destes estão presentes: :values.',
    'same' => ':Attribute e :other devem ser iguais.',
    'size' => [
        'numeric' => ':Attribute deve ser :size.',
        'file' => ':Attribute deve ter :size kilobytes.',
        'string' => ':Attribute deve ter :size caracteres.',
        'array' => ':Attribute deve conter :size itens.',
    ],
    'string' => ':Attribute deve ser uma string',
    'time' => ':attribute é um hora inválida',
    'timezone' => ':Attribute deve ser uma timezone válida.',
    'unique' => ':Attribute já está em uso.',
    'url' => 'O formato de :attribute é inválido.',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'dataInício.*' => [
            'required' => 'O campo data de início é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
        ],
        'dataDeFim.*' => [
            'required' => 'O campo data de fim é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'A data informada deve ser depois da data de início.',
        ],
        'disponibilidade.*' => [
            'required' => 'O campo disponibilidade é obrigatório.',
        ],
        'valorDesconto' => [
            'required' => 'O campo valor é obrigatório.',
        ],
        'inícioDesconto.*' => [
            'required' => 'O campo início é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
        ],
        'fimDesconto.*' => [
            'required' => 'O campo início é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'A data informada deve ser depois da data de início.',
        ],

        // Editar categoria participante
        'nome_*' => [
            'required' => 'O campo nome é obrigatório.',
        ],
        'valor_total_*' => [
            'required' => 'O campo valor da inscrição é obrigatório.',
        ],
        'inícioDesconto_*.*' => [
            'required' => 'O campo início é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
        ],
        'fimDesconto_*.*' => [
            'required' => 'O campo início é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'A data informada deve ser depois da data de início.',
        ],

        // Editar pacote
        'identificador_*' => [
            'required' => 'O campo identificador é obrigatório.',
        ],
        'valor_*' => [
            'required' => 'O campo valor do pacote é obrigatório.',
        ],
        'dataDeInício_*' => [
            'required' => 'O campo data de início é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
        ],
        'dataDeFim_*' => [
            'required' => 'O campo data de fim é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'A data informada deve ser depois da data de início.',
        ],
        'disponibilidade_*' => [
            'required' => 'O campo disponibilidade é obrigatório.',
        ],

        //Editar cupom de desconto
        'identificador_cupom_*' => [
            'required' => 'O campo identificador é obrigatório.',
        ],
        'quantidade_cupom_*' => [
            'required' => 'O campo disponibilidade é obrigatório.',
        ],
        'tipo_valor_cupom_*' => [
            'required' => 'O campo valor desconto é obrigatório.',
        ],
        'valor_cupom_*' => [
            'required' => 'O campo é obrigatório.',
        ],
        'início_cupom_*' => [
            'required' => 'O campo início é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
        ],
        'fim_cupom_*' => [
            'required' => 'O campo fim é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'A data informada deve ser depois do início.',
        ],
        'dataInicio' => [
            'after' => 'A data informada deve ser depois de ontem.',
        ],
        'areas' => [
            'required' => 'Escolha as áreas do revisor',
        ],
        'modalidades' => [
            'required' => 'Escolha as modalidades do revisor',
        ],
        'areasEditadas_*' => [
            'required' => 'Escolha as áreas do revisor',
        ],
        'modalidadesEditadas_*' => [
            'required' => 'Escolha as modalidades do revisor',
        ],
        'areaId' => [
            'required' => 'Selecione a área do evento a qual o trabalho pertence',
        ],
        // Editar modalidade
        'inicioSubmissao*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
        ],
        'fimSubmissao*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'Fim da submissão deve ser uma data depois do início da submissão.',
        ],
        'inicioRevisao*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'Início da Avaliação deve ser uma data depois do fim da submissão. Para alterar, selecione "Permitir avaliação durante o período de submissão"',
        ],
        'inícioRevisão*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'Início da Avaliação deve ser uma data depois do fim da submissão.',
        ],
        'fimRevisao*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'Fim da Avaliação deve ser uma data depois do início da submissão.',
        ],
        'fimRevisão*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'Fim da Avaliação deve ser uma data depois do início da submissão.',
        ],
        'inicioCorrecao*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'Início da correção deve ser uma data depois do fim da avaliação.',
            'required_with' => 'O campo início da correção é obrigatório quando fim da correção está presente.',
        ],
        'fimCorrecao*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'Fim da correção deve ser uma data depois do início da correção.',
            'required_with' => 'O campo fim da correção é obrigatório quando início da correção está presente.',
        ],
        'inicioValidacao*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'Início da validação deve ser uma data depois do fim da correção.',
            'required_with' => 'O campo início da validação é obrigatório quando fim da validação está presente.',
        ],
        'fimValidacao*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'Fim da validação deve ser uma data depois do início da validação.',
            'required_with' => 'O campo fim da validação é obrigatório quando início da validação está presente.',
        ],
        'inicioResultado*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
            'after' => 'Resultado deve ser uma data depois do fim da avaliação.',
        ],
        'inicioSubmissao*' => [
            'required' => 'O campo é obrigatório.',
            'date' => 'O campo deve ter uma data válida.',
        ],
        'arquivoRegras*' => [
            'file' => 'O campo arquivo regras deve ter um arquivo válido.',
            'mimes' => 'O arquivo deve ser do tipo PDF.',
            'max' => 'O arquivo deve ter no máximo 2mb.',
        ],
        'arquivoTemplates*' => [
            'file' => 'O campo arquivo template deve ter um arquivo válido.',
            'mimes' => 'O arquivo deve ser do tipo odt, ott, docx, doc, rtf, txt ou pdf.',
            'max' => 'O arquivo deve ter no máximo 2mb.',
        ],
        'area*' => [
            'required' => 'O campo área do trabalho é obrigatório.',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [
        'name' => 'nome',
        'email' => 'e-mail',
        'password' => 'senha',
        'cpf' => 'CPF',
        'instituicao' => 'instituição',
        'numero' => 'número',
        'cep' => 'CEP',
    ],
];
