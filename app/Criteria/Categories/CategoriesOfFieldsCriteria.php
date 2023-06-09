<?php
/**
 * File name: CategoriesOfFieldsCriteria.php
 * Last modified: 2020.05.04 at 09:04:18
 * Author: Pixbit Solutions - https://pixbitsolutions.com
 * Copyright (c) 2020
 *
 */

namespace App\Criteria\Categories;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CategoriesOfFieldsCriteria.
 *
 * @package namespace App\Criteria\Categories;
 */
class CategoriesOfFieldsCriteria implements CriteriaInterface
{

    /**
     * @var array
     */
    private $request;

    /**
     * CategoriesOfFieldsCriteria constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (!$this->request->has('fields')) {
            return $model;
        } else {
            $fields = $this->request->get('fields');
            if (in_array('0', $fields)) { // means all fields
                return $model;
            }
            return $model->join('products', 'products.category_id', '=', 'categories.id')
//                ->join('market_fields', 'market_fields.market_id', '=', 'products.market_id')
                ->whereIn('products.sector_id', $this->request->get('fields', []))->select('categories.*')->groupBy('categories.id');
        }
    }
}
