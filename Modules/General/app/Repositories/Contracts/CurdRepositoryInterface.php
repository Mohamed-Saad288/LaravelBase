<?php

namespace Modules\General\app\Repositories\Contracts;

interface CurdRepositoryInterface
{
    /**
     * Display a listing of the resource.
     * @param array $data
     * @return object
     */
    public function index(array $data) : object;
    /**
     * Store a newly created resource in storage.
     * @param array $data
     * @return object
     */
    public function store(array $data) : object;

    /**
     * Show the specified resource.
     * @param int $id
     * @return object
     */
    public function show(int $id) : object;
    /**
     * Update the specified resource in storage.
     * @param array $data
     * @param int $id
     * @return object
     */
    public function update(array $data,int $id) : object;
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return bool
     */
    public function destroy(int $id) : bool;
}
