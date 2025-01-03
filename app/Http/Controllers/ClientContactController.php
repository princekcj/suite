<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\ClientContacts\StoreContact;
use App\Http\Requests\ClientContacts\UpdateContact;
use App\Models\ClientContact;
use Illuminate\Http\Request;

class ClientContactController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.clients';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('clients', $this->user->modules));

            return $next($request);
        });
    }

    public function create()
    {
        $this->pageTitle = __('app.addContact');
        $this->addClientPermission = user()->permission('add_client_contacts');

        abort_403(!in_array($this->addClientPermission, ['all', 'added']));

        $this->clientId = request('client');

        $this->view = 'clients.contacts.create';

        if (request()->ajax()) {
            return $this->returnAjax($this->view);
        }

        return view('clients.create', $this->data);
    }

    public function store(StoreContact $request)
    {
        $contact = ClientContact::create($request->all());

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => route('clients.show', $contact->user_id) . '?tab=contacts']);
    }

    public function show($id)
    {

        $this->contact = ClientContact::findOrFail($id);
        $this->pageTitle = __('app.showContact');

        $this->viewPermission = user()->permission('view_client_contacts');

        abort_403(!($this->viewPermission == 'all'
            || ($this->viewPermission == 'added' && $this->contact->client->clientDetails->added_by == user()->id)
            || ($this->viewPermission == 'both' && $this->contact->client->clientDetails->added_by == user()->id)));


        $this->view = 'clients.show';

        if (request()->ajax()) {
            return $this->returnAjax($this->view);
        }

        return redirect(route('clients.show', $this->contact->user_id) . '?tab=contacts');

    }

    public function edit($id)
    {
        $this->pageTitle = __('app.editContact');
        $this->contact = ClientContact::findOrFail($id);

        $this->editPermission = user()->permission('edit_client_contacts');


        abort_403(!($this->editPermission == 'all'
            || ($this->editPermission == 'added' && $this->contact->client->clientDetails->added_by == user()->id)
            || ($this->editPermission == 'both' && $this->contact->client->clientDetails->added_by == user()->id)));


        $this->clientId = $this->contact->user_id;


        $this->view = 'clients.contacts.edit';

        if (request()->ajax()) {
            return $this->returnAjax($this->view);
        }

        return view('clients.create', $this->data);

    }

    public function update(UpdateContact $request, $id)
    {
        $contact = ClientContact::findOrFail($id);
        $contact->update($request->all());

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => route('clients.show', $contact->user_id) . '?tab=contacts']);
    }

    public function destroy($id)
    {
        $this->contact = ClientContact::findOrFail($id);
        $this->deletePermission = user()->permission('delete_client_contacts');

        if (
            $this->deletePermission == 'all'
            || ($this->deletePermission == 'added' && $this->contact->client->clientDetails->added_by == user()->id)
            || ($this->deletePermission == 'both' && $this->contact->client->clientDetails->added_by == user()->id)
        ) {
            $this->contact->delete();
        }

        return Reply::success(__('messages.deleteSuccess'));
    }

    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
        case 'delete':
            $this->deleteRecords($request);

            return Reply::success(__('messages.deleteSuccess'));
        default:
            return Reply::error(__('messages.selectAction'));
        }
    }

    protected function deleteRecords($request)
    {
        abort_403(user()->permission('delete_clients') !== 'all');
        ClientContact::whereIn('id', explode(',', $request->row_ids))->delete();

        return true;
    }

}
