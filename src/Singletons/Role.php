<?php

namespace Laracms\Singletons;

class Role
{
    /**
     * Define list of role
     *
     * @var array
     */
    private $roles;

    /**
     * Create a new service provider instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->roles = config('laraveladmin.roles');
    }

    /**
     * Get role list
     */
    public function list()
    {
        return (array) @array_keys($this->roles);
    }

    /**
     * Get admin access list
     */
    public function admins()
    {
        $data = array_filter($this->roles, function($role) {
            return (@$role['admin_access'] === true);
        });
        return (array) @array_keys($data);
    }

    /**
     * Get admin not access list
     */
    public function users()
    {
        $data = array_filter($this->roles, function($role) {
            return (@$role['admin_access'] != true);
        });
        return (array) @array_keys($data);
    }
}