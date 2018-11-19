<?php

namespace App\Controllers\Search;

use Illuminate\Database\Capsule\Manager as DB;
use App\Models\Clinic;
use App\Models\User;
use App\Controllers\Controller;
use App\Transformers\ClinicTransformer;
use App\Transformers\UserTransformer;
use App\Files\FileStore;
use League\Fractal\{
  Resource\Item,
  Resource\Collection,
  Pagination\IlluminatePaginatorAdapter
};
use Respect\validation\validator as V;

class SearchController extends Controller
{
  public function index($request, $response, $args)
  {
    $userId = optional($this->auth->requestUser($request))->id;

    if ($request->getParam('type') && $request->getParam('type') === 'clinic') {
      $clinics = Clinic::where('name', 'LIKE', '%' . $args['query']. '%')
        ->orWhere('city', 'LIKE', '%' . $args['query'] . '%')
        ->get();

      $transformer = (new Collection($clinics, new ClinicTransformer($userId)));
  
      $data = $this->container->fractal->createData($transformer)->toArray();
  
      return $response->withJson($data, 200);
    }

    if ($request->getParam('type') && $request->getParam('type') === 'user') {
      $users = User::where('first_name', 'LIKE', '%' . $args['query'] . '%')
        ->orWhere('last_name', 'LIKE', '%' . $args['query'] . '%')
        ->orWhere('legal_name', 'LIKE',  '%' . $args['query'] . '%')
        ->get();

      $transformer = (new Collection($users, new UserTransformer));

      $data = $this->container->fractal->createData($transformer)->toArray();
  
      return $response->withJson($data, 200);
    }
  }
}
