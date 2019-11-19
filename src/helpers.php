<?php

use KABBOUCHI\TableView\TableView;

if (! function_exists('tableView')) {
    /**
     * @param \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Builder $data
     * @return TableView
     */
    function tableView($data)
    {

        if(isset($_GET['sort'])){

            if(isset($_GET['desc'])){

                $data = $data->sortByDesc($_GET['sort']);

            }else{

                $data = $data->sortBy($_GET['sort']);

            }

        }

        return new TableView($data);

    }

}
