<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class CustomerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(App\User::class)->make();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateSuccess()
    {
        $input = factory(App\Customer::class)->make()->toArray();

        $this->actingAs($this->user)
            ->visit('customers/create')
            ->submitForm('Save', $input)
            ->see('Customer created!')
            ->seePageIs('customers');
    }

    public function testCreateDuplicateCustomerEmail()
    {
        factory(App\Customer::class)->create(['email' => 'customer@example.com']);

        $input = factory(App\Customer::class)->make([
            'email' => 'customer@example.com',
        ])->toArray();

        $this->actingAs($this->user)
            ->visit('customers/create')
            ->submitForm('Save', $input)
            ->see('The email has already been taken.')
            ->seePageIs('customers/create');
    }

    public function testEditDataAvailable()
    {
        factory(App\Customer::class)->create();

        $this->actingAs($this->user)
            ->visit('customers/1/edit')
            ->see('Customers - Edit');
    }

    public function testEditDataNotFound()
    {
        $this->actingAs($this->user)
            ->get('customers/1/edit')
            ->assertResponseStatus(404);
    }

    public function testUpdateSuccess()
    {
        factory(App\Customer::class)->create(['name' => 'Customer Tests']);

        $input = factory(App\Customer::class)->make()->toArray();

        $this->actingAs($this->user)
            ->visit('customers/1/edit')
            ->submitForm('Update', $input)
            ->see('Customer updated!')
            ->seePageIs('customers');
    }

    public function testDeleteSuccess()
    {
        factory(App\Customer::class)->create(['name' => 'Customer Tests']);

        $this->actingAs($this->user)
            ->visit('customers')
            ->submitForm('Delete')
            ->see('Customer deleted!')
            ->seePageIs('customers');
    }
}
