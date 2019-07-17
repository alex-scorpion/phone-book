<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Phone;
use Core\Http\Controllers\Controller;
use App\Repository\ContactRepository;
use Zend\Diactoros\ServerRequest;

class ContactsController extends Controller
{
    private $contacts;

    public function __construct()
    {
        $this->contacts = new ContactRepository();
    }

    public function list(ServerRequest $request)
    {
        $data = $request->getQueryParams();
        $result = [
            'data' => [],
            'meta' => [
                'page'  => (int)$data['page'],
                'total' => $this->contacts->findContactsCount($data['search'])
            ]
        ];

        foreach ($this->contacts->findContacts($data['page'], $data['count'], $data['search']) as $contact) {
            /** @var Contact $contact */
            $result['data'][] = $contact->toArray(true);
        }

        return $this->jsonResponse($result);
    }

    public function show($id = 0)
    {
        if ($contact = $this->contacts->findContact($id)) {
            /** @var Contact $contact */
            return $this->jsonResponseData($contact->toArray(true));
        }

        return $this->emptyResponse(404);
    }

    public function store(ServerRequest $request)
    {
        $postData = $request->getParsedBody();

        $contact = new Contact($postData['contact']['name'], $postData['contact']['description']);
        $phones = [];
        foreach ($postData['phones'] as $postPhone) {
            $phone = new Phone($contact, $postPhone['phone']);
            $contact->addPhone($phone);
            $phones[] = $phone;
        }

        $this->contacts->createOrUpdateContact($contact, $phones);

        return $this->emptyResponse(201);
    }

    public function update(ServerRequest $request, $id)
    {
        if ($contact = $this->contacts->findContact($id)) {
            /** @var Contact $contact */
            $postData = $request->getParsedBody();
            $contact->update($postData['contact']['name'], $postData['contact']['description']);
            $phones = [];
            foreach ($postData['phones'] as $postPhone) {
                $phone = new Phone($contact, $postPhone['phone']);
                $contact->addPhone($phone);
                $phones[] = $phone;
            }
            $this->contacts->createOrUpdateContact($contact, $phones);
            return $this->emptyResponse(200);
        }

        return $this->emptyResponse(404);
    }

    public function destroy($id = 0)
    {
        if ($contact = $this->contacts->findContact($id)) {
            /** @var Contact $contact */
            $this->contacts->delete($contact);
            $code = 200;
        }

        return $this->emptyResponse($code ?? 404);
    }
}
