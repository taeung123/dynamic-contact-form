<?php
namespace VCComponent\Laravel\ConfigContact\Traits;

use Illuminate\Http\Request;

trait CheckRequestMethods
{
    public function checkStatusRequest(Request $request, $query)
    {

        if ($request->has('status')) {
            return $query->where('status', $request->get('status'));
        }

        return $query;
    }

    public function checkSearchRequest(Request $request, $field, $query)
    {
        if ($request->has('search')) {
            if (is_array($field)) {
                foreach ($field as $column) {
                    $query = $query->orWhere($column, "like", "%" . $request->get('search') . "%");
                }
                return $query;
            }

            return $query->where($field, "like", "%" . $request->get('search') . "%");
        }

        return $query;
    }

    public function checkPerPageRequest(Request $request, $query)
    {
        if ($request->has('per_page')) {
            return $query->paginate($request->get('per_page'));
        }

        return $query->paginate(10);
    }
}
