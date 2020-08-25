<?php

namespace Ignite\User\Controllers;

use Ignite\Support\IndexTable;
use Ignite\User\Models\User;
use Ignite\User\Requests\UserDeleteRequest;
use Ignite\User\Requests\UserReadRequest;
use Illuminate\Http\Request;

class FjordUserController
{
    /**
     * Create new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = lit()->config('user.user_index');
    }

    /**
     * Show user index.
     *
     * @param  UserReadRequest $request
     * @return void
     */
    public function showIndex(UserReadRequest $request)
    {
        $config = $this->config->get(
            'sortBy',
            'sortByDefault',
            'perPage',
            'index',
            'filter'
        );

        return view('litstack::app')->withComponent('lit-users')
            ->withTitle('Users')
            ->withProps([
                'config' => $config,
            ]);
    }

    /**
     * Delete multiple users.
     *
     * @param Request $request
     *
     * @return void
     */
    public function deleteAll(UserDeleteRequest $request)
    {
        IndexTable::deleteSelected(User::class, $request);

        return response([
            'message' => __lit('lit.deleted_all', [
                'count' => count($request->ids),
            ]),
        ]);
    }

    /**
     * Fetch index.
     *
     * @param Request $request
     *
     * @return array
     */
    public function fetchIndex(UserReadRequest $request)
    {
        $query = $this->config->index_query
            ->with('ordered_roles');

        return IndexTable::query($query)
            ->request($request)
            ->search($this->config->search)
            ->get();
    }
}
