<?php

namespace App\Http\Controllers;

use App\Admin\Fields\Avatar;
use App\Admin\Fields\BelongsTo;
use App\Flight;
use App\User;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function index(Request $request, $resourceName)
    {
        $resource = $this->getResource($resourceName);
        $fields = $resource::indexFields();
        /** @var QueryBuilder $query */
        $query = $resource::query();
        $searchable = [];
        $sortable = [];

        foreach ($fields as $field) {
            $field->value = $field->attribute;
            if ($field->searchable)
                $searchable[] = $field;

            if ($field->sortable)
                $sortable[] = $field;
        }

        if ($request->search) {
            foreach ($searchable as $field) {
                $query = $query->orWhere($field->searchColumn(), "like", "%" . $request->search . "%");
            }
        }

        if ($request->options) {
            $options = json_decode($request->options);
            foreach ($options->sortBy as $index => $attribute) {
                $desc = $options->sortDesc[$index];
                $field = array_filter($fields, function ($field) use ($attribute) {
                    return $field->attribute == $attribute;
                });
                $field = array_pop($field);
                if ($field)
                    $query = $query->orderBy($field->sortColumn(), $desc ? "desc" : "asc");
            }
        }

        $query = $query->paginate(10);
        $items = $query->items();
        return ["label" => $resource::label(),
            "singularLabel" => $resource::singularLabel(),
            "headers" => $fields,
            "items" => $items,
            "total" => $query->total(),];
    }


    public function detail($resourceName, $resourceId)
    {

        $resource = $this->getResource($resourceName);
        $fields = $resource::detailFields();

        $item = $resource::query()->find($resourceId);
        return [
            "label" => $resource::label(),
            "singularLabel" => $resource::singularLabel(),
            "fields" => $fields,
            "item" => $item
        ];
    }

    public function form($resourceName, $resourceId = null)
    {
        $resource = $this->getResource($resourceName);
        if ($resourceId) {
            $fields = $resource::updateFields();
        } else {
            $fields = $resource::creationFields();
        }

        $item = $resourceId ? $resource::query()->find($resourceId) : ($resource::$model)::skeleton();

        return [
            "label" => $resource::label(),
            "singularLabel" => $resource::singularLabel(),
            "fields" => $fields,
            "item" => $item
        ];
    }

    public function create(Request $request, $resourceName)
    {
        $resource = $this->getResource($resourceName);
        $fields = $resource::creationFields();
        $validator = $this->validateFields($fields, $request);
        if ($validator->fails()) {
            return Response::json([
                "errors" => $validator->errors(),
                "message" => "The given data was invalid"
            ])->setStatusCode(422);
        }
        $model = $resource::$model;
        $item = new $model;
        $item = $this->fill($item, $fields);

        $item->save();
        return Response::json([
            "item" => $item,
            "message" => "The " . Str::lower($resource::singularLabel()) . " was created!"
        ]);
    }


    public function update(Request $request, $resourceName, $resourceId)
    {
        $resource = $this->getResource($resourceName);
        $fields = $resource::updateFields();

        $validator = $this->validateFields($fields, $request, true);
        if ($validator->fails()) {
            return Response::json([
                "errors" => $validator->errors(),
                "message" => "The given data was invalid"
            ])->setStatusCode(422);
        }

        $item = $resource::query()->find($resourceId);
        $item = $this->fill($item, $fields);
        $item->save();

        return Response::json([
            "item" => $item,
            "message" => "The " . Str::lower($resource::singularLabel()) . " was updated!"
        ]);
    }

    public function dropResource(Request $request, $resourceName, $resourceId)
    {
        $resource = $this->getResource($resourceName);
        $item = $resource::query()->find($resourceId);
        $item->delete();
        return Response::json([
            "message" => "The " . Str::lower($resource::singularLabel()) . " was deleted!"
        ]);
    }

    public function validateFields($fields, Request $request, $update = false)
    {
        $rules = [];
        foreach ($fields as $field) {
            $field->value = $request->{$field->attribute};
            $field_rules = $update ? $field->updateRules : $field->creationRules;
            if (count($field_rules))
                $rules[$field->attribute] = $field_rules;
        }

        return Validator::make($request->all(), $rules);


    }

    public function getResource($resourceName)
    {
        $className = Str::singular($resourceName);
        $className = Str::title($className);
        $className = Str::replaceArray("-", ["", ""], $className);
        return "\\App\\Admin\\$className";
    }

    public function fill($item, $fields)
    {
        foreach ($fields as $field) {
            $field->fill($item);
        }
        return $item;
    }

    public function login(Request $request)
    {
        $rules = [
            "email" => "required|email",
            "password" => "required"
        ];

        $this->validate($request, $rules);
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $device = Str::random(6);
        return Response::json([
            "token" => $user->createToken($device)->plainTextToken,
            "user" => $user,
        ]);
    }

    public function classes(Flight $flight)
    {
        return Response::json([
            "classes" => $flight->classes,
        ]);

    }
}
