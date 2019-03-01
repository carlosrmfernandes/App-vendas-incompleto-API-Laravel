<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Produto;
use Validator;
use Illuminate\Http\Request;
use App\Repositories\Categoria\CategoriaRepository;

class CategoriaController extends Controller
{

    function get($id=null)
    {
        $query = Categoria::query();
        if (isset($id)) {
            $query->with('produto');
            $categoria = $query->find($id);
            
            if(!$categoria){
                return response()->json(['categoria' => 'Nenhum item encontrado']);
            }

            return response()->json($categoria);
        }
        $categoria = $query->orderBy('categoria', 'asc')->get();
        return response()->json(['categoria' => $categoria]);
    }

    function create(Request $request)
    {   
        $menssagem;
        $notDelete = [];

        if (isset($request->id)) {
            $menssagem="Categoria atualizada com sucesso";
            $categoria=Categoria::find($request->id);
        } else {
            $menssagem="Categoria criado com sucesso";
            $categoria = new Categoria();
        }

        $validator = Validator::make($request->all(), $categoria->rules($request->id),$this->menssagem());

        if ($validator->fails()) {
            return response()->json(['erro'=>$validator->errors()]);
        }

        $categoria->fill($request->all());
        $categoria->save();

        if(isset($request->itens)){

            foreach($request->itens as $iten){

            $produto = Produto::firstOrNew(['id'=>$iten['id']]);
            $produto->produto=$iten['produto'];
            $produto->preco=$iten['preco'];
            $produto->categoria_id=$categoria->id;
            $produto->save();
            $notDelete[]=$produto->id;

           }

           Produto::where('categoria_id',$categoria->id)->whereNotIn('id', $notDelete)->get()->each(function($obj) {
            $obj->delete();
        });
        }
        return response()->json($menssagem);
    }

    function destroy(Request $request)
    {
        $ids=$request->ids;
        if(empty($ids)){
            return response()->json(['categoria'=>'Nenhum item selecionado']);
        }
        Produto::whereIn('categoria_id',$ids)->get()->each(function($ob){
            $ob->delete();
        });
        Categoria::whereIn('id',$ids)->get()->each(function($ob){
            $ob->delete();
        });
        return response()->json(['categoria'=>'Categoria removido com sucesso']);
    }

    public function menssagem(){
        $mansage=[
            'categoria.required'=>"Categória é obrigatório",
            'categoria.unique'=>"Categória já existe",
        ];

        return $mansage;
    }
    public function totaisCategoriasProdutos(){
        $totais = CategoriaRepository::totais();
       return response()->json(['totalCategoria'=>$totais['categoria'],'totaisProduto'=>$totais['produto']]);
    }
}
