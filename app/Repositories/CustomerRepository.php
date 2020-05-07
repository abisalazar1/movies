<?php

namespace App\Repositories;

use App\Customer;

class CustomerRepository extends Repository
{
    /**
     * CustomerRepository Constructor
     *
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }

    /**
     * Index
     *
     * @return Collection
     */
    public function index()
    {
        return $this->model->paginate();
    }

    /**
     * Findsa customer with the specific email address
     *
     * @param string $email
     *
     * @return Customer
     */
    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }
}
