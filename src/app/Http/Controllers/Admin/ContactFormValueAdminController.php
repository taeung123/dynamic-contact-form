<?php
namespace VCComponent\Laravel\ConfigContact\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormValueRepository;
use VCComponent\Laravel\ConfigContact\Traits\CheckRequestMethods;
use VCComponent\Laravel\ConfigContact\Traits\Helpers;
use VCComponent\Laravel\ConfigContact\Transformers\ContactFormValueTransformer;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

class ContactFormValueAdminController extends ApiController
{
    use CheckRequestMethods, Helpers;

    protected $contact_form_value_repository;
    protected $contact_form_value_transformer;
    protected $contact_form_value_entity;

    public function __construct(ContactFormValueRepository $contact_form_value_repository, ContactFormValueTransformer $contact_form_value_transformer)
    {
        $this->contact_form_value_repository  = $contact_form_value_repository;
        $this->contact_form_value_transformer = $contact_form_value_transformer;
        $this->contact_form_value_entity      = $contact_form_value_repository->getEntity();

        if (!empty(config('dynamic-contact-form.auth_middleware.admin'))) {
            $user = $this->getAuthenticatedUser();
            if (!$this->contact_form_value_entity->ableToUse($user)) {
                throw new PermissionDeniedException();
            }
            foreach (config('dynamic-contact-form.auth_middleware.admin') as $middleware) {
                $this->middleware($middleware['middleware'], ['except' => $middleware['except']]);
            }
        }
    }

    public function index(Request $request)
    {
        $perpage            = $request->has('per_page') ? $request->get('per_page') : 15;
        $contact_form_value = $this->contact_form_value_repository->orderBy('id', 'desc')->paginate($perpage);

        return $this->response->paginator($contact_form_value, new $this->contact_form_value_transformer);
    }

    public function show($id)
    {
        $contact_form_value = $this->contact_form_value_entity->find($id);

        if (!$contact_form_value) {
            throw new Exception('Contact form value does not exist');
        }

        $contact_form_value = $this->contact_form_value_repository->find($id);

        return $this->response->item($contact_form_value, new $this->contact_form_value_transformer);
    }

    public function update(Request $request, $id)
    {
        $contact_form_value = $this->contact_form_value_entity->find($id);

        if (!$contact_form_value) {
            throw new Exception('Contact form value does not exist');
        }

        $data               = $request->all();
        $data['payload']    = json_encode($request->payload);
        $contact_form_value = $this->contact_form_value_repository->update($data, $id);

        return $this->response->item($contact_form_value, $this->contact_form_value_transformer);
    }

    public function destroy($id)
    {
        $contact_form_value = $this->contact_form_value_entity->find($id);

        if (!$contact_form_value) {
            throw new Exception('Contact form value does not exist');
        }

        $contact_form_value->destroy($id);

        return $this->success();
    }

    public function getPayload(Request $request, $id)
    {
        $contact_form_value = $this->contact_form_value_entity->where('contact_form_id', $id)->exists();

        if (!$contact_form_value) {
            $contact_form_value = ['data' => array()];
            return response()->json($contact_form_value);
        }

        $query              = $this->contact_form_value_entity;
        $query              = $this->checkStatusRequest($request, $query);
        $query              = $query->where('contact_form_id', $id)->orderBy('id', 'desc');
        $query              = $this->checkPerPageRequest($request, $query);
        $contact_form_value = $query;

        if ($request->has('search')) {
            return $this->searchPayload($request, $id);
        }

        return $this->response->paginator($contact_form_value, $this->contact_form_value_transformer);
    }

    public function changeStatus(Request $request, $id)
    {
        $contact_form_value = $this->contact_form_value_entity->find($id);

        if (!$contact_form_value) {
            throw new Exception('Contact form value does not exist');
        }

        $data               = $request->all();
        $contact_form_value = $this->contact_form_value_repository->update($data, $id);

        return $this->response->item($contact_form_value, $this->contact_form_value_transformer);
    }

    public function searchPayload(Request $request, $id)
    {
        $query    = $this->contact_form_value_entity->where('contact_form_id', $id);
        $query    = $this->checkStatusRequest($request, $query);
        $payloads = $query->pluck('id', 'payload')->toArray();
        $search   = $request->get('search');
        $per_page = $request->has('per_page') ? $request->get('per_page') : 15;

        $data = [];
        foreach ($payloads as $key => $value) {
            $data[$value] = $this->removeSpecialCharacter(mb_strtolower($key));
        }

        $record_has_search = [];
        foreach ($data as $key => $value) {
            if (!$search) {
                $payloads = $query->orderBy('id', 'desc')->paginate($per_page);
                return $this->response->paginator($payloads, $this->contact_form_value_transformer);
            }
            if (strpos(mb_strtolower($value), mb_strtolower($this->removeSpecialCharacter($search))) !== false) {
                $record_has_search[] = [$key];
            }
        }

        if (count($record_has_search) > 0) {
            $payloads = $query->orderBy('id', 'desc')->whereIn('id', $record_has_search)->paginate($per_page);
        } else {
            $payloads = [
                'data' => [],
            ];
            return response()->json($payloads);
        }

        return $this->response->paginator($payloads, $this->contact_form_value_transformer);
    }
}
