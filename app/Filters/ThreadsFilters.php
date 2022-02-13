<?php
namespace App\Filters;

use App\User;

// builds an eloquent query to pass to the
// $threads->filter( ** $builder_goes here ** )   ->get();

class ThreadsFilters extends Filters {

    //filters we can respond to
    protected $filters = ['by', 'popular'];

    protected function by($username){
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    protected function popular()
    {
        $this->builder->getQuery()->orders = [];

        $this->builder->orderBy('replies_count', 'desc');
    }

}