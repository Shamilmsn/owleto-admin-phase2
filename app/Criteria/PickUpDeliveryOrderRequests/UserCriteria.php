<?php
/**
 * File name: OrdersOfUserCriteria.php
 * Last modified: 2020.04.30 at 08:24:08
 * Author: Pixbit Solutions - https://pixbitsolutions.com
 * Copyright (c) 2020
 *
 */

namespace App\Criteria\PickUpDeliveryOrderRequests;

use App\Models\User;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class OrdersOfUserCriteria.
 *
 * @package namespace App\Criteria\Orders;
 */
class UserCriteria implements CriteriaInterface
{
    /**
     * @var User
     */
    private $userId;

    /**
     * OrdersOfUserCriteria constructor.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
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
        return $model->where('user_id', $this->userId);
    }
}
