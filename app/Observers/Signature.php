<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

class Signature
{
    public function getId(){
        return Auth::id() ? Auth::id() : 0;
    }

    public function creating($model)
    {
        $model->created_by = $this->getId();
        $model->updated_by = $this->getId();
    }

    public function updating($model)
    {
        $model->updated_by = $this->getId();
    }


    public function removing($model)
    {
        $model->deleted_by = $this->getId();
    }
}
