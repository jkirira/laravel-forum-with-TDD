<?php
namespace App\Filters;


use Illuminate\Http\Request;


abstract class Filters
{

    protected $request, $builder;

    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        // $builder is the query passed from the 'scopeFilter' method on the thread model in thread.php
        $this->builder = $builder;

        collect($this->getFilters())
            //get filters on the request object as $filter and $value
            // filter collection down to only the items where
            // there is a method on the child filter class of the same name as the filter
            ->filter(function($value, $filter){
                return (method_exists($this, $filter));
            })
            //for each one found we trigger the function of the same name
            // and pass the value from the request object to the function
            ->each(function($value, $filter){
                $this->$filter($value);
            });

//        foreach ($this->getFilters() as $filter => $value)
//        {
//            if(method_exists($this, $filter))
//            {
//                $this->$filter($value);
//            }
//        }

        return $this->builder;
    }

    protected function getFilters()
    {
//        $filters = array_intersect(array_keys($this->request->all()), $this->filters);
//        return $this->request->only($filters);

        return array_filter($this->request->only($this->filters));
    }

}