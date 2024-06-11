<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function store(StoreProjectRequest $request)
    {

        $project = DB::transaction(function () use ($request) {
            $project = Project::create($request->all());
            if ($request->has('tags')) {
                $tags = collect( explode(',', $request->tags))->map(function ($tag) use ($project) {
                    return ['name' => trim($tag), 'project_id' => $project->id];
                });
                $project->tags()->createMany($tags->toArray());
            }

            if ($request->hasFile('image')) {
                $project->addMedia($request->image)->toMediaCollection('images');
            }
            return $project;
        });
        return ProjectResource::make($project);
    }

    public function index(Request $request)
    {
        $projects = QueryBuilder::for(Project::class)
            ->allowedFilters('name', 'role')
            ->with('tags')
            ->orderBy('created_at', 'desc')
            ->get();
        return ProjectResource::collection($projects);
    }
}
