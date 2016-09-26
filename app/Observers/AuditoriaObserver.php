<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class AuditoriaObserver
{
    //creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored

    private $id_usuario_logado;

    public function __construct(){
      $this->id_usuario_logado = 1;
    }

    public function creating(Model $model)
    {
      $model->id_usuario_inclusao = $this->id_usuario_logado;
      $model->id_usuario_alteracao = $this->id_usuario_logado;
    }

    public function created(Model $model)
    {
        //
    }

    public function updating(Model $model)
    {
        $model->id_usuario_alteracao = $this->id_usuario_logado;
    }


    public function deleting(Model $model)
    {
        //
    }
}
