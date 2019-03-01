<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'categoria'
    ];
    function produto()
    {
        return $this->hasMany('App\Produto','categoria_id');
    }

    public function rules($id){
        return([
            'categoria'=>'required|unique:categorias,categoria'.($id == null ? '' : ',' . $id),
        ]);
    }

}
