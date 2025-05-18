<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    protected $model;
    public function __construct()
    {
        $this->model = new Customer();
    }

    public function create($request)
    {
        return $this->model->create($request->all());
    }

    public function update($data, $id)
    {
        $model = $this->fetch($id);
        return $model->fill($data)->save();
    }

    public function delete($id)
    {
        $model = $this->fetch($id);
        return $model->delete();
    }

    public function fetch($id, $columns = ['*'])
    {
        return $this->model->select($columns)->findOrFail($id);
    }

    public function fetchAll($request)
    {
        return $this->model->when($request->has('customer_id') && $request->customer_id, function ($query) use ($request) {
            return $query->where('id', $request->input('customer_id'));
        })->when($request->has('name') && $request->name, function ($query) use ($request) {
            return $query->where('first_name', 'LIKE', "%{$request->input('name')}%");
        })->when($request->has('type'), function ($query) use ($request) {
            return $query->where('type', $request->input('type'));
        })->when($request->has('status'), function ($query) use ($request) {
            return $query->where('status', (int)$request->input('status'));
        })->orderBy('created_at', 'desc')->paginate(config('constants.pagination_limit'));
    }

    public function fetchAllWithRelation($relations = [])
    {
        return $this->model->with($relations)->get();
    }

    public function fetchWithRelation($id, $relations = [])
    {
        return $this->model->with($relations)->find($id);
    }

    public function getDetail($id)
    {
        $customer_columns      = ['id', 'type', 'first_name', 'profile_picture', 'email', 'city_id', 'area', 'mobile_phone_number', 'is_notification_enabled', 'available_balance', 'status', 'email_verified_at', 'number_verified_at'];
        $city_columns          = ['id', 'name'];
        $advertisement_columns = ['id', 'title', 'description', 'is_sold', 'status', 'is_sponsored', 'price', 'customer_id'];

        return $this->model->select($customer_columns)->with([
            'city' => function ($query) use ($city_columns) {
                $query->select($city_columns);
            }, 'transactionHistories' => function ($query) {
                $query->with('model')->limit(config('constants.show_page_limit'));
            }, 'advertisements' => function ($query) use ($advertisement_columns) {
                $query->select($advertisement_columns)->limit(config('constants.show_page_limit'));
            },
            'advertisementLikes' => function ($query) use ($advertisement_columns) {
                $query->select('id', 'advertisement_id', 'customer_id', 'updated_at')->where('is_liked', true)
                    ->with(['advertisement' => function ($query)  use ($advertisement_columns) {
                        $query->select($advertisement_columns);
                    }])->limit(config('constants.show_page_limit'));
            }
        ])->withCount(['advertisements', 'advertisementLikes' => function ($query) {
            $query->where('is_liked', true);
        }])->findOrFail($id);
    }
}
