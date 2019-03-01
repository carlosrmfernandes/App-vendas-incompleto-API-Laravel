<?php

namespace App\Repositories\Categoria;

use Illuminate\Support\Facades\DB;
use App\Categoria;
use App\Produto;

class CategoriaRepository {

    public static function totais() {

    	$categoria = Categoria::count();

        $produto = Produto::select([
            DB::raw('count(produtos.id) as produto'),
            DB::raw('sum(produtos.preco) as preco'),
        ])->first();

        return ['categoria'=>$categoria,'produto'=>$produto];
       
        }
    }
