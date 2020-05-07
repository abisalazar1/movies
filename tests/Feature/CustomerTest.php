<?php

namespace Tests\Feature;

use App\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Customer list customer.
     *
     * @test
     *
     * @return void
     */
    public function can_visit_customer_index_page()
    {
        $response = $this->call('get', 'api/customers');


        $response->assertStatus(200);
    }

    /**
     * show a single customer.
     *
     * @test
     *
     * @return void
     */
    public function can_visit_a_customer_page()
    {
        $cusotmer = factory(Customer::class)->create([
            'email' => 'test@test.com',
            'name' => 'test customer',
        ]);

        $response = $this->call('get', 'api/customers/' . $cusotmer->id);


        $response->assertStatus(200);
    }

    /**
     * cant show a customer page that does not exist.
     *
     * @test
     *
     * @return void
     */
    public function cant_visit_a_customer_page_that_doesnt_exist()
    {
        $response = $this->call('get', 'api/customers/1');

        $response->assertStatus(404);
    }

    /**
     * A customer can be updated
     *
     * @test
     *
     * @return void
     */
    public function customer_can_be_updated()
    {
        $cusotmer = factory(Customer::class)->create([
            'email' => 'test@test.com',
            'name' => 'test customer',
        ]);


        $this->call('put', 'api/customers/' . $cusotmer->id, [
            'email' => 'test@updated.com',
            'name' => 'test customer updated'
            ]);
            
        $this->assertDatabaseHas('customers', [
            'id' => $cusotmer->id,
            'email' => 'test@updated.com',
            'name' => 'test customer updated'
        ]);
    }

    /**
     * A customer can be created
     *
     * @test
     *
     * @return void
     */
    public function customer_can_be_created()
    {
        $this->call('post', 'api/customers', [
            'email' => 'test@test.com',
            'name' => 'test customer'
        ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'test@test.com',
        ]);
    }

    /**
     * A customer can be deleted
     *
     * @test
     *
     * @return void
     */
    public function customer_can_be_deleted()
    {
        $cusotmer = factory(Customer::class)->create([
            'email' => 'test@test.com',
            'name' => 'test customer',
        ]);

        $this->call('delete', 'api/customers/' . $cusotmer->id);

        $this->assertDatabaseMissing('customers', [
            'email' => 'test@test.com',
        ]);
    }
}
